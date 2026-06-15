<?php
function test_bing_or_ddg($query, $filepath) {
    $url = "https://www.bing.com/images/search?q=" . urlencode($query);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Bing response code: $http_code\n";
    
    // In Bing Images, the image metadata is stored in `m="{"ns":"...", "murl":"https://..."}"` or class="iusc"
    // Let's search for murl or similar.
    if (preg_match_all('/murl&quot;:&quot;(http[^&]+)&quot;/', $html, $matches)) {
        $img_urls = $matches[1];
        if (!empty($img_urls)) {
            foreach ($img_urls as $img_url) {
                if (filter_var($img_url, FILTER_VALIDATE_URL)) {
                    echo "Found Bing image URL: $img_url\n";
                    // Try downloading
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $img_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36");
                    $img_data = curl_exec($ch);
                    $img_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    
                    if ($img_http_code == 200 && $img_data) {
                        file_put_contents($filepath, $img_data);
                        echo "Successfully downloaded from Bing and saved to: $filepath\n";
                        return true;
                    } else {
                        echo "Failed to download $img_url (HTTP Code: $img_http_code)\n";
                    }
                }
            }
        }
    }
    
    echo "No image found in Bing search for: $query. Testing DuckDuckGo...\n";
    
    // Test DuckDuckGo HTML
    $url = "https://html.duckduckgo.com/html/?q=" . urlencode($query);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "DuckDuckGo response code: $http_code\n";
    // DuckDuckGo HTML contains normal web links, not image search directly, but we can extract page links or see if there is an image.
    // Actually, DuckDuckGo image search uses an API, but let's see if we can query it or another service.
    
    return false;
}

$test_query = "Bàn phím cơ Keychron K2 Pro";
$test_path = "c:\\xampp\\htdocs\\project\\scratch\\test_keychron.jpg";
test_bing_or_ddg($test_query, $test_path);
?>

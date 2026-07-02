<?php
session_start();

// Disable direct error output to keep JSON clean, but log errors
ini_set('display_errors', 0);
error_reporting(E_ALL);

include "../model/connectdb.php";

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

if (empty($q)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([]);
    exit;
}

$conn = connectdb();
if (!$conn) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Build base query
$sql = "SELECT p.id, p.name, p.price, p.old_price, p.img, p.description, c.name as cat_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.status = 1 AND p.name LIKE :query_like";

$params = ['query_like' => "%$q%"];

// Normalize and filter category
if (!empty($category) && $category !== 'Tất cả danh mục' && $category !== 'Tất cả danh mục lớn' && $category !== 'Tất cả') {
    $sql .= " AND c.name = :category";
    $params['category'] = $category;
}

// Order by relevance:
// 1. Exact match
// 2. Starts with query
// 3. Substring match
$sql .= " ORDER BY 
          CASE 
            WHEN p.name = :query_exact THEN 1
            WHEN p.name LIKE :query_starts THEN 2
            ELSE 3 
          END, 
          p.name ASC
          LIMIT 3";

$params['query_exact'] = $q;
$params['query_starts'] = "$q%";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Ensure correct relative image paths from web root
    foreach ($results as &$row) {
        if (!empty($row['img'])) {
            // If the path doesn't start with assets, check it
            // Typically img is stored as e.g. "assets/images/product/product_1.jpg"
            // Ensure no leading slash issues
            $row['img'] = ltrim($row['img'], '/');
        }
    }
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($results);
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $e->getMessage()]);
}
exit;

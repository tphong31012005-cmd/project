<?php
$c = file_get_contents('assets/css/style.css');
if (preg_match_all('/(?:header|middle-header|top-header)[^{]*{[^}]*overflow[^}]*}/i', $c, $m)) {
    print_r($m[0]);
} else {
    echo "No matches\n";
}

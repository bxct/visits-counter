<?php
// Database configuration
$host = 'mysql';
$dbname = $_ENV['MYSQL_DATABASE'] ?? 'dbname';
$username = $_ENV['MYSQL_USER'] ?? 'dbuser';
$password = $_ENV['MYSQL_PASSWORD'] ?? 'dbpassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $page_url = $_SERVER['HTTP_REFERER'] ?? 'direct';
    $view_date = date('Y-m-d H:i:s');
    
    $stmt = $pdo->prepare("SELECT views_count FROM visits WHERE ip_address = ? AND user_agent = ? AND page_url = ?");
    $stmt->execute([$ip_address, $user_agent, $page_url]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        $new_count = $existing['views_count'] + 1;
        $stmt = $pdo->prepare("UPDATE visits SET view_date = ?, views_count = ? WHERE ip_address = ? AND user_agent = ? AND page_url = ?");
        $stmt->execute([$view_date, $new_count, $ip_address, $user_agent, $page_url]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO visits (ip_address, user_agent, view_date, page_url, views_count) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$ip_address, $user_agent, $view_date, $page_url]);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}

header('Content-Type: image/png');
$image = imagecreate(400, 100);
$bg = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 255, 0, 0);
imagestring($image, 3, 80, 40, 'This is one big beautiful banner.', $text_color);
imagepng($image);
imagedestroy($image);

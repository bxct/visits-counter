<?php
// Database configuration
$host = 'mysql';
$dbname = $_ENV['MYSQL_DATABASE'] ?? 'dbname';
$username = $_ENV['MYSQL_USER'] ?? 'dbuser';
$password = $_ENV['MYSQL_PASSWORD'] ?? 'dbpassword';
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$ip_address = filter_var($_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP) ?? '0.0.0.0';
$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 4096) : '';
$page_url = isset($_SERVER['HTTP_REFERER']) ? (filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL) ?? 'invalid_referer') : 'direct';

try {
    $pdo->prepare("
        INSERT INTO visits (ip_address, user_agent, view_date, page_url, views_count, user_agent_hash, page_url_hash) 
        VALUES (?, ?, ?, ?, 1, ?, ?)
        ON DUPLICATE KEY UPDATE 
            view_date = VALUES(view_date),
            views_count = views_count + 1
    ")->execute([$ip_address, $user_agent, date('Y-m-d H:i:s'), $page_url, hash('sha256', $user_agent), hash('sha256', $page_url)]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}

$stmt = $pdo->prepare("SELECT views_count FROM visits WHERE ip_address = ? AND user_agent = ? AND page_url = ?");
$stmt->execute([$ip_address, $user_agent, $page_url]);
$existing = $stmt->fetch();

header('Content-Type: image/png');
$image = imagecreate(400, 100);
$bg = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 255, 0, 0);
imagestring($image, 3, isset($existing['views_count']) ? 10 : 80, 40, 'This is one big beautiful banner.' . (isset($existing['views_count']) ? ' This is Visit No.' . $existing['views_count']: ''), $text_color);
imagepng($image);
imagedestroy($image);

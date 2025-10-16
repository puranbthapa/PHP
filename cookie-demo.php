<?php
/**
 * Cookie Demo
 * Demonstrates secure cookie setting, reading and deletion
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

// Set a cookie with secure flags (HttpOnly and SameSite)
$cookieName = 'demo_theme';
$cookieValue = $_GET['set'] ?? null;

if ($cookieValue !== null) {
    setcookie($cookieName, $cookieValue, [
        'expires' => time() + 60*60*24*30,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    header('Location: cookie-demo.php');
    exit;
}

if (isset($_GET['delete'])) {
    setcookie($cookieName, '', time() - 3600, '/');
    header('Location: cookie-demo.php');
    exit;
}

$current = $_COOKIE[$cookieName] ?? 'not set';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cookie Demo</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;max-width:800px;margin:20px auto}</style>
</head>
<body>
    <h1>Cookie Demo</h1>
    <p>Current cookie <strong><?php echo htmlspecialchars($cookieName); ?></strong>: <code><?php echo htmlspecialchars($current); ?></code></p>
    
    <p>
        <a href="?set=light">Set cookie to 'light'</a> | 
        <a href="?set=dark">Set cookie to 'dark'</a> | 
        <a href="?delete=1">Delete cookie</a>
    </p>

    <h3>Notes</h3>
    <ul>
        <li>Cookies with HttpOnly cannot be read by JavaScript (helps prevent XSS theft)</li>
        <li>`SameSite=Lax` helps mitigate CSRF on unsafe cross-site requests</li>
        <li>Set `Secure` to true when serving over HTTPS</li>
    </ul>
</body>
</html>
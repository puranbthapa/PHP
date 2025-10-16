<?php
/**
 * Session Demo
 * Demonstrates secure session start, login simulation, flash messages and logout
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */
require_once 'form-validators.php';

SessionManager::start();

// Simple login simulation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        // Demo credentials (DO NOT use in production)
        $users = [
            'student' => 'pass123',
            'teacher' => 'teach123'
        ];
        
        if (isset($users[$username]) && $users[$username] === $password) {
            SessionManager::login(uniqid('u_'), ['username' => $username]);
            SessionManager::setFlash('success', 'Login successful. Welcome ' . htmlspecialchars($username));
            header('Location: session-demo.php');
            exit;
        } else {
            SessionManager::setFlash('error', 'Invalid credentials');
            header('Location: session-demo.php');
            exit;
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        SessionManager::logout();
        header('Location: session-demo.php');
        exit;
    }
}

$flashes = SessionManager::getFlash();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Session Demo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; }
        .box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .success { background: #d4edda; color: #155724; padding: 10px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; }
    </style>
</head>
<body>
    <h1>Session Demonstration</h1>

    <?php
    if (!empty($flashes)) {
        foreach ($flashes as $type => $messages) {
            foreach ($messages as $msg) {
                echo '<div class="' . $type . '">' . htmlspecialchars($msg) . '</div>';
            }
        }
    }
    ?>

    <?php if (SessionManager::isLoggedIn()): ?>
        <div class="box">
            <h3>Welcome</h3>
            <p>You are logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_username'] ?? 'Unknown'); ?></strong></p>
            <form method="POST">
                <input type="hidden" name="action" value="logout">
                <button type="submit">Logout</button>
            </form>
        </div>
    <?php else: ?>
        <div class="box">
            <h3>Login (demo)</h3>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <label>Username: <input type="text" name="username"></label><br><br>
                <label>Password: <input type="password" name="password"></label><br><br>
                <button type="submit">Login</button>
            </form>
            <p>Demo accounts: <code>student / pass123</code>, <code>teacher / teach123</code></p>
        </div>
    <?php endif; ?>

    <div class="box">
        <h3>Session Data Debug</h3>
        <pre><?php print_r($_SESSION); ?></pre>
    </div>
</body>
</html>
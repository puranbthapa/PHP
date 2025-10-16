<?php
/**
 * Security Demonstration
 * Shows practical implementation of security best practices
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

// Load security classes first
require_once 'security-classes.php';

// Set security headers
require_once 'security-headers.php';

echo "<h1>Security Best Practices Demo</h1>";

// 1. Input Validation Demo
echo "<h2>1. Input Validation</h2>";

$testInputs = [
    'valid@example.com',
    'invalid-email',
    '<script>alert("xss")</script>user@example.com',
    'very-long-email-that-exceeds-normal-limits@' . str_repeat('example', 20) . '.com'
];

echo "<h3>Email Validation Tests:</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Input</th><th>Valid</th><th>Result/Error</th></tr>";

foreach ($testInputs as $email) {
    $result = SecurityValidator::validateEmail($email);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($email) . "</td>";
    echo "<td>" . ($result['valid'] ? '✓' : '✗') . "</td>";
    echo "<td>" . htmlspecialchars($result['error'] ?? $result['value'] ?? 'Valid') . "</td>";
    echo "</tr>";
}
echo "</table>";

// 2. Password Security Demo
echo "<h2>2. Password Security</h2>";

$testPasswords = [
    'weak',
    'StrongPass123!',
    'NoNumbers!',
    'nonumbers123',
    'NOLOWERCASE123!'
];

echo "<h3>Password Validation Tests:</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Password</th><th>Valid</th><th>Issues</th></tr>";

foreach ($testPasswords as $password) {
    $result = SecurityValidator::validatePassword($password);
    echo "<tr>";
    echo "<td>" . str_repeat('*', strlen($password)) . " (" . strlen($password) . " chars)</td>";
    echo "<td>" . ($result['valid'] ? '✓' : '✗') . "</td>";
    echo "<td>" . (isset($result['errors']) ? implode('<br>', $result['errors']) : 'Valid') . "</td>";
    echo "</tr>";
}
echo "</table>";

// 3. Password Hashing Demo
echo "<h2>3. Password Hashing</h2>";

$plainPassword = "MySecurePassword123!";
$hashedPassword = PasswordSecurity::hashPassword($plainPassword);

echo "<p><strong>Original Password:</strong> " . htmlspecialchars($plainPassword) . "</p>";
echo "<p><strong>Hashed Password:</strong> <code>" . htmlspecialchars($hashedPassword) . "</code></p>";
echo "<p><strong>Hash Length:</strong> " . strlen($hashedPassword) . " characters</p>";

// Verify password
$isValid = PasswordSecurity::verifyPassword($plainPassword, $hashedPassword);
$isInvalid = PasswordSecurity::verifyPassword("WrongPassword", $hashedPassword);

echo "<p><strong>Correct Password Verification:</strong> " . ($isValid ? '✓ Valid' : '✗ Invalid') . "</p>";
echo "<p><strong>Wrong Password Verification:</strong> " . ($isInvalid ? '✓ Valid' : '✗ Invalid') . "</p>";

// 4. XSS Protection Demo
echo "<h2>4. XSS Protection</h2>";

$maliciousInputs = [
    '<script>alert("XSS Attack!")</script>',
    '<img src="x" onerror="alert(\'XSS\')">',
    'Normal text with <b>bold</b> formatting',
    '\"><script>alert(String.fromCharCode(88,83,83))</script>'
];

echo "<h3>XSS Prevention Tests:</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Original Input</th><th>Escaped Output</th><th>Cleaned HTML</th></tr>";

foreach ($maliciousInputs as $input) {
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($input) . "</code></td>";
    echo "<td><code>" . htmlspecialchars(XSSProtection::escape($input)) . "</code></td>";
    echo "<td><code>" . htmlspecialchars(XSSProtection::cleanHTML($input)) . "</code></td>";
    echo "</tr>";
}
echo "</table>";

// 5. CSRF Token Demo
echo "<h2>5. CSRF Protection</h2>";

session_start();
$csrfToken = CSRFProtection::generateToken();

echo "<p><strong>Generated CSRF Token:</strong> <code>" . htmlspecialchars($csrfToken) . "</code></p>";

// Simulate form with CSRF token
echo "<h3>Sample Protected Form:</h3>";
echo "<form method='POST' action='#'>";
echo CSRFProtection::getTokenInput();
echo "<input type='text' name='username' placeholder='Username' required><br><br>";
echo "<input type='password' name='password' placeholder='Password' required><br><br>";
echo "<button type='submit'>Login (Protected)</button>";
echo "</form>";

// 6. Encryption Demo
echo "<h2>6. Data Encryption</h2>";

try {
    $encryption = new DataEncryption();
    $sensitiveData = "Credit Card: 1234-5678-9012-3456";
    
    echo "<p><strong>Original Data:</strong> " . htmlspecialchars($sensitiveData) . "</p>";
    
    $encrypted = $encryption->encrypt($sensitiveData);
    echo "<p><strong>Encrypted Data:</strong> <code>" . htmlspecialchars($encrypted) . "</code></p>";
    
    $decrypted = $encryption->decrypt($encrypted);
    echo "<p><strong>Decrypted Data:</strong> " . htmlspecialchars($decrypted) . "</p>";
    
    echo "<p><strong>Encryption Status:</strong> " . ($decrypted === $sensitiveData ? '✓ Success' : '✗ Failed') . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Encryption Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// 7. File Upload Security Demo
echo "<h2>7. File Upload Security</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['demo_file'])) {
    try {
        $uploader = new SecureFileUpload('uploads/demo/');
        $result = $uploader->handleUpload('demo_file', 1);
        
        if ($result['success']) {
            echo "<p style='color: green;'>✓ File uploaded successfully:</p>";
            echo "<ul>";
            echo "<li><strong>Filename:</strong> " . htmlspecialchars($result['filename']) . "</li>";
            echo "<li><strong>Size:</strong> " . number_format($result['size']) . " bytes</li>";
            echo "<li><strong>Type:</strong> " . htmlspecialchars($result['type']) . "</li>";
            echo "</ul>";
        } else {
            echo "<p style='color: red;'>✗ Upload failed: " . htmlspecialchars($result['error']) . "</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Upload Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

echo "<h3>Test File Upload Security:</h3>";
echo "<form method='POST' enctype='multipart/form-data'>";
echo CSRFProtection::getTokenInput();
echo "<input type='file' name='demo_file' required><br><br>";
echo "<button type='submit'>Upload File (Test Security)</button><br>";
echo "<small>Try uploading different file types including .php, .exe, oversized files</small>";
echo "</form>";

// 8. Rate Limiting Simulation
echo "<h2>8. Rate Limiting Simulation</h2>";

if (!isset($_SESSION['demo_attempts'])) {
    $_SESSION['demo_attempts'] = 0;
    $_SESSION['demo_last_attempt'] = 0;
}

$currentTime = time();
$timeSinceLastAttempt = $currentTime - $_SESSION['demo_last_attempt'];

if (isset($_POST['demo_action'])) {
    if ($timeSinceLastAttempt < 2) { // 2 second rate limit
        echo "<p style='color: red;'>✗ Rate limited! Please wait before trying again.</p>";
    } else {
        $_SESSION['demo_attempts']++;
        $_SESSION['demo_last_attempt'] = $currentTime;
        echo "<p style='color: green;'>✓ Action allowed. Attempt #" . $_SESSION['demo_attempts'] . "</p>";
    }
}

echo "<form method='POST'>";
echo CSRFProtection::getTokenInput();
echo "<input type='hidden' name='demo_action' value='1'>";
echo "<button type='submit'>Test Rate Limiting</button>";
echo "</form>";
echo "<p><small>Try clicking multiple times quickly to see rate limiting in action.</small></p>";

// 9. Security Headers Check
echo "<h2>9. Security Headers Status</h2>";

$headers = headers_list();
$securityHeaders = [
    'X-Content-Type-Options',
    'X-Frame-Options', 
    'X-XSS-Protection',
    'Content-Security-Policy',
    'Strict-Transport-Security'
];

echo "<h3>Active Security Headers:</h3>";
echo "<ul>";
foreach ($headers as $header) {
    foreach ($securityHeaders as $secHeader) {
        if (strpos($header, $secHeader) === 0) {
            echo "<li>✓ " . htmlspecialchars($header) . "</li>";
        }
    }
}
echo "</ul>";

// 10. Session Security Info
echo "<h2>10. Session Security Status</h2>";

echo "<h3>Session Configuration:</h3>";
echo "<ul>";
echo "<li><strong>HttpOnly:</strong> " . (ini_get('session.cookie_httponly') ? '✓ Enabled' : '✗ Disabled') . "</li>";
echo "<li><strong>Secure:</strong> " . (ini_get('session.cookie_secure') ? '✓ Enabled' : '✗ Disabled') . "</li>";
echo "<li><strong>SameSite:</strong> " . (ini_get('session.cookie_samesite') ?: 'Not Set') . "</li>";
echo "<li><strong>Strict Mode:</strong> " . (ini_get('session.use_strict_mode') ? '✓ Enabled' : '✗ Disabled') . "</li>";
echo "<li><strong>Session ID:</strong> " . htmlspecialchars(session_id()) . "</li>";
echo "</ul>";

echo "<h2>Security Recommendations</h2>";
echo "<ol>";
echo "<li><strong>Always validate and sanitize user input</strong></li>";
echo "<li><strong>Use prepared statements for database queries</strong></li>";
echo "<li><strong>Hash passwords with strong algorithms (Argon2ID)</strong></li>";
echo "<li><strong>Implement CSRF protection on all forms</strong></li>";
echo "<li><strong>Set proper security headers</strong></li>";
echo "<li><strong>Use HTTPS in production</strong></li>";
echo "<li><strong>Implement rate limiting for sensitive operations</strong></li>";
echo "<li><strong>Regularly update PHP and dependencies</strong></li>";
echo "<li><strong>Log security events for monitoring</strong></li>";
echo "<li><strong>Conduct regular security audits</strong></li>";
echo "</ol>";
?>
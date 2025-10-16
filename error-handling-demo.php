<?php
/**
 * Error Handling Demo
 * Demonstrates different types of errors and exception handling
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

// Set up error reporting for demonstration
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create logs directory if it doesn't exist
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

echo "<h1>Error Handling & Exception Demo</h1>";

// 1. Basic Exception Handling
echo "<h2>1. Basic Try-Catch</h2>";
try {
    $result = 10 / 0; // This will cause a warning, not an exception
    echo "Result: $result<br>";
} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage() . "<br>";
}

// 2. Throwing custom exceptions
echo "<h2>2. Custom Exceptions</h2>";
function divide($a, $b) {
    if ($b == 0) {
        throw new InvalidArgumentException("Division by zero is not allowed");
    }
    return $a / $b;
}

try {
    echo "5 ÷ 2 = " . divide(5, 2) . "<br>";
    echo "10 ÷ 0 = " . divide(10, 0) . "<br>";
} catch (InvalidArgumentException $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
}

// 3. Multiple exception types
echo "<h2>3. Multiple Exception Types</h2>";

class ValidationException extends Exception {
    private $errors;
    
    public function __construct($message, $errors = []) {
        parent::__construct($message);
        $this->errors = $errors;
    }
    
    public function getErrors() {
        return $this->errors;
    }
}

function validateEmail($email) {
    if (empty($email)) {
        throw new ValidationException("Email is required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new ValidationException("Invalid email format");
    }
    return true;
}

$testEmails = ['valid@example.com', 'invalid-email', ''];

foreach ($testEmails as $email) {
    try {
        validateEmail($email);
        echo "✓ Email '$email' is valid<br>";
    } catch (ValidationException $e) {
        echo "✗ Email '$email': " . $e->getMessage() . "<br>";
    }
}

// 4. Finally block
echo "<h2>4. Finally Block Demo</h2>";
function processFile($filename) {
    $file = null;
    try {
        echo "Attempting to open: $filename<br>";
        $file = fopen($filename, 'r');
        if (!$file) {
            throw new Exception("Could not open file: $filename");
        }
        echo "File opened successfully<br>";
        // Simulate some processing
        $content = fread($file, 100);
        echo "Read " . strlen($content) . " bytes<br>";
    } catch (Exception $e) {
        echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
    } finally {
        if ($file) {
            fclose($file);
            echo "File closed in finally block<br>";
        }
        echo "Cleanup completed<br>";
    }
}

// Test with existing file (this script itself)
processFile(__FILE__);
echo "<br>";

// Test with non-existent file
processFile('nonexistent.txt');

// 5. Error to Exception conversion
echo "<h2>5. Converting Errors to Exceptions</h2>";
function errorToException($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('errorToException');

try {
    // This will trigger a warning that gets converted to exception
    $result = file_get_contents('nonexistent-file.txt');
} catch (ErrorException $e) {
    echo "<span style='color: orange;'>Converted Error: " . $e->getMessage() . "</span><br>";
    echo "Severity: " . $e->getSeverity() . ", Line: " . $e->getLine() . "<br>";
}

// Restore default error handler
restore_error_handler();

// 6. Logging errors
echo "<h2>6. Error Logging</h2>";
$logFile = $logDir . '/demo_errors.log';

function logError($message, $context = []) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $entry = "[$timestamp] $message";
    if (!empty($context)) {
        $entry .= ' | Context: ' . json_encode($context);
    }
    $entry .= "\n";
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

try {
    throw new Exception("Demo error for logging");
} catch (Exception $e) {
    logError($e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    echo "Error logged to: " . basename($logFile) . "<br>";
}

// Show recent log entries
if (file_exists($logFile)) {
    echo "<h3>Recent Log Entries:</h3>";
    $logs = file($logFile);
    $recentLogs = array_slice($logs, -5); // Last 5 entries
    echo "<pre>" . htmlspecialchars(implode('', $recentLogs)) . "</pre>";
}

echo "<p><em>Demo completed. Check the logs/ directory for error logs.</em></p>";
?>
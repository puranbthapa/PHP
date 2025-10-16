# Lesson 11: Error Handling & Debugging
**Course:** PHP Web Development - Class XII  
**Duration:** 3.5 hours  
**Prerequisites:** Lessons 1-10 completed; understanding of PHP functions and file operations

---

## Learning Objectives
By the end of this lesson students will be able to:
1. Understand different types of PHP errors (fatal, warning, notice, exception)
2. Use try-catch blocks for exception handling
3. Create custom exceptions and error handlers
4. Configure error reporting and logging for development vs production
5. Use debugging techniques: var_dump, print_r, error_log
6. Set up and use Xdebug for step-by-step debugging
7. Implement proper logging strategies for web applications

---

## Section 1 — Types of PHP Errors

### Error Categories
1. **Fatal Errors** - Stop script execution (undefined functions, memory limits)
2. **Warnings** - Continue execution but indicate problems (file not found)
3. **Notices** - Minor issues that don't stop execution (undefined variables)
4. **Exceptions** - Object-oriented error handling (thrown and caught)

### Error Reporting Configuration
```php
<?php
// Development environment
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

// Production environment (hide errors from users)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
?>
```

---

## Section 2 — Exception Handling

### Basic Try-Catch
```php
<?php
try {
    $file = fopen('nonexistent.txt', 'r');
    if (!$file) {
        throw new Exception('File could not be opened');
    }
    // Process file
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage();
} finally {
    // Cleanup code (always runs)
    if (isset($file) && $file) {
        fclose($file);
    }
}
?>
```

### Multiple Exception Types
```php
<?php
try {
    $result = riskyOperation();
} catch (InvalidArgumentException $e) {
    echo 'Invalid argument: ' . $e->getMessage();
} catch (RuntimeException $e) {
    echo 'Runtime error: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'General error: ' . $e->getMessage();
}
?>
```

### Custom Exceptions
```php
<?php
class ValidationException extends Exception {
    private $errors = [];
    
    public function __construct($message, array $errors = []) {
        parent::__construct($message);
        $this->errors = $errors;
    }
    
    public function getErrors() {
        return $this->errors;
    }
}

// Usage
function validateUser($data) {
    $errors = [];
    if (empty($data['email'])) $errors[] = 'Email required';
    if (empty($data['name'])) $errors[] = 'Name required';
    
    if (!empty($errors)) {
        throw new ValidationException('Validation failed', $errors);
    }
}

try {
    validateUser(['email' => '']);
} catch (ValidationException $e) {
    echo $e->getMessage() . "\n";
    foreach ($e->getErrors() as $error) {
        echo "- $error\n";
    }
}
?>
```

---

## Section 3 — Custom Error Handlers

### Set Custom Error Handler
```php
<?php
function customErrorHandler($severity, $message, $file, $line) {
    $errorTypes = [
        E_ERROR => 'Fatal Error',
        E_WARNING => 'Warning',
        E_NOTICE => 'Notice',
        E_USER_ERROR => 'User Error',
    ];
    
    $type = $errorTypes[$severity] ?? 'Unknown Error';
    $logMessage = "[$type] $message in $file on line $line";
    
    error_log($logMessage);
    
    // Don't show errors to users in production
    if (ini_get('display_errors')) {
        echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 5px;'>";
        echo "<strong>$type:</strong> $message<br>";
        echo "<small>File: $file, Line: $line</small>";
        echo "</div>";
    }
    
    return true; // Don't execute PHP's internal error handler
}

set_error_handler('customErrorHandler');

// Test the handler
$undefinedVar; // This will trigger a notice
?>
```

---

## Section 4 — Debugging Techniques

### Basic Debugging Functions
```php
<?php
$data = ['name' => 'Alice', 'scores' => [95, 87, 92]];

// var_dump - detailed type information
echo '<h3>var_dump output:</h3>';
var_dump($data);

// print_r - human readable
echo '<h3>print_r output:</h3>';
echo '<pre>' . print_r($data, true) . '</pre>';

// var_export - valid PHP code
echo '<h3>var_export output:</h3>';
echo '<pre>' . var_export($data, true) . '</pre>';

// Debug backtrace
function debugTrace() {
    echo '<h3>Debug Backtrace:</h3>';
    echo '<pre>' . print_r(debug_backtrace(), true) . '</pre>';
}
?>
```

### Conditional Debugging
```php
<?php
define('DEBUG_MODE', true);

function debugLog($message, $data = null) {
    if (DEBUG_MODE) {
        $timestamp = date('Y-m-d H:i:s');
        $output = "[$timestamp] DEBUG: $message";
        if ($data !== null) {
            $output .= "\nData: " . print_r($data, true);
        }
        error_log($output);
        echo '<pre>' . htmlspecialchars($output) . '</pre>';
    }
}

// Usage
debugLog('Processing user data', $_POST);
?>
```

---

## Section 5 — Logging Strategies

### Application Logging
```php
<?php
class Logger {
    const LEVEL_DEBUG = 0;
    const LEVEL_INFO = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR = 3;
    
    private $logFile;
    private $minLevel;
    
    public function __construct($logFile = null, $minLevel = self::LEVEL_INFO) {
        $this->logFile = $logFile ?: __DIR__ . '/logs/app.log';
        $this->minLevel = $minLevel;
        
        // Ensure log directory exists
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }
    
    public function debug($message, $context = []) {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }
    
    public function info($message, $context = []) {
        $this->log(self::LEVEL_INFO, $message, $context);
    }
    
    public function warning($message, $context = []) {
        $this->log(self::LEVEL_WARNING, $message, $context);
    }
    
    public function error($message, $context = []) {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }
    
    private function log($level, $message, $context) {
        if ($level < $this->minLevel) return;
        
        $levels = ['DEBUG', 'INFO', 'WARNING', 'ERROR'];
        $timestamp = date('Y-m-d H:i:s');
        $levelName = $levels[$level];
        
        $logEntry = "[$timestamp] [$levelName] $message";
        if (!empty($context)) {
            $logEntry .= ' ' . json_encode($context);
        }
        $logEntry .= "\n";
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}

// Usage
$logger = new Logger();
$logger->info('User login attempt', ['username' => 'alice', 'ip' => $_SERVER['REMOTE_ADDR']]);
$logger->error('Database connection failed', ['error' => 'Connection timeout']);
?>
```

---

## Section 6 — Xdebug Setup & Usage

### Installing Xdebug
1. **Check PHP version**: `php -v`
2. **Download Xdebug**: Visit https://xdebug.org/download
3. **Install via package manager** (recommended):
   ```bash
   # Windows (via XAMPP)
   # Xdebug usually comes pre-installed, check phpinfo()
   
   # Ubuntu/Debian
   sudo apt install php-xdebug
   
   # CentOS/RHEL
   sudo yum install php-xdebug
   ```

### Xdebug Configuration (php.ini)
```ini
[xdebug]
zend_extension=xdebug
xdebug.mode=debug,develop
xdebug.start_with_request=yes
xdebug.client_port=9003
xdebug.client_host=127.0.0.1
xdebug.log=/tmp/xdebug.log
xdebug.show_error_trace=1
```

### VS Code Xdebug Setup
1. Install "PHP Debug" extension
2. Create `.vscode/launch.json`:
```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/path/to/server": "${workspaceFolder}"
            }
        }
    ]
}
```

---

## Practical Activities

### Activity 1: Error Handling Demo (30 minutes)
Run `error-handling-demo.php` to see different error types and exception handling in action.

### Activity 2: Debugging Practice (45 minutes)
Use `debugging-practice.php` to practice identifying and fixing common bugs using various debugging techniques.

### Activity 3: Xdebug Walkthrough (30 minutes)
Set up Xdebug and practice step-through debugging with breakpoints in VS Code.

---

## Exercises

1. **Error Logger**: Create a comprehensive error logging system that categorizes errors by severity and sends email alerts for critical errors.

2. **Debug Helper**: Build a debugging helper class that can capture and display variable states, execution time, and memory usage.

3. **Exception Chain**: Practice creating custom exception hierarchies for a student management system with specific exceptions for validation, database, and authentication errors.

---

## Homework

Create a **Robust File Processing System** with comprehensive error handling:

**Requirements:**
- Process CSV files with student data
- Handle various error conditions (file not found, invalid format, permission errors)
- Log all operations and errors with different severity levels  
- Use custom exceptions for different error types
- Provide user-friendly error messages while logging technical details
- Include recovery mechanisms where possible (retry logic, fallback options)

**Deliverables:**
- Main processor class with exception handling
- Custom exception classes
- Logging configuration
- Error recovery mechanisms
- Test cases demonstrating error scenarios

---

## Next Lesson Preview
**Lesson 12: Working with Databases** — MySQLi and PDO, prepared statements, CRUD operations, and database security.

---

*Debugging is a skill that improves with practice. Always log errors in production and use debugging tools during development to understand your code's behavior.*
<?php
/**
 * Form Validation and Sanitization Library
 * Provides secure, reusable functions for form processing
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

class FormValidator {
    
    /**
     * Sanitize string input
     * @param string $input Raw input
     * @return string Sanitized string
     */
    public static function sanitizeString($input) {
        if (!is_string($input)) return '';
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate and sanitize email
     * @param string $email Email address
     * @return string|false Valid email or false
     */
    public static function validateEmail($email) {
        $email = trim($email);
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Validate phone number (10 digits)
     * @param string $phone Phone number
     * @return bool True if valid
     */
    public static function validatePhone($phone) {
        $cleaned = preg_replace('/\D/', '', $phone);
        return strlen($cleaned) === 10;
    }
    
    /**
     * Validate password strength
     * @param string $password Password
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validatePassword($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }
        
        if (!preg_match('/\d/', $password)) {
            $errors[] = "Password must contain at least one number";
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Validate file upload
     * @param array $file $_FILES array element
     * @param array $options Configuration options
     * @return array ['valid' => bool, 'error' => string, 'safe_name' => string]
     */
    public static function validateFileUpload($file, $options = []) {
        $defaults = [
            'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
            'max_size' => 5 * 1024 * 1024, // 5MB
            'upload_dir' => 'uploads/'
        ];
        
        $options = array_merge($defaults, $options);
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'valid' => false,
                'error' => self::getUploadErrorMessage($file['error']),
                'safe_name' => ''
            ];
        }
        
        // Check file size
        if ($file['size'] > $options['max_size']) {
            return [
                'valid' => false,
                'error' => 'File size exceeds maximum allowed size of ' . 
                          round($options['max_size'] / 1024 / 1024, 1) . 'MB',
                'safe_name' => ''
            ];
        }
        
        // Check file extension
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $options['allowed_types'])) {
            return [
                'valid' => false,
                'error' => 'File type not allowed. Allowed types: ' . 
                          implode(', ', $options['allowed_types']),
                'safe_name' => ''
            ];
        }
        
        // Check MIME type for additional security
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowed_mimes = [
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        ];
        
        if (isset($allowed_mimes[$file_extension]) && 
            !in_array($mime_type, $allowed_mimes[$file_extension])) {
            return [
                'valid' => false,
                'error' => 'File content does not match extension',
                'safe_name' => ''
            ];
        }
        
        // Generate safe filename
        $safe_name = uniqid('file_', true) . '.' . $file_extension;
        
        return [
            'valid' => true,
            'error' => '',
            'safe_name' => $safe_name
        ];
    }
    
    /**
     * Get human-readable upload error message
     * @param int $error_code PHP upload error code
     * @return string Error message
     */
    private static function getUploadErrorMessage($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'File exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'File exceeds the MAX_FILE_SIZE directive in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }
    
    /**
     * Generate CSRF token
     * @return string CSRF token
     */
    public static function generateCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     * @param string $token Token from form
     * @return bool True if valid
     */
    public static function validateCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Validate required fields
     * @param array $data Form data
     * @param array $required Required field names
     * @return array Missing field names
     */
    public static function validateRequired($data, $required) {
        $missing = [];
        
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $missing[] = $field;
            }
        }
        
        return $missing;
    }
    
    /**
     * Validate integer within range
     * @param mixed $value Value to validate
     * @param int $min Minimum value
     * @param int $max Maximum value
     * @return bool True if valid
     */
    public static function validateIntRange($value, $min = null, $max = null) {
        if (!is_numeric($value)) return false;
        
        $int_val = (int)$value;
        
        if ($min !== null && $int_val < $min) return false;
        if ($max !== null && $int_val > $max) return false;
        
        return true;
    }
    
    /**
     * Sanitize filename for safe storage
     * @param string $filename Original filename
     * @return string Safe filename
     */
    public static function sanitizeFilename($filename) {
        // Remove path information and dots
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^A-Za-z0-9._-]/', '_', $filename);
        
        // Remove multiple consecutive dots/underscores
        $filename = preg_replace('/[._-]+/', '_', $filename);
        
        // Trim and ensure not empty
        $filename = trim($filename, '_.-');
        
        return empty($filename) ? 'file' : $filename;
    }
    
    /**
     * Create HTML for CSRF token input
     * @return string HTML input element
     */
    public static function csrfTokenInput() {
        $token = self::generateCSRFToken();
        return '<input type="hidden" name="csrf_token" value="' . 
               htmlspecialchars($token) . '">';
    }
}

/**
 * Session Management Helper
 */
class SessionManager {
    
    /**
     * Start session securely
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // Security settings
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            ini_set('session.use_strict_mode', 1);
            
            session_start();
            
            // Regenerate session ID periodically
            if (!isset($_SESSION['created'])) {
                $_SESSION['created'] = time();
            } elseif (time() - $_SESSION['created'] > 300) { // 5 minutes
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }
    
    /**
     * Set flash message
     * @param string $type Message type (success, error, info, warning)
     * @param string $message Message content
     */
    public static function setFlash($type, $message) {
        self::start();
        $_SESSION['flash'][$type][] = $message;
    }
    
    /**
     * Get and clear flash messages
     * @param string $type Message type (optional)
     * @return array Flash messages
     */
    public static function getFlash($type = null) {
        self::start();
        
        if ($type) {
            $messages = $_SESSION['flash'][$type] ?? [];
            unset($_SESSION['flash'][$type]);
            return $messages;
        }
        
        $all_messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $all_messages;
    }
    
    /**
     * Check if user is logged in
     * @return bool True if logged in
     */
    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Login user
     * @param int $user_id User ID
     * @param array $user_data Additional user data
     */
    public static function login($user_id, $user_data = []) {
        self::start();
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['login_time'] = time();
        
        foreach ($user_data as $key => $value) {
            $_SESSION['user_' . $key] = $value;
        }
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        self::start();
        
        // Clear session data
        $_SESSION = [];
        
        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
}
?>
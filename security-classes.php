<?php
/**
 * Security Classes
 * Comprehensive security utilities for PHP applications
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

// Input Validation and Sanitization
class SecurityValidator {
    public static function validateEmail($email) {
        $email = trim($email);
        
        if (empty($email)) {
            return ['valid' => false, 'error' => 'Email is required'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'error' => 'Invalid email format'];
        }
        
        if (strlen($email) > 254) {
            return ['valid' => false, 'error' => 'Email too long'];
        }
        
        return ['valid' => true, 'value' => $email];
    }
    
    public static function validatePassword($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if (!preg_match('/\d/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }
        
        return empty($errors) ? ['valid' => true] : ['valid' => false, 'errors' => $errors];
    }
    
    public static function sanitizeString($input, $maxLength = null) {
        $input = trim($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        if ($maxLength && strlen($input) > $maxLength) {
            $input = substr($input, 0, $maxLength);
        }
        
        return $input;
    }
    
    public static function validateInteger($input, $min = null, $max = null) {
        if (!is_numeric($input)) {
            return ['valid' => false, 'error' => 'Must be a number'];
        }
        
        $value = (int)$input;
        
        if ($min !== null && $value < $min) {
            return ['valid' => false, 'error' => "Must be at least $min"];
        }
        
        if ($max !== null && $value > $max) {
            return ['valid' => false, 'error' => "Must be no more than $max"];
        }
        
        return ['valid' => true, 'value' => $value];
    }
}

// XSS Protection
class XSSProtection {
    public static function escape($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    public static function cleanHTML($html) {
        $allowed = '<p><br><strong><em><ul><ol><li><a>';
        return strip_tags($html, $allowed);
    }
    
    public static function escapeForJSON($data) {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
    
    public static function setCSPHeader() {
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");
    }
}

// Password Security
class PasswordSecurity {
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3,
        ]);
    }
    
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public static function needsRehash($hash) {
        return password_needs_rehash($hash, PASSWORD_ARGON2ID);
    }
    
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    public static function generatePasswordResetToken() {
        return [
            'token' => self::generateToken(),
            'expires' => time() + 3600,
        ];
    }
}

// CSRF Protection
class CSRFProtection {
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    public static function validateToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function getTokenInput() {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    public static function validateRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!self::validateToken($token)) {
                throw new Exception('CSRF token validation failed');
            }
        }
    }
}

// Data Encryption
class DataEncryption {
    private $key;
    private $cipher = 'aes-256-gcm';
    
    public function __construct($key = null) {
        $this->key = $key ?: $this->generateKey();
    }
    
    public function generateKey() {
        return random_bytes(32);
    }
    
    public function encrypt($data) {
        $iv = random_bytes(12);
        $tag = '';
        
        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        
        if ($encrypted === false) {
            throw new Exception('Encryption failed');
        }
        
        return base64_encode($iv . $tag . $encrypted);
    }
    
    public function decrypt($encryptedData) {
        $data = base64_decode($encryptedData);
        
        if ($data === false) {
            throw new Exception('Invalid encrypted data format');
        }
        
        $iv = substr($data, 0, 12);
        $tag = substr($data, 12, 16);
        $encrypted = substr($data, 28);
        
        $decrypted = openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        
        if ($decrypted === false) {
            throw new Exception('Decryption failed');
        }
        
        return $decrypted;
    }
}

// Secure File Upload
class SecureFileUpload {
    private $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    private $maxSize = 5242880; // 5MB
    private $uploadDir;
    
    public function __construct($uploadDir = 'uploads/') {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        $this->ensureUploadDir();
    }
    
    private function ensureUploadDir() {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        
        $htaccess = $this->uploadDir . '.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Options -Indexes\nOptions -ExecCGI\nAddHandler cgi-script .php .phtml .php3 .pl .py .jsp .asp .sh .cgi");
        }
    }
    
    public function handleUpload($fileInput, $userId = null) {
        if (!isset($_FILES[$fileInput])) {
            return ['success' => false, 'error' => 'No file uploaded'];
        }
        
        $file = $_FILES[$fileInput];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => $this->getUploadErrorMessage($file['error'])];
        }
        
        if ($file['size'] > $this->maxSize) {
            return ['success' => false, 'error' => 'File too large. Maximum size: ' . ($this->maxSize / 1024 / 1024) . 'MB'];
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedTypes)) {
            return ['success' => false, 'error' => 'File type not allowed. Allowed: ' . implode(', ', $this->allowedTypes)];
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif',
            'application/pdf',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (!in_array($mimeType, $allowedMimes)) {
            return ['success' => false, 'error' => 'Invalid file type detected'];
        }
        
        $safeFilename = $this->generateSafeFilename($file['name'], $userId);
        $fullPath = $this->uploadDir . $safeFilename;
        
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            return ['success' => false, 'error' => 'Failed to save file'];
        }
        
        chmod($fullPath, 0644);
        
        return [
            'success' => true,
            'filename' => $safeFilename,
            'path' => $fullPath,
            'size' => $file['size'],
            'type' => $mimeType
        ];
    }
    
    private function generateSafeFilename($originalName, $userId = null) {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        
        $basename = preg_replace('/[^a-zA-Z0-9_-]/', '', $basename);
        $basename = substr($basename, 0, 50);
        
        $prefix = $userId ? "user{$userId}_" : '';
        $timestamp = time();
        
        return $prefix . $timestamp . '_' . $basename . '.' . $extension;
    }
    
    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
        ];
        
        return $errors[$errorCode] ?? 'Unknown upload error';
    }
}
?>
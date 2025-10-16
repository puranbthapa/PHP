<?php
/**
 * Security Headers Configuration
 * Sets comprehensive security headers for web application protection
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

class SecurityHeaders {
    public static function setAllSecurityHeaders() {
        // Prevent XSS attacks
        header('X-XSS-Protection: 1; mode=block');
        
        // Prevent content type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Prevent clickjacking
        header('X-Frame-Options: DENY');
        
        // Enforce HTTPS (when using HTTPS)
        if (isset($_SERVER['HTTPS'])) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
        
        // Content Security Policy
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; media-src 'self'; object-src 'none'; child-src 'none'; worker-src 'none'; frame-ancestors 'none'; form-action 'self'; base-uri 'self'");
        
        // Referrer Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Feature Policy / Permissions Policy
        header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
        
        // Remove server information
        header_remove('X-Powered-By');
        header_remove('Server');
    }
    
    public static function setCSRFHeader($token) {
        header("X-CSRF-Token: $token");
    }
    
    public static function setCacheHeaders($private = true, $maxAge = 0) {
        if ($private) {
            header('Cache-Control: private, no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
        } else {
            header("Cache-Control: public, max-age=$maxAge");
        }
    }
    
    public static function setJSONHeaders() {
        header('Content-Type: application/json; charset=utf-8');
        self::setCacheHeaders(true);
    }
    
    public static function setDownloadHeaders($filename, $contentType = 'application/octet-stream') {
        header("Content-Type: $contentType");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Content-Transfer-Encoding: binary');
        self::setCacheHeaders(false, 3600); // Cache downloads for 1 hour
    }
}

// Auto-apply security headers if this file is included
SecurityHeaders::setAllSecurityHeaders();
?>
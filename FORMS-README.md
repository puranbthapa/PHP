# PHP Form Handling & Security Demo

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Overview
This directory contains comprehensive examples for Lesson 9: Advanced Form Handling & Security. The examples demonstrate modern PHP form processing with AJAX, security measures, and progressive enhancement.

## Files Included

### Core Library
- `form-validators.php` - Complete validation and security library
- `ajax-registration.php` - Modern AJAX-powered registration form
- `ajax-process-registration.php` - Secure server-side form processor

### Features Demonstrated
- ✅ **CSRF Protection** - Security tokens prevent cross-site attacks
- ✅ **Real-time Validation** - Client-side validation with visual feedback
- ✅ **AJAX Submission** - Seamless form submission without page reload
- ✅ **Progressive Enhancement** - Works even when JavaScript is disabled
- ✅ **File Upload Security** - MIME type checking and safe filename generation
- ✅ **Session Management** - Secure session handling and flash messages
- ✅ **Input Sanitization** - XSS prevention and data cleaning
- ✅ **Responsive Design** - Mobile-friendly CSS Grid layout

## Quick Start (XAMPP)

1. **Copy Files to XAMPP:**
   ```bash
   # Copy all files to your XAMPP htdocs directory
   cp -r * /xampp/htdocs/php-forms/
   ```

2. **Start XAMPP Services:**
   - Start Apache server
   - No database required for these demos

3. **Access the Demo:**
   ```
   http://localhost/php-forms/ajax-registration.php
   ```

## Security Features Testing

### Test CSRF Protection:
1. Open browser developer tools
2. Try submitting form without valid CSRF token
3. Should see "Security token validation failed" error

### Test Input Validation:
1. Enter invalid email formats
2. Try phone numbers with wrong format
3. Upload files with restricted extensions
4. All should show appropriate error messages

### Test File Upload Security:
1. Try uploading a PHP file renamed with .jpg extension
2. Should fail MIME type validation
3. Upload legitimate image files - should work

## Form Validator Class Usage

### Basic Validation:
```php
require_once 'form-validators.php';

// Validate email
$email = FormValidator::validateEmail($_POST['email']);
if (!$email) {
    echo "Invalid email format";
}

// Validate phone number
$valid_phone = FormValidator::validatePhone($_POST['phone']);

// Check password strength
$password_check = FormValidator::validatePassword($_POST['password']);
if (!$password_check['valid']) {
    echo "Weak password: " . implode(', ', $password_check['errors']);
}
```

### File Upload Validation:
```php
$upload_result = FormValidator::validateFileUpload($_FILES['document'], [
    'allowed_types' => ['jpg', 'png', 'pdf'],
    'max_size' => 5 * 1024 * 1024 // 5MB
]);

if ($upload_result['valid']) {
    $safe_filename = $upload_result['safe_name'];
    // Proceed with file upload
} else {
    echo "Upload error: " . $upload_result['error'];
}
```

### CSRF Protection:
```php
// In your form HTML:
echo FormValidator::csrfTokenInput();

// In form processor:
if (!FormValidator::validateCSRFToken($_POST['csrf_token'])) {
    die('Security validation failed');
}
```

## Session Management

### Flash Messages:
```php
SessionManager::start();

// Set success message
SessionManager::setFlash('success', 'Registration completed successfully!');

// Set error message
SessionManager::setFlash('error', 'Please correct the form errors.');

// Display messages (in template):
$messages = SessionManager::getFlash();
foreach ($messages as $type => $msgs) {
    foreach ($msgs as $msg) {
        echo "<div class='alert alert-$type'>$msg</div>";
    }
}
```

### User Authentication:
```php
// Login user
SessionManager::login($user_id, [
    'username' => $username,
    'email' => $email,
    'role' => 'student'
]);

// Check if logged in
if (SessionManager::isLoggedIn()) {
    echo "Welcome back!";
}

// Logout
SessionManager::logout();
```

## AJAX Integration

The registration form demonstrates:
- Real-time validation with visual feedback
- Progress indicators during submission
- Error handling with field-specific messages
- Fallback to traditional POST if AJAX fails
- JSON response handling

### JavaScript Features:
- Form validation on input/blur events
- Progress bar updates based on completion
- Smooth animations and transitions
- Responsive design with CSS Grid
- Accessibility support (ARIA labels)

## Data Storage

Registration data is stored in JSON format in the `data/` directory:

```json
[
    {
        "id": "student_671234567890abcdef",
        "full_name": "John Doe",
        "roll_number": 1001,
        "email": "john@example.com",
        "registration_id": "REG202410161001",
        "registration_date": "2024-10-16 15:30:45"
    }
]
```

## Troubleshooting

### Common Issues:

1. **CSRF Token Errors:**
   - Ensure sessions are started before generating tokens
   - Check that form includes csrf_token hidden input

2. **File Upload Problems:**
   - Verify PHP upload settings (upload_max_filesize, post_max_size)
   - Ensure uploads directory has write permissions

3. **AJAX Not Working:**
   - Check browser console for JavaScript errors
   - Verify JSON response format from server
   - Form should still work without JavaScript (progressive enhancement)

4. **Session Issues:**
   - Check PHP session configuration
   - Ensure cookies are enabled in browser
   - Verify session directory permissions

## Browser Compatibility
- Chrome/Chromium (recommended)
- Firefox
- Safari
- Edge
- IE 11+ (with polyfills for fetch API)

## Security Notes
- All user input is sanitized before output
- File uploads are validated by both extension and MIME type
- CSRF tokens prevent cross-site request forgery
- Sessions use secure settings when HTTPS is available
- Rate limiting prevents brute force attacks

## Next Steps
1. Implement database storage instead of JSON files
2. Add email sending functionality
3. Create admin dashboard for managing registrations
4. Add user authentication system
5. Implement file compression for uploads
6. Add data export functionality (CSV, PDF)

For questions or issues, refer to the lesson materials or contact your instructor.
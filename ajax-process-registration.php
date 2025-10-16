<?php
/**
 * AJAX Registration Processor
 * Handles both AJAX and traditional form submissions
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

require_once 'form-validators.php';

// Set JSON header for AJAX responses
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'errors' => [],
    'data' => []
];

try {
    // Start session for CSRF protection
    SessionManager::start();
    
    // Check request method
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request method");
    }
    
    // Validate CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!FormValidator::validateCSRFToken($csrf_token)) {
        throw new Exception("Security token validation failed. Please refresh and try again.");
    }
    
    // Collect and sanitize form data
    $full_name = FormValidator::sanitizeString($_POST['full_name'] ?? '');
    $roll_number = $_POST['roll_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $class = FormValidator::sanitizeString($_POST['class'] ?? '');
    $gender = FormValidator::sanitizeString($_POST['gender'] ?? '');
    $address = FormValidator::sanitizeString($_POST['address'] ?? '');
    $subjects = $_POST['subjects'] ?? [];
    $terms = $_POST['terms'] ?? '';
    
    // Validation errors array
    $validation_errors = [];
    
    // Validate required fields
    $required_fields = ['full_name', 'roll_number', 'email', 'phone', 'class', 'gender', 'address'];
    $missing_fields = FormValidator::validateRequired($_POST, $required_fields);
    
    foreach ($missing_fields as $field) {
        $validation_errors[$field] = ucwords(str_replace('_', ' ', $field)) . ' is required';
    }
    
    // Validate specific fields
    if (!empty($full_name) && strlen($full_name) < 2) {
        $validation_errors['full_name'] = 'Full name must be at least 2 characters long';
    }
    
    if (!FormValidator::validateIntRange($roll_number, 1, 9999)) {
        $validation_errors['roll_number'] = 'Roll number must be between 1 and 9999';
    }
    
    if (!empty($email) && !FormValidator::validateEmail($email)) {
        $validation_errors['email'] = 'Please enter a valid email address';
    }
    
    if (!empty($phone) && !FormValidator::validatePhone($phone)) {
        $validation_errors['phone'] = 'Phone number must be exactly 10 digits';
    }
    
    if (empty($subjects) || !is_array($subjects)) {
        $validation_errors['subjects'] = 'Please select at least one subject';
    }
    
    if ($terms !== 'agreed') {
        $validation_errors['terms'] = 'You must agree to the terms and conditions';
    }
    
    // Check for existing roll number (simulate database check)
    $existing_students_file = 'data/students.json';
    $existing_students = [];
    
    if (file_exists($existing_students_file)) {
        $existing_students = json_decode(file_get_contents($existing_students_file), true) ?? [];
    }
    
    foreach ($existing_students as $student) {
        if ($student['roll_number'] == $roll_number) {
            $validation_errors['roll_number'] = 'This roll number is already registered';
            break;
        }
    }
    
    // If validation errors exist, return them
    if (!empty($validation_errors)) {
        $response['message'] = 'Please correct the following errors:';
        $response['errors'] = $validation_errors;
        echo json_encode($response);
        exit;
    }
    
    // Create student record
    $student_data = [
        'id' => uniqid('student_', true),
        'full_name' => $full_name,
        'roll_number' => (int)$roll_number,
        'email' => $email,
        'phone' => $phone,
        'class' => $class,
        'gender' => $gender,
        'subjects' => $subjects,
        'address' => $address,
        'registration_date' => date('Y-m-d H:i:s'),
        'registration_id' => 'REG' . date('Ymd') . str_pad($roll_number, 4, '0', STR_PAD_LEFT)
    ];
    
    // Save to file (in real application, save to database)
    if (!is_dir('data')) {
        mkdir('data', 0777, true);
    }
    
    $existing_students[] = $student_data;
    
    if (file_put_contents($existing_students_file, json_encode($existing_students, JSON_PRETTY_PRINT))) {
        // Generate success message with registration details
        $success_message = "<h3>🎉 Registration Successful!</h3>";
        $success_message .= "<p><strong>Welcome, " . htmlspecialchars($full_name) . "!</strong></p>";
        $success_message .= "<div style='background: white; padding: 15px; border-radius: 8px; margin: 15px 0;'>";
        $success_message .= "<h4>📋 Registration Details:</h4>";
        $success_message .= "<p><strong>Registration ID:</strong> " . $student_data['registration_id'] . "</p>";
        $success_message .= "<p><strong>Full Name:</strong> " . htmlspecialchars($full_name) . "</p>";
        $success_message .= "<p><strong>Roll Number:</strong> " . $roll_number . "</p>";
        $success_message .= "<p><strong>Class:</strong> " . htmlspecialchars($class) . "</p>";
        $success_message .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        $success_message .= "<p><strong>Subjects:</strong> " . htmlspecialchars(implode(', ', $subjects)) . "</p>";
        $success_message .= "<p><strong>Registration Time:</strong> " . $student_data['registration_date'] . "</p>";
        $success_message .= "</div>";
        $success_message .= "<p>📧 A confirmation email will be sent to your registered email address.</p>";
        $success_message .= "<p>🎓 Please save your Registration ID for future reference.</p>";
        
        $response['success'] = true;
        $response['message'] = $success_message;
        $response['data'] = [
            'registration_id' => $student_data['registration_id'],
            'student_id' => $student_data['id']
        ];
        
        // Set success flash message for traditional form submission
        SessionManager::setFlash('success', 'Student registered successfully! Registration ID: ' . $student_data['registration_id']);
        
    } else {
        throw new Exception("Failed to save registration data. Please try again.");
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// Return JSON response for AJAX requests
echo json_encode($response);

// If this was a traditional form submission (non-AJAX), redirect
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    
    if ($response['success']) {
        header('Location: registration-success.php?id=' . urlencode($response['data']['registration_id']));
    } else {
        header('Location: ajax-registration.php?error=' . urlencode($response['message']));
    }
    exit;
}
?>
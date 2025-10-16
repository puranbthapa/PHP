<!--
/**
 * AJAX Student Registration System
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */
-->
<!DOCTYPE html>
<html>
<head>
    <title>AJAX Student Registration</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .form-section { background-color: #f8f9fa; padding: 30px; border-radius: 10px; margin: 20px 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input, select, textarea { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #e1e5e9; 
            border-radius: 6px; 
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        .submit-btn { 
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white; 
            padding: 15px 30px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3); }
        .submit-btn:disabled { 
            background: #6c757d; 
            cursor: not-allowed; 
            transform: none;
            box-shadow: none;
        }
        .loading { display: none; margin-left: 10px; }
        .result { 
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0; 
            display: none;
            animation: slideIn 0.3s ease-out;
        }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .field-error { color: #dc3545; font-size: 14px; margin-top: 5px; }
        .progress-bar {
            width: 100%;
            height: 4px;
            background-color: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #28a745);
            width: 0%;
            transition: width 0.3s ease;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <h1>🎓 Modern Student Registration System</h1>
    <p>Experience real-time form validation and AJAX submission with fallback support.</p>
    
    <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
    </div>
    
    <div class="form-section">
        <form id="registrationForm" action="ajax-process-registration.php" method="POST">
            <?php
            require_once 'form-validators.php';
            echo FormValidator::csrfTokenInput();
            ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" required>
                    <div class="field-error" id="full_name_error"></div>
                </div>
                
                <div class="form-group">
                    <label for="roll_number">Roll Number *</label>
                    <input type="number" id="roll_number" name="roll_number" min="1" max="9999" required>
                    <div class="field-error" id="roll_number_error"></div>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                    <div class="field-error" id="email_error"></div>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>
                    <div class="field-error" id="phone_error"></div>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="class">Class *</label>
                    <select id="class" name="class" required>
                        <option value="">Select Class</option>
                        <option value="XI-A">XI-A</option>
                        <option value="XI-B">XI-B</option>
                        <option value="XII-A">XII-A</option>
                        <option value="XII-B">XII-B</option>
                    </select>
                    <div class="field-error" id="class_error"></div>
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender *</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="field-error" id="gender_error"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="subjects">Subjects (Hold Ctrl/Cmd to select multiple) *</label>
                <select id="subjects" name="subjects[]" multiple required style="height: 120px;">
                    <option value="Physics">Physics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="English">English</option>
                    <option value="Biology">Biology</option>
                    <option value="Economics">Economics</option>
                </select>
                <div class="field-error" id="subjects_error"></div>
            </div>
            
            <div class="form-group">
                <label for="address">Address *</label>
                <textarea id="address" name="address" rows="3" required></textarea>
                <div class="field-error" id="address_error"></div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="terms" name="terms" value="agreed" required>
                    I agree to the terms and conditions *
                </label>
                <div class="field-error" id="terms_error"></div>
            </div>
            
            <button type="submit" class="submit-btn" id="submitBtn">
                Register Student
                <span class="loading" id="loading">🔄 Processing...</span>
            </button>
        </form>
    </div>
    
    <div id="result" class="result"></div>
    
    <div class="form-section">
        <h3>🔧 Technical Features Demonstrated:</h3>
        <ul>
            <li><strong>AJAX Submission:</strong> Form submits without page reload</li>
            <li><strong>Real-time Validation:</strong> Client-side validation with visual feedback</li>
            <li><strong>Progressive Enhancement:</strong> Works even if JavaScript is disabled</li>
            <li><strong>CSRF Protection:</strong> Security token prevents cross-site attacks</li>
            <li><strong>Visual Feedback:</strong> Loading states and smooth animations</li>
            <li><strong>Responsive Design:</strong> Adapts to mobile and desktop screens</li>
        </ul>
    </div>

    <script>
        // Form validation and AJAX submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');
            const result = document.getElementById('result');
            const progressFill = document.getElementById('progressFill');
            
            // Real-time validation
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', validateField);
                input.addEventListener('blur', validateField);
            });
            
            // Update progress bar based on filled fields
            function updateProgress() {
                const requiredFields = form.querySelectorAll('[required]');
                let filled = 0;
                
                requiredFields.forEach(field => {
                    if (field.type === 'checkbox') {
                        if (field.checked) filled++;
                    } else if (field.multiple) {
                        if (field.selectedOptions.length > 0) filled++;
                    } else if (field.value.trim()) {
                        filled++;
                    }
                });
                
                const percentage = (filled / requiredFields.length) * 100;
                progressFill.style.width = percentage + '%';
            }
            
            // Real-time field validation
            function validateField(e) {
                const field = e.target;
                const errorDiv = document.getElementById(field.name + '_error');
                let error = '';
                
                // Clear previous styling
                field.style.borderColor = '';
                
                if (field.hasAttribute('required') && !field.value.trim()) {
                    error = 'This field is required';
                } else {
                    switch (field.type) {
                        case 'email':
                            if (field.value && !isValidEmail(field.value)) {
                                error = 'Please enter a valid email address';
                            }
                            break;
                        case 'tel':
                            if (field.value && !isValidPhone(field.value)) {
                                error = 'Please enter a 10-digit phone number';
                            }
                            break;
                        case 'number':
                            if (field.value && (field.value < 1 || field.value > 9999)) {
                                error = 'Roll number must be between 1 and 9999';
                            }
                            break;
                    }
                    
                    // Special validation for multiple select
                    if (field.multiple && field.hasAttribute('required')) {
                        if (field.selectedOptions.length === 0) {
                            error = 'Please select at least one subject';
                        }
                    }
                }
                
                if (errorDiv) {
                    errorDiv.textContent = error;
                    if (error) {
                        field.style.borderColor = '#dc3545';
                    } else {
                        field.style.borderColor = '#28a745';
                    }
                }
                
                updateProgress();
                return !error;
            }
            
            // Helper validation functions
            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }
            
            function isValidPhone(phone) {
                return /^\d{10}$/.test(phone.replace(/\D/g, ''));
            }
            
            // Form submission with AJAX
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate all fields
                let isValid = true;
                inputs.forEach(input => {
                    if (!validateField({ target: input })) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    showResult('Please correct the errors above.', 'error');
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                loading.style.display = 'inline';
                result.style.display = 'none';
                
                // Prepare form data
                const formData = new FormData(form);
                
                // AJAX request
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .catch(() => {
                    // If JSON parsing fails, fallback to traditional form submission
                    console.log('AJAX failed, falling back to traditional submission');
                    form.submit();
                    return null;
                })
                .then(data => {
                    if (data) {
                        submitBtn.disabled = false;
                        loading.style.display = 'none';
                        
                        if (data.success) {
                            showResult(data.message, 'success');
                            form.reset();
                            progressFill.style.width = '0%';
                            
                            // Clear field styling
                            inputs.forEach(input => {
                                input.style.borderColor = '';
                                const errorDiv = document.getElementById(input.name + '_error');
                                if (errorDiv) errorDiv.textContent = '';
                            });
                        } else {
                            showResult(data.message, 'error');
                            
                            // Show field-specific errors
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    const errorDiv = document.getElementById(field + '_error');
                                    const fieldEl = document.getElementById(field);
                                    if (errorDiv) {
                                        errorDiv.textContent = data.errors[field];
                                    }
                                    if (fieldEl) {
                                        fieldEl.style.borderColor = '#dc3545';
                                    }
                                });
                            }
                        }
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    loading.style.display = 'none';
                    showResult('An error occurred. Please try again.', 'error');
                    console.error('Error:', error);
                });
            });
            
            // Show result message
            function showResult(message, type) {
                result.className = 'result ' + type;
                result.innerHTML = message;
                result.style.display = 'block';
                result.scrollIntoView({ behavior: 'smooth' });
            }
            
            // Initialize progress
            updateProgress();
        });
    </script>
</body>
</html>
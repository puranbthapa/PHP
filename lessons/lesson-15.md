# Lesson 15: Project Development & Deployment
**Course:** PHP Web Development - Class XII  
**Duration:** 3 hours  
**Prerequisites:** Lessons 1-14 completed, understanding of full-stack development

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Plan and structure a complete web application project
2. Implement MVC (Model-View-Controller) architecture basics
3. Deploy PHP applications to web hosting services
4. Apply security best practices throughout the application
5. Optimize application performance and user experience
6. Document and present their final project

---

## Key Concepts

### 1. Project Planning and Architecture

#### Project Structure:
```
student-portal/
├── index.php (Landing page)
├── config/
│   ├── database.php
│   └── config.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── admin/
│   ├── dashboard.php
│   ├── manage-students.php
│   └── reports.php
├── student/
│   ├── dashboard.php
│   ├── profile.php
│   └── grades.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/
└── database/
    └── schema.sql
```

#### MVC Architecture Basics:
- **Model:** Data handling and database operations
- **View:** User interface and presentation
- **Controller:** Business logic and user input handling

### 2. Security Implementation Checklist

#### Essential Security Measures:
- Input validation and sanitization
- SQL injection prevention
- XSS (Cross-Site Scripting) protection
- CSRF (Cross-Site Request Forgery) tokens
- Secure password hashing
- File upload restrictions
- Session security

### 3. Performance Optimization

#### Optimization Techniques:
- Efficient database queries
- Code organization and reusability
- Image optimization
- CSS and JavaScript minification
- Caching strategies

---

## Final Project: Student Portal System

### Project Requirements

#### Core Features:
1. **User Authentication System**
   - Student login/logout
   - Admin login with different privileges
   - Password recovery system
   - Session management

2. **Student Management**
   - Student registration and profiles
   - Grade management
   - Attendance tracking
   - Document uploads

3. **Administrative Features**
   - Dashboard with statistics
   - Student management interface
   - Report generation
   - System settings

4. **Database Integration**
   - MySQL database with proper schema
   - CRUD operations for all entities
   - Data validation and integrity

### Implementation Guide

#### Step 1: Database Schema Creation

**File: `database/schema.sql`**
```sql
-- Student Portal Database Schema

CREATE DATABASE student_portal;
USE student_portal;

-- Users table (for authentication)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    user_type ENUM('student', 'admin') DEFAULT 'student',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    roll_number VARCHAR(20) UNIQUE NOT NULL,
    class VARCHAR(10) NOT NULL,
    date_of_birth DATE,
    phone VARCHAR(15),
    address TEXT,
    guardian_name VARCHAR(100),
    guardian_phone VARCHAR(15),
    admission_date DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Subjects table
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    subject_code VARCHAR(10) UNIQUE NOT NULL,
    credits INT DEFAULT 1,
    description TEXT
);

-- Grades table
CREATE TABLE grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    exam_type ENUM('assignment', 'quiz', 'midterm', 'final') NOT NULL,
    marks_obtained DECIMAL(5,2) NOT NULL,
    total_marks DECIMAL(5,2) NOT NULL,
    exam_date DATE NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE
);

-- Attendance table
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late') DEFAULT 'present',
    remarks TEXT,
    marked_by INT,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    FOREIGN KEY (marked_by) REFERENCES users(user_id)
);

-- Documents table
CREATE TABLE documents (
    document_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    document_name VARCHAR(255) NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO subjects (subject_name, subject_code, credits) VALUES
('Mathematics', 'MATH101', 4),
('Physics', 'PHY101', 4),
('Chemistry', 'CHEM101', 4),
('Computer Science', 'CS101', 4),
('English', 'ENG101', 3);

-- Insert admin user (password: admin123)
INSERT INTO users (username, email, password_hash, user_type) VALUES
('admin', 'admin@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

#### Step 2: Configuration Files

**File: `config/database.php`**
```php
<?php
// Database configuration
class Database {
    private $host = 'localhost';
    private $db_name = 'student_portal';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Security functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Session management
function start_secure_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function check_login() {
    start_secure_session();
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

function require_login() {
    if (!check_login()) {
        header('Location: login.php');
        exit();
    }
}

function check_admin() {
    start_secure_session();
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function require_admin() {
    require_login();
    if (!check_admin()) {
        header('Location: dashboard.php');
        exit();
    }
}
?>
```

**File: `config/config.php`**
```php
<?php
// Application configuration
define('APP_NAME', 'Student Portal System');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/student-portal/');

// File upload settings
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx']);

// Pagination settings
define('RECORDS_PER_PAGE', 10);

// Email settings (for notifications)
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@domain.com');
define('SMTP_PASSWORD', 'your-password');

// Security settings
define('CSRF_TOKEN_LENGTH', 32);
define('SESSION_TIMEOUT', 3600); // 1 hour

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Kolkata');
?>
```

#### Step 3: Main Landing Page

**File: `index.php`**
```php
<?php
require_once 'config/config.php';
require_once 'config/database.php';

start_secure_session();

// Redirect if already logged in
if (check_login()) {
    if (check_admin()) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: student/dashboard.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap"></i> <?php echo APP_NAME; ?>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="login.php">Login</a>
                <a class="nav-link" href="register.php">Register</a>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Welcome to Student Portal</h1>
                    <p class="lead mb-4">
                        Manage your academic journey with our comprehensive student management system. 
                        Access grades, attendance, assignments, and more in one centralized platform.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="login.php" class="btn btn-primary btn-lg px-4 me-md-2">
                            <i class="fas fa-sign-in-alt"></i> Student Login
                        </a>
                        <a href="register.php" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-user-plus"></i> New Registration
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/student-portal.jpg" alt="Student Portal" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <div class="features-section py-5">
        <div class="container">
            <h2 class="text-center mb-5">Key Features</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Grade Tracking</h5>
                            <p class="card-text">Monitor your academic progress with detailed grade reports and analytics.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar-check fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Attendance Management</h5>
                            <p class="card-text">Keep track of your attendance across all subjects and sessions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-file-upload fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Document Management</h5>
                            <p class="card-text">Upload and manage important academic documents securely.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2025 <?php echo APP_NAME; ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>Version <?php echo APP_VERSION; ?> | Built with PHP & MySQL</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
</body>
</html>
```

#### Step 4: Authentication System

**File: `login.php`**
```php
<?php
require_once 'config/config.php';
require_once 'config/database.php';

start_secure_session();

// Redirect if already logged in
if (check_login()) {
    if (check_admin()) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: student/dashboard.php');
    }
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT user_id, username, password_hash, user_type, is_active FROM users WHERE username = :username OR email = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user['is_active'] && verify_password($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['login_time'] = time();
                
                // Redirect based on user type
                if ($user['user_type'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: student/dashboard.php');
                }
                exit();
            } else {
                $error_message = 'Invalid credentials or account disabled.';
            }
        } else {
            $error_message = 'User not found.';
        }
    } else {
        $error_message = 'Please fill in all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/auth.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card mt-5">
                    <div class="card-header text-center bg-primary text-white">
                        <h4><i class="fas fa-sign-in-alt"></i> Student Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username or Email</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        
                        <hr>
                        <div class="text-center">
                            <p>Don't have an account? <a href="register.php">Register here</a></p>
                            <p><a href="forgot-password.php">Forgot Password?</a></p>
                            <p><a href="index.php">Back to Home</a></p>
                        </div>
                        
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-muted">
                                <strong>Demo Credentials:</strong><br>
                                Admin: username: <code>admin</code>, password: <code>admin123</code><br>
                                Student: Register new account or use existing credentials
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
</body>
</html>
```

---

## Deployment Guide

### 1. Local to Production Checklist

#### Pre-deployment Steps:
1. **Code Review and Testing**
   ```php
   // Disable error display in production
   error_reporting(0);
   ini_set('display_errors', 0);
   
   // Enable error logging
   ini_set('log_errors', 1);
   ini_set('error_log', '/path/to/error.log');
   ```

2. **Database Configuration**
   - Export database structure and data
   - Update database credentials for production
   - Test database connections

3. **Security Hardening**
   - Change default passwords
   - Update file permissions
   - Configure HTTPS
   - Enable secure headers

#### File Permissions:
```bash
# Set appropriate permissions
chmod 755 /path/to/project/
chmod 644 /path/to/project/*.php
chmod 777 /path/to/project/uploads/
chmod 600 /path/to/project/config/database.php
```

### 2. Web Hosting Deployment

#### Steps for Shared Hosting:
1. **File Upload**
   - Compress project files (ZIP)
   - Upload via FTP/cPanel File Manager
   - Extract files in public_html

2. **Database Setup**
   - Create MySQL database via cPanel
   - Import database schema
   - Update database configuration

3. **Domain Configuration**
   - Point domain to hosting server
   - Configure DNS settings
   - Set up SSL certificate

#### Free Hosting Options for Students:
- 000webhost.com
- infinityfree.net
- freehostia.com
- heroku.com (with ClearDB MySQL)

### 3. Performance Optimization

#### Code Optimization:
```php
// Use prepared statements
$stmt = $conn->prepare("SELECT * FROM students WHERE class = ?");
$stmt->execute([$class]);

// Implement caching
function get_cached_data($key, $callback, $expiry = 3600) {
    $cache_file = "cache/{$key}.cache";
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $expiry) {
        return unserialize(file_get_contents($cache_file));
    }
    
    $data = $callback();
    file_put_contents($cache_file, serialize($data));
    return $data;
}

// Optimize images
function optimize_image($source, $destination, $quality = 75) {
    $info = getimagesize($source);
    
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination);
    }
    
    imagedestroy($image);
}
```

---

## Project Assessment Criteria

### Technical Implementation (40%)
- [ ] Complete MVC architecture implementation
- [ ] Secure authentication system
- [ ] Database integration with CRUD operations
- [ ] Input validation and sanitization
- [ ] Error handling and user feedback
- [ ] File upload functionality
- [ ] Responsive design implementation

### Code Quality (25%)
- [ ] Clean, well-commented code
- [ ] Consistent naming conventions
- [ ] Proper code organization
- [ ] Security best practices
- [ ] Database normalization
- [ ] Efficient queries and operations

### User Experience (20%)
- [ ] Intuitive navigation
- [ ] Responsive design
- [ ] Clear error messages
- [ ] Fast loading times
- [ ] Accessibility considerations
- [ ] Cross-browser compatibility

### Documentation (15%)
- [ ] Complete README file
- [ ] Installation instructions
- [ ] User manual
- [ ] Code comments
- [ ] Database schema documentation
- [ ] API documentation (if applicable)

---

## Final Project Presentation

### Presentation Structure (15 minutes):
1. **Project Overview (3 minutes)**
   - Problem statement and solution
   - Key features demonstration
   - Technology stack used

2. **Technical Implementation (7 minutes)**
   - Architecture explanation
   - Database design
   - Security measures implemented
   - Code walkthrough (key components)

3. **Live Demonstration (4 minutes)**
   - User registration and login
   - Core functionality showcase
   - Admin panel features
   - Mobile responsiveness

4. **Q&A Session (1 minute)**
   - Technical questions
   - Implementation challenges
   - Future enhancements

### Deliverables:
- [ ] Complete source code (zip file)
- [ ] Database dump file
- [ ] Installation guide
- [ ] User manual
- [ ] Presentation slides
- [ ] Live demo (optional: deployed version)

---

## Course Completion Certificate

Upon successful completion of the final project with a score of 70% or above, students will receive a certificate of completion for "PHP Web Development - Class XII" covering:

- PHP Programming Fundamentals
- Database Integration with MySQL
- Web Application Development
- Security Implementation
- Project Development and Deployment

---

## Further Learning Path

### Advanced Topics to Explore:
1. **PHP Frameworks**
   - Laravel, CodeIgniter, Symfony
   - MVC architecture in depth
   - ORM (Object-Relational Mapping)

2. **Advanced Database Concepts**
   - Database optimization
   - Indexing strategies
   - Stored procedures and triggers

3. **Modern Web Development**
   - RESTful API development
   - AJAX and asynchronous programming
   - Frontend frameworks integration

4. **DevOps and Deployment**
   - Version control with Git
   - Automated testing
   - CI/CD pipelines
   - Cloud deployment

---

## Resources for Continued Learning

### Online Platforms:
- **PHP Official Documentation:** https://www.php.net/docs.php
- **W3Schools PHP Tutorial:** https://www.w3schools.com/php/
- **Codecademy PHP Course:** https://www.codecademy.com/learn/learn-php
- **Udemy PHP Courses:** Various comprehensive courses
- **YouTube Channels:** Programming with Mosh, Traversy Media

### Books:
- "PHP: The Complete Reference" by Steven Holzner
- "Learning PHP, MySQL & JavaScript" by Robin Nixon
- "Modern PHP" by Josh Lockhart

### Practice Platforms:
- **GitHub:** Host your projects and collaborate
- **Stack Overflow:** Community support and problem solving
- **CodePen:** Frontend experimentation
- **PHPFiddle:** Online PHP testing

---

*Congratulations on completing the PHP Web Development course! This final lesson represents the culmination of your learning journey and the beginning of your career as a web developer. Continue practicing, building projects, and staying updated with the latest web development trends.*
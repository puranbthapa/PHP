# Lesson 13: Advanced Database Operations & Security
**Course:** PHP Web Development - Class XII  
**Duration:** 3 hours  
**Prerequisites:** Lessons 1-12 completed, basic CRUD operations, understanding of database relationships

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Write complex SQL queries with JOINs and subqueries
2. Implement advanced data aggregation and analysis
3. Apply database security best practices and SQL injection prevention
4. Optimize database performance and indexing
5. Build secure authentication and authorization systems
6. Handle database transactions and error recovery

---

## Key Concepts

### 1. Advanced SQL Queries

#### JOIN Operations:
```sql
-- INNER JOIN: Students with their grades
SELECT s.first_name, s.last_name, s.roll_number, 
       sub.subject_name, g.marks_obtained, g.total_marks
FROM students s
INNER JOIN grades g ON s.student_id = g.student_id
INNER JOIN subjects sub ON g.subject_id = sub.subject_id
WHERE s.class = 'XII-A'
ORDER BY s.first_name, sub.subject_name;

-- LEFT JOIN: All students with or without grades
SELECT s.first_name, s.last_name, 
       COUNT(g.grade_id) as total_grades,
       AVG(g.marks_obtained) as average_marks
FROM students s
LEFT JOIN grades g ON s.student_id = g.student_id
GROUP BY s.student_id, s.first_name, s.last_name;

-- MULTIPLE JOIN: Student, Class, Teacher information
SELECT s.first_name, s.last_name, 
       c.class_name, c.room_number,
       t.first_name as teacher_name, t.last_name as teacher_surname
FROM students s
INNER JOIN classes c ON s.class_id = c.class_id
INNER JOIN teachers t ON c.class_teacher_id = t.teacher_id;
```

#### Subqueries and Advanced Filtering:
```sql
-- Students with above-average performance
SELECT s.first_name, s.last_name, AVG(g.marks_obtained) as avg_marks
FROM students s
INNER JOIN grades g ON s.student_id = g.student_id
GROUP BY s.student_id, s.first_name, s.last_name
HAVING AVG(g.marks_obtained) > (
    SELECT AVG(marks_obtained) FROM grades
);

-- Top 3 performers in each subject
SELECT subject_name, first_name, last_name, marks_obtained, rank_position
FROM (
    SELECT sub.subject_name, s.first_name, s.last_name, g.marks_obtained,
           ROW_NUMBER() OVER (PARTITION BY sub.subject_id ORDER BY g.marks_obtained DESC) as rank_position
    FROM grades g
    INNER JOIN students s ON g.student_id = s.student_id
    INNER JOIN subjects sub ON g.subject_id = sub.subject_id
) ranked_students
WHERE rank_position <= 3;
```

### 2. Data Aggregation and Analytics

#### Complex Reporting Queries:
```php
<?php
// Advanced analytics functions
class DatabaseAnalytics {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Student performance analysis
    public function getStudentPerformanceReport($student_id) {
        $sql = "
            SELECT 
                s.first_name, s.last_name, s.roll_number,
                COUNT(g.grade_id) as total_exams,
                AVG(g.marks_obtained) as average_marks,
                MAX(g.marks_obtained) as highest_marks,
                MIN(g.marks_obtained) as lowest_marks,
                SUM(CASE WHEN g.marks_obtained >= 60 THEN 1 ELSE 0 END) as passed_subjects,
                SUM(CASE WHEN g.marks_obtained < 60 THEN 1 ELSE 0 END) as failed_subjects
            FROM students s
            LEFT JOIN grades g ON s.student_id = g.student_id
            WHERE s.student_id = ?
            GROUP BY s.student_id
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetch();
    }
    
    // Subject-wise class performance
    public function getClassPerformanceBySubject($class_name) {
        $sql = "
            SELECT 
                sub.subject_name,
                COUNT(g.grade_id) as total_students,
                AVG(g.marks_obtained) as class_average,
                MAX(g.marks_obtained) as highest_score,
                MIN(g.marks_obtained) as lowest_score,
                STDDEV(g.marks_obtained) as standard_deviation,
                SUM(CASE WHEN g.marks_obtained >= 90 THEN 1 ELSE 0 END) as grade_A,
                SUM(CASE WHEN g.marks_obtained >= 80 AND g.marks_obtained < 90 THEN 1 ELSE 0 END) as grade_B,
                SUM(CASE WHEN g.marks_obtained >= 60 AND g.marks_obtained < 80 THEN 1 ELSE 0 END) as grade_C,
                SUM(CASE WHEN g.marks_obtained < 60 THEN 1 ELSE 0 END) as grade_F
            FROM subjects sub
            INNER JOIN grades g ON sub.subject_id = g.subject_id
            INNER JOIN students s ON g.student_id = s.student_id
            WHERE s.class = ?
            GROUP BY sub.subject_id, sub.subject_name
            ORDER BY class_average DESC
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$class_name]);
        return $stmt->fetchAll();
    }
    
    // Teacher workload analysis
    public function getTeacherWorkloadReport() {
        $sql = "
            SELECT 
                t.first_name, t.last_name,
                COUNT(DISTINCT c.class_id) as classes_assigned,
                COUNT(DISTINCT s.student_id) as total_students,
                COUNT(g.grade_id) as grades_entered,
                AVG(g.marks_obtained) as average_class_performance
            FROM teachers t
            LEFT JOIN classes c ON t.teacher_id = c.class_teacher_id
            LEFT JOIN students s ON c.class_id = s.class_id
            LEFT JOIN grades g ON s.student_id = g.student_id AND g.teacher_id = t.teacher_id
            GROUP BY t.teacher_id, t.first_name, t.last_name
            ORDER BY total_students DESC
        ";
        
        return $this->pdo->query($sql)->fetchAll();
    }
}
?>
```

### 3. Database Security Implementation

#### SQL Injection Prevention:
```php
<?php
class SecureDatabase {
    private $pdo;
    private $allowed_tables = ['students', 'teachers', 'subjects', 'grades'];
    private $allowed_columns = [
        'students' => ['first_name', 'last_name', 'email', 'class', 'roll_number'],
        'teachers' => ['first_name', 'last_name', 'department'],
        'subjects' => ['subject_name', 'subject_code'],
        'grades' => ['marks_obtained', 'exam_date']
    ];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Set secure connection attributes
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    
    // Secure dynamic query builder
    public function secureSearch($table, $search_column, $search_term, $order_column = 'id') {
        // Validate table name
        if (!in_array($table, $this->allowed_tables)) {
            throw new InvalidArgumentException("Invalid table name");
        }
        
        // Validate column names
        if (!in_array($search_column, $this->allowed_columns[$table])) {
            throw new InvalidArgumentException("Invalid search column");
        }
        
        if (!in_array($order_column, $this->allowed_columns[$table])) {
            $order_column = $this->allowed_columns[$table][0]; // Use first allowed column
        }
        
        // Build secure query
        $sql = "SELECT * FROM `$table` WHERE `$search_column` LIKE ? ORDER BY `$order_column`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$search_term%"]);
        
        return $stmt->fetchAll();
    }
    
    // Input validation and sanitization
    public function validateInput($data, $rules) {
        $validated = [];
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = trim($data[$field] ?? '');
            
            // Required field check
            if ($rule['required'] && empty($value)) {
                $errors[$field] = "Field '$field' is required";
                continue;
            }
            
            // Type validation
            switch ($rule['type']) {
                case 'email':
                    if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = "Invalid email format";
                    }
                    break;
                    
                case 'numeric':
                    if (!empty($value) && !is_numeric($value)) {
                        $errors[$field] = "Field '$field' must be numeric";
                    }
                    break;
                    
                case 'string':
                    if (!empty($value) && !is_string($value)) {
                        $errors[$field] = "Field '$field' must be a string";
                    }
                    break;
                    
                case 'date':
                    if (!empty($value) && !strtotime($value)) {
                        $errors[$field] = "Invalid date format";
                    }
                    break;
            }
            
            // Length validation
            if (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
                $errors[$field] = "Field '$field' exceeds maximum length of {$rule['max_length']}";
            }
            
            if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
                $errors[$field] = "Field '$field' must be at least {$rule['min_length']} characters";
            }
            
            // Pattern validation
            if (isset($rule['pattern']) && !preg_match($rule['pattern'], $value)) {
                $errors[$field] = "Field '$field' format is invalid";
            }
            
            if (empty($errors[$field])) {
                $validated[$field] = $value;
            }
        }
        
        if (!empty($errors)) {
            throw new ValidationException("Validation failed", $errors);
        }
        
        return $validated;
    }
}

// Custom exception for validation errors
class ValidationException extends Exception {
    private $validation_errors;
    
    public function __construct($message, $errors = []) {
        parent::__construct($message);
        $this->validation_errors = $errors;
    }
    
    public function getValidationErrors() {
        return $this->validation_errors;
    }
}
?>
```

### 4. Authentication and Authorization System

#### Secure User Authentication:
```php
<?php
class AuthenticationSystem {
    private $pdo;
    private $session_timeout = 3600; // 1 hour
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Secure password hashing
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    // User registration with validation
    public function registerUser($username, $email, $password, $role = 'student') {
        // Validate inputs
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Check if user already exists
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Username or email already exists");
        }
        
        // Insert new user
        $hashed_password = $this->hashPassword($password);
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, email, password_hash, role, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([$username, $email, $hashed_password, $role]);
        return $this->pdo->lastInsertId();
    }
    
    // Secure login
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("
            SELECT user_id, username, email, password_hash, role, last_login 
            FROM users 
            WHERE username = ? OR email = ?
        ");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            // Log failed login attempt
            $this->logLoginAttempt($username, false);
            throw new Exception("Invalid username or password");
        }
        
        // Update last login
        $stmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);
        
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time();
        
        // Log successful login
        $this->logLoginAttempt($username, true);
        
        return $user;
    }
    
    // Check if user is authenticated
    public function isAuthenticated() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['login_time'])) {
            return false;
        }
        
        // Check session timeout
        if (time() - $_SESSION['login_time'] > $this->session_timeout) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    // Check user permissions
    public function hasPermission($required_role) {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        $user_role = $_SESSION['role'];
        
        // Role hierarchy: admin > teacher > student
        $role_levels = ['student' => 1, 'teacher' => 2, 'admin' => 3];
        
        return $role_levels[$user_role] >= $role_levels[$required_role];
    }
    
    // Secure logout
    public function logout() {
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }
    
    // Log login attempts for security monitoring
    private function logLoginAttempt($username, $success) {
        $stmt = $this->pdo->prepare("
            INSERT INTO login_logs (username, success, ip_address, user_agent, attempt_time) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $username,
            $success ? 1 : 0,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    }
}
?>
```

### 5. Database Transactions and Error Recovery

#### Transaction Management:
```php
<?php
class TransactionManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Complex transaction example: Student enrollment with grade initialization
    public function enrollStudentInSubjects($student_id, $subject_ids, $teacher_assignments) {
        try {
            $this->pdo->beginTransaction();
            
            // Verify student exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM students WHERE student_id = ?");
            $stmt->execute([$student_id]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception("Student not found");
            }
            
            foreach ($subject_ids as $index => $subject_id) {
                // Check if already enrolled
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) FROM enrollments 
                    WHERE student_id = ? AND subject_id = ?
                ");
                $stmt->execute([$student_id, $subject_id]);
                
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Student already enrolled in subject ID: $subject_id");
                }
                
                // Enroll student
                $stmt = $this->pdo->prepare("
                    INSERT INTO enrollments (student_id, subject_id, teacher_id, enrollment_date) 
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$student_id, $subject_id, $teacher_assignments[$index]]);
                
                // Initialize attendance record
                $stmt = $this->pdo->prepare("
                    INSERT INTO attendance_summary (student_id, subject_id, total_classes, attended_classes) 
                    VALUES (?, ?, 0, 0)
                ");
                $stmt->execute([$student_id, $subject_id]);
            }
            
            $this->pdo->commit();
            return "Student enrolled successfully in " . count($subject_ids) . " subjects";
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Enrollment failed: " . $e->getMessage());
        }
    }
    
    // Batch grade update with validation
    public function updateBatchGrades($grade_updates) {
        try {
            $this->pdo->beginTransaction();
            
            foreach ($grade_updates as $update) {
                // Validate marks
                if ($update['marks'] < 0 || $update['marks'] > $update['total_marks']) {
                    throw new Exception("Invalid marks for student ID: " . $update['student_id']);
                }
                
                // Update grade
                $stmt = $this->pdo->prepare("
                    UPDATE grades 
                    SET marks_obtained = ?, updated_at = NOW() 
                    WHERE grade_id = ? AND student_id = ?
                ");
                
                $result = $stmt->execute([
                    $update['marks'], 
                    $update['grade_id'], 
                    $update['student_id']
                ]);
                
                if ($stmt->rowCount() == 0) {
                    throw new Exception("Grade record not found for student ID: " . $update['student_id']);
                }
                
                // Update student's overall performance
                $this->updateStudentPerformance($update['student_id']);
            }
            
            $this->pdo->commit();
            return "Batch grades updated successfully";
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Batch update failed: " . $e->getMessage());
        }
    }
    
    private function updateStudentPerformance($student_id) {
        $stmt = $this->pdo->prepare("
            UPDATE students SET 
                overall_percentage = (
                    SELECT AVG((marks_obtained / total_marks) * 100) 
                    FROM grades 
                    WHERE student_id = ?
                ),
                updated_at = NOW()
            WHERE student_id = ?
        ");
        $stmt->execute([$student_id, $student_id]);
    }
}
?>
```

---

## Practical Activities

### Activity 1: Advanced School Analytics Dashboard (90 minutes)

#### Create file: `advanced-analytics-dashboard.php`
```php
<?php
// Advanced Analytics Dashboard
require_once 'database-config.php';
require_once 'authentication-system.php';

$auth = new AuthenticationSystem($pdo);

// Check authentication and permissions
if (!$auth->isAuthenticated() || !$auth->hasPermission('teacher')) {
    header('Location: login.php');
    exit;
}

$analytics = new DatabaseAnalytics($pdo);

// Get dashboard data
$overall_stats = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM students) as total_students,
        (SELECT COUNT(*) FROM teachers) as total_teachers,
        (SELECT COUNT(*) FROM subjects) as total_subjects,
        (SELECT COUNT(*) FROM grades) as total_grades,
        (SELECT AVG(marks_obtained) FROM grades) as overall_average
")->fetch();

$class_performance = $pdo->query("
    SELECT 
        s.class,
        COUNT(DISTINCT s.student_id) as student_count,
        AVG(g.marks_obtained) as class_average,
        MAX(g.marks_obtained) as highest_score,
        MIN(g.marks_obtained) as lowest_score
    FROM students s
    LEFT JOIN grades g ON s.student_id = g.student_id
    GROUP BY s.class
    ORDER BY class_average DESC
")->fetchAll();

$subject_performance = $pdo->query("
    SELECT 
        sub.subject_name,
        COUNT(g.grade_id) as total_grades,
        AVG(g.marks_obtained) as subject_average,
        STDDEV(g.marks_obtained) as std_deviation,
        COUNT(CASE WHEN g.marks_obtained >= 60 THEN 1 END) as pass_count,
        COUNT(CASE WHEN g.marks_obtained < 60 THEN 1 END) as fail_count
    FROM subjects sub
    LEFT JOIN grades g ON sub.subject_id = g.subject_id
    GROUP BY sub.subject_id, sub.subject_name
    ORDER BY subject_average DESC
")->fetchAll();

$top_performers = $pdo->query("
    SELECT 
        s.first_name, s.last_name, s.class,
        AVG(g.marks_obtained) as average_marks,
        COUNT(g.grade_id) as total_exams
    FROM students s
    INNER JOIN grades g ON s.student_id = g.student_id
    GROUP BY s.student_id
    HAVING COUNT(g.grade_id) >= 3
    ORDER BY average_marks DESC
    LIMIT 10
")->fetchAll();

$failing_students = $pdo->query("
    SELECT 
        s.first_name, s.last_name, s.class, s.roll_number,
        AVG(g.marks_obtained) as average_marks,
        COUNT(CASE WHEN g.marks_obtained < 60 THEN 1 END) as failed_subjects
    FROM students s
    INNER JOIN grades g ON s.student_id = g.student_id
    GROUP BY s.student_id
    HAVING AVG(g.marks_obtained) < 60 OR COUNT(CASE WHEN g.marks_obtained < 60 THEN 1 END) >= 2
    ORDER BY average_marks ASC
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Advanced Analytics Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .widget { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; padding: 30px; border-radius: 10px; }
        .stat-number { font-size: 3em; font-weight: bold; margin-bottom: 10px; }
        .stat-label { font-size: 1.2em; opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .progress-bar { width: 100%; height: 20px; background-color: #e0e0e0; border-radius: 10px; overflow: hidden; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #4CAF50, #8BC34A); transition: width 0.3s ease; }
        .alert { padding: 15px; border-radius: 5px; margin: 10px 0; }
        .alert-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .chart-container { position: relative; height: 300px; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>📊 Advanced Analytics Dashboard</h1>
    
    <!-- Overall Statistics -->
    <div class="dashboard">
        <div class="stat-card">
            <div class="stat-number"><?php echo number_format($overall_stats['total_students']); ?></div>
            <div class="stat-label">Total Students</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stat-number"><?php echo number_format($overall_stats['total_teachers']); ?></div>
            <div class="stat-label">Active Teachers</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="stat-number"><?php echo number_format($overall_stats['total_subjects']); ?></div>
            <div class="stat-label">Subjects Offered</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="stat-number"><?php echo number_format($overall_stats['overall_average'], 1); ?>%</div>
            <div class="stat-label">Overall Average</div>
        </div>
    </div>
    
    <div class="dashboard">
        <!-- Class Performance -->
        <div class="widget">
            <h2>📚 Class Performance Analysis</h2>
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Students</th>
                        <th>Average</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($class_performance as $class): ?>
                        <tr>
                            <td><strong><?php echo $class['class']; ?></strong></td>
                            <td><?php echo $class['student_count']; ?></td>
                            <td><?php echo number_format($class['class_average'], 1); ?>%</td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo min(100, $class['class_average']); ?>%"></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Subject Performance -->
        <div class="widget">
            <h2>🎯 Subject Performance Metrics</h2>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Avg Score</th>
                        <th>Pass Rate</th>
                        <th>Difficulty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subject_performance as $subject): ?>
                        <?php 
                        $total_students = $subject['pass_count'] + $subject['fail_count'];
                        $pass_rate = $total_students > 0 ? ($subject['pass_count'] / $total_students) * 100 : 0;
                        $difficulty = $subject['subject_average'] < 60 ? 'High' : ($subject['subject_average'] < 75 ? 'Medium' : 'Low');
                        ?>
                        <tr>
                            <td><strong><?php echo $subject['subject_name']; ?></strong></td>
                            <td><?php echo number_format($subject['subject_average'], 1); ?>%</td>
                            <td><?php echo number_format($pass_rate, 1); ?>%</td>
                            <td>
                                <span style="color: <?php echo $difficulty == 'High' ? 'red' : ($difficulty == 'Medium' ? 'orange' : 'green'); ?>">
                                    <?php echo $difficulty; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Top Performers -->
        <div class="widget">
            <h2>🏆 Top Performing Students</h2>
            <?php if (empty($top_performers)): ?>
                <div class="alert alert-warning">No performance data available yet.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Average</th>
                            <th>Exams</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_performers as $index => $student): ?>
                            <tr>
                                <td>
                                    <?php 
                                    echo $index + 1;
                                    if ($index == 0) echo ' 🥇';
                                    elseif ($index == 1) echo ' 🥈';
                                    elseif ($index == 2) echo ' 🥉';
                                    ?>
                                </td>
                                <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                <td><?php echo $student['class']; ?></td>
                                <td><strong><?php echo number_format($student['average_marks'], 1); ?>%</strong></td>
                                <td><?php echo $student['total_exams']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Students Needing Attention -->
        <div class="widget">
            <h2>⚠️ Students Requiring Attention</h2>
            <?php if (empty($failing_students)): ?>
                <div class="alert alert-success">Great! All students are performing well.</div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <strong><?php echo count($failing_students); ?> student(s)</strong> need additional support.
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Roll No.</th>
                            <th>Class</th>
                            <th>Average</th>
                            <th>Failed Subjects</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($failing_students as $student): ?>
                            <tr style="background-color: #ffeaa7;">
                                <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                <td><?php echo $student['roll_number']; ?></td>
                                <td><?php echo $student['class']; ?></td>
                                <td><strong style="color: red;"><?php echo number_format($student['average_marks'], 1); ?>%</strong></td>
                                <td><?php echo $student['failed_subjects']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Recent Activity Log -->
        <div class="widget">
            <h2>📋 Recent Database Activity</h2>
            <?php
            $recent_activities = $pdo->query("
                SELECT 'Grade Added' as activity_type, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       sub.subject_name,
                       g.marks_obtained as details,
                       g.created_at as activity_time
                FROM grades g
                INNER JOIN students s ON g.student_id = s.student_id
                INNER JOIN subjects sub ON g.subject_id = sub.subject_id
                ORDER BY g.created_at DESC
                LIMIT 10
            ")->fetchAll();
            ?>
            
            <?php if (empty($recent_activities)): ?>
                <p>No recent activity to display.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Details</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_activities as $activity): ?>
                            <tr>
                                <td><?php echo $activity['activity_type']; ?></td>
                                <td><?php echo $activity['student_name']; ?></td>
                                <td><?php echo $activity['subject_name']; ?></td>
                                <td><?php echo $activity['details']; ?>%</td>
                                <td><?php echo date('M j, Y H:i', strtotime($activity['activity_time'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto-refresh dashboard every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000);
        
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>
</html>
```

### Activity 2: Database Security Audit Tool (45 minutes)

#### Create file: `security-audit.php`
```php
<?php
// Database Security Audit Tool
class DatabaseSecurityAudit {
    private $pdo;
    private $audit_results = [];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function runFullAudit() {
        $this->checkTableStructure();
        $this->checkUserPrivileges();
        $this->checkPasswordSecurity();
        $this->analyzeSQLQueries();
        $this->checkDataIntegrity();
        
        return $this->audit_results;
    }
    
    private function checkTableStructure() {
        $this->audit_results['table_structure'] = [];
        
        // Check for tables without primary keys
        $tables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $columns = $this->pdo->query("DESCRIBE `$table`")->fetchAll();
            $has_primary_key = false;
            $has_indexes = false;
            
            foreach ($columns as $column) {
                if ($column['Key'] == 'PRI') $has_primary_key = true;
                if ($column['Key'] != '') $has_indexes = true;
            }
            
            $this->audit_results['table_structure'][$table] = [
                'has_primary_key' => $has_primary_key,
                'has_indexes' => $has_indexes,
                'column_count' => count($columns)
            ];
        }
    }
    
    private function checkPasswordSecurity() {
        $this->audit_results['password_security'] = [];
        
        try {
            // Check if users table exists and has proper password hashing
            $stmt = $this->pdo->query("
                SELECT user_id, username, password_hash, 
                       LENGTH(password_hash) as hash_length
                FROM users 
                LIMIT 10
            ");
            
            $users = $stmt->fetchAll();
            $weak_passwords = 0;
            
            foreach ($users as $user) {
                // Check if password hash looks like bcrypt (should be 60 characters)
                if ($user['hash_length'] < 50) {
                    $weak_passwords++;
                }
            }
            
            $this->audit_results['password_security'] = [
                'total_users' => count($users),
                'weak_password_count' => $weak_passwords,
                'security_score' => count($users) > 0 ? (100 - ($weak_passwords / count($users)) * 100) : 100
            ];
            
        } catch (Exception $e) {
            $this->audit_results['password_security']['error'] = "Users table not found or inaccessible";
        }
    }
    
    private function checkDataIntegrity() {
        $this->audit_results['data_integrity'] = [];
        
        // Check for orphaned records (foreign key violations)
        try {
            // Students without valid class references
            $orphaned_students = $this->pdo->query("
                SELECT COUNT(*) FROM students s 
                LEFT JOIN classes c ON s.class_id = c.class_id 
                WHERE s.class_id IS NOT NULL AND c.class_id IS NULL
            ")->fetchColumn();
            
            // Grades without valid student references
            $orphaned_grades = $this->pdo->query("
                SELECT COUNT(*) FROM grades g 
                LEFT JOIN students s ON g.student_id = s.student_id 
                WHERE s.student_id IS NULL
            ")->fetchColumn();
            
            $this->audit_results['data_integrity'] = [
                'orphaned_students' => $orphaned_students,
                'orphaned_grades' => $orphaned_grades,
                'integrity_score' => ($orphaned_students + $orphaned_grades) == 0 ? 100 : 75
            ];
            
        } catch (Exception $e) {
            $this->audit_results['data_integrity']['error'] = $e->getMessage();
        }
    }
    
    public function generateSecurityReport() {
        $results = $this->runFullAudit();
        
        echo "<h2>🔒 Database Security Audit Report</h2>";
        echo "<p><strong>Generated on:</strong> " . date('Y-m-d H:i:s') . "</p>";
        
        // Overall Security Score
        $scores = [];
        if (isset($results['password_security']['security_score'])) {
            $scores[] = $results['password_security']['security_score'];
        }
        if (isset($results['data_integrity']['integrity_score'])) {
            $scores[] = $results['data_integrity']['integrity_score'];
        }
        
        $overall_score = !empty($scores) ? array_sum($scores) / count($scores) : 0;
        
        echo "<div style='background: " . ($overall_score >= 80 ? 'linear-gradient(90deg, #4CAF50, #8BC34A)' : 
             ($overall_score >= 60 ? 'linear-gradient(90deg, #FF9800, #FFC107)' : 'linear-gradient(90deg, #F44336, #E91E63)')) . 
             "; color: white; padding: 20px; border-radius: 10px; text-align: center; margin: 20px 0;'>";
        echo "<h3>Overall Security Score: " . number_format($overall_score, 1) . "/100</h3>";
        echo "</div>";
        
        // Detailed Results
        foreach ($results as $category => $data) {
            echo "<div style='background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>";
            echo "<h3>" . ucwords(str_replace('_', ' ', $category)) . "</h3>";
            
            if (is_array($data)) {
                echo "<ul>";
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        echo "<li><strong>$key:</strong><ul>";
                        foreach ($value as $subkey => $subvalue) {
                            echo "<li>$subkey: " . (is_bool($subvalue) ? ($subvalue ? 'Yes' : 'No') : $subvalue) . "</li>";
                        }
                        echo "</ul></li>";
                    } else {
                        echo "<li><strong>$key:</strong> " . (is_bool($value) ? ($value ? 'Yes' : 'No') : $value) . "</li>";
                    }
                }
                echo "</ul>";
            }
            echo "</div>";
        }
    }
}

// Run the audit
try {
    $pdo = new PDO("mysql:host=localhost;dbname=school_management", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $audit = new DatabaseSecurityAudit($pdo);
    $audit->generateSecurityReport();
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;'>";
    echo "<strong>Audit Failed:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
```

---

## Exercises

### Exercise 1: Complex Query Builder (25 minutes)
Create `query-builder.php` that:
- Builds dynamic SELECT queries with multiple JOINs
- Implements advanced WHERE conditions with subqueries
- Includes GROUP BY and HAVING clauses
- Provides query execution timing and optimization hints

### Exercise 2: Database Migration System (30 minutes)
Build `migration-system.php` featuring:
- Schema version tracking
- Automated database updates
- Rollback functionality
- Data transformation during migrations

### Exercise 3: Performance Monitoring Tool (35 minutes)
Develop `performance-monitor.php` that:
- Tracks slow queries and execution times
- Monitors database connection usage
- Analyzes table sizes and index effectiveness
- Suggests optimization improvements

---

## Assessment

### Knowledge Check:
1. Explain the difference between INNER JOIN and LEFT JOIN with examples
2. How do you prevent SQL injection attacks?
3. What are database transactions and when should you use them?
4. How do you implement role-based access control?
5. What are the key elements of a secure authentication system?

### Practical Assessment:
- [ ] Written complex queries with multiple JOINs
- [ ] Implemented secure authentication system
- [ ] Used transactions for data consistency
- [ ] Applied proper input validation and sanitization
- [ ] Demonstrated understanding of database security principles

---

## Homework Assignment

### **Secure Multi-User School Management System**
Create a comprehensive secure system (`secure-school-system.php`) with:

#### **Security Implementation:**
1. **User Authentication:**
   - Secure login/logout with session management
   - Password strength requirements and hashing
   - Role-based access control (admin, teacher, student)
   - Failed login attempt monitoring

2. **Data Security:**
   - SQL injection prevention using prepared statements
   - Input validation and sanitization
   - CSRF protection simulation
   - Error handling without information disclosure

#### **Advanced Features:**
3. **Complex Analytics:**
   - Multi-table JOIN queries for comprehensive reports
   - Student performance trending over time
   - Teacher workload and effectiveness analysis
   - Class comparison and ranking systems

4. **Transaction Management:**
   - Atomic operations for grade batch updates
   - Student enrollment with rollback capability
   - Data consistency checks and integrity maintenance

**Due:** Next class session  
**Assessment:** Security implementation, query complexity, transaction handling, overall system design

---

## Next Lesson Preview
**Lesson 14: Web APIs and Modern PHP Development**
- RESTful API development with PHP
- JSON data handling and AJAX integration
- Modern PHP frameworks introduction
- API security and authentication tokens

---

*Advanced database operations and security form the backbone of professional web applications. Master these concepts to build enterprise-level systems.*
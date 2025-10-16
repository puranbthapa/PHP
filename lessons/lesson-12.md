# Lesson 12: Database Basics & MySQL Integration
**Course:** PHP Web Development - Class XII  
**Duration:** 3 hours  
**Prerequisites:** Lessons 1-11 completed, understanding of arrays and form handling

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Understand database concepts and relational database management systems
2. Set up and configure MySQL database using phpMyAdmin
3. Connect PHP applications to MySQL databases
4. Perform basic CRUD operations (Create, Read, Update, Delete)
5. Design simple database schemas for web applications
6. Implement proper error handling for database operations

---

## Key Concepts

### 1. Database Fundamentals

#### What is a Database?
- **Database:** Organized collection of structured information or data
- **DBMS:** Database Management System (MySQL, PostgreSQL, Oracle)
- **Table:** Collection of related data entries consisting of columns and rows
- **Record/Row:** Individual entry in a table
- **Field/Column:** Specific data category in a table
- **Primary Key:** Unique identifier for each record
- **Foreign Key:** Field that links to primary key of another table

#### Why Use Databases?
- **Data Persistence:** Information survives application restarts
- **Data Integrity:** Enforced rules and constraints
- **Concurrent Access:** Multiple users can access simultaneously
- **Security:** User authentication and authorization
- **Scalability:** Handle large amounts of data efficiently
- **Backup and Recovery:** Data protection mechanisms

### 2. MySQL Database Setup

#### Using phpMyAdmin:
```sql
-- Access phpMyAdmin at http://localhost/phpmyadmin
-- Create a new database
CREATE DATABASE school_management;
USE school_management;

-- Create students table
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    roll_number VARCHAR(20) UNIQUE NOT NULL,
    class VARCHAR(10) NOT NULL,
    date_of_birth DATE,
    phone VARCHAR(15),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create subjects table
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    subject_code VARCHAR(10) UNIQUE NOT NULL,
    credits INT DEFAULT 1
);

-- Create grades table
CREATE TABLE grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    marks_obtained INT NOT NULL,
    total_marks INT NOT NULL DEFAULT 100,
    exam_date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
);
```

### 3. PHP Database Connection

#### MySQLi Connection:
```php
<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "school_management";

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully to database: " . $database;
?>
```

#### PDO Connection (Recommended):
```php
<?php
try {
    $host = 'localhost';
    $dbname = 'school_management';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    echo "Connected successfully using PDO";
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

### 4. CRUD Operations

#### Create (INSERT):
```php
<?php
// Insert new student
$sql = "INSERT INTO students (first_name, last_name, email, roll_number, class, date_of_birth, phone, address) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'John',
    'Doe', 
    'john.doe@email.com',
    '2025001',
    'XII-A',
    '2007-05-15',
    '9876543210',
    '123 Main Street, City'
]);

echo "Student added successfully. ID: " . $pdo->lastInsertId();
?>
```

#### Read (SELECT):
```php
<?php
// Select all students
$sql = "SELECT * FROM students ORDER BY first_name ASC";
$stmt = $pdo->query($sql);
$students = $stmt->fetchAll();

foreach ($students as $student) {
    echo "Name: " . $student['first_name'] . " " . $student['last_name'] . "<br>";
    echo "Email: " . $student['email'] . "<br>";
    echo "Roll: " . $student['roll_number'] . "<br><br>";
}

// Select specific student
$student_id = 1;
$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if ($student) {
    echo "Found: " . $student['first_name'] . " " . $student['last_name'];
} else {
    echo "Student not found";
}
?>
```

#### Update (UPDATE):
```php
<?php
// Update student information
$sql = "UPDATE students SET phone = ?, address = ? WHERE student_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(['9876543211', '456 New Address', 1]);

echo "Affected rows: " . $stmt->rowCount();
?>
```

#### Delete (DELETE):
```php
<?php
// Delete student (use with caution)
$sql = "DELETE FROM students WHERE student_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([1]);

echo "Deleted rows: " . $stmt->rowCount();
?>
```

---

## Practical Activities

### Activity 1: Complete Student Management System (60 minutes)

#### Create file: `student-database-system.php`
```php
<?php
// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=school_management", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submissions
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_student':
            try {
                $sql = "INSERT INTO students (first_name, last_name, email, roll_number, class, date_of_birth, phone, address) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $_POST['first_name'],
                    $_POST['last_name'],
                    $_POST['email'],
                    $_POST['roll_number'],
                    $_POST['class'],
                    $_POST['date_of_birth'],
                    $_POST['phone'],
                    $_POST['address']
                ]);
                $message = "Student added successfully! ID: " . $pdo->lastInsertId();
            } catch (PDOException $e) {
                $error = "Error adding student: " . $e->getMessage();
            }
            break;
            
        case 'update_student':
            try {
                $sql = "UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? 
                        WHERE student_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $_POST['first_name'],
                    $_POST['last_name'],
                    $_POST['email'],
                    $_POST['phone'],
                    $_POST['address'],
                    $_POST['student_id']
                ]);
                $message = "Student updated successfully! Affected rows: " . $stmt->rowCount();
            } catch (PDOException $e) {
                $error = "Error updating student: " . $e->getMessage();
            }
            break;
            
        case 'delete_student':
            try {
                $sql = "DELETE FROM students WHERE student_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_POST['student_id']]);
                $message = "Student deleted successfully! Deleted rows: " . $stmt->rowCount();
            } catch (PDOException $e) {
                $error = "Error deleting student: " . $e->getMessage();
            }
            break;
    }
}

// Get all students for display
$search = $_GET['search'] ?? '';
$class_filter = $_GET['class'] ?? '';

$sql = "SELECT * FROM students WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR roll_number LIKE ? OR email LIKE ?)";
    $search_term = "%$search%";
    $params = array_fill(0, 4, $search_term);
}

if (!empty($class_filter)) {
    $sql .= " AND class = ?";
    $params[] = $class_filter;
}

$sql .= " ORDER BY first_name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

// Get unique classes for filter
$class_stmt = $pdo->query("SELECT DISTINCT class FROM students ORDER BY class");
$classes = $class_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Database Management System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; background-color: #f5f5f5; }
        .container { background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .form-section { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-success { background-color: #28a745; }
        .btn-success:hover { background-color: #218838; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .message { padding: 15px; border-radius: 4px; margin: 20px 0; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .search-section { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: white; margin: 5% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 600px; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: black; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗃️ Student Database Management System</h1>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Add Student Form -->
        <div class="form-section">
            <h2>➕ Add New Student</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_student">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="roll_number">Roll Number:</label>
                        <input type="text" id="roll_number" name="roll_number" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="class">Class:</label>
                        <select id="class" name="class" required>
                            <option value="">Select Class</option>
                            <option value="XI-A">XI-A</option>
                            <option value="XI-B">XI-B</option>
                            <option value="XII-A">XII-A</option>
                            <option value="XII-B">XII-B</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn-success">Add Student</button>
            </form>
        </div>
        
        <!-- Search and Filter Section -->
        <div class="search-section">
            <h3>🔍 Search & Filter Students</h3>
            <form method="GET" action="">
                <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 15px; align-items: end;">
                    <div>
                        <label for="search">Search (Name, Roll, Email):</label>
                        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Enter search term">
                    </div>
                    <div>
                        <label for="class_filter">Filter by Class:</label>
                        <select id="class_filter" name="class">
                            <option value="">All Classes</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo $class; ?>" <?php echo ($class_filter == $class) ? 'selected' : ''; ?>>
                                    <?php echo $class; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Students List -->
        <h2>👥 Students List (<?php echo count($students); ?> students found)</h2>
        
        <?php if (empty($students)): ?>
            <p>No students found matching your criteria.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Roll Number</th>
                        <th>Class</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo $student['student_id']; ?></td>
                            <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['roll_number']); ?></td>
                            <td><?php echo htmlspecialchars($student['class']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['phone']); ?></td>
                            <td><?php echo $student['date_of_birth']; ?></td>
                            <td>
                                <button onclick="editStudent(<?php echo htmlspecialchars(json_encode($student)); ?>)" class="btn-success">Edit</button>
                                <button onclick="deleteStudent(<?php echo $student['student_id']; ?>, '<?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>')" class="btn-danger">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <!-- Database Statistics -->
        <div class="form-section">
            <h2>📊 Database Statistics</h2>
            <?php
            // Get statistics
            $total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
            $classes_count = $pdo->query("SELECT COUNT(DISTINCT class) FROM students")->fetchColumn();
            
            // Class distribution
            $class_distribution = $pdo->query("
                SELECT class, COUNT(*) as student_count 
                FROM students 
                GROUP BY class 
                ORDER BY class
            ")->fetchAll();
            
            echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;'>";
            echo "<div style='background-color: #e3f2fd; padding: 20px; border-radius: 8px; text-align: center;'>";
            echo "<h3>$total_students</h3><p>Total Students</p></div>";
            echo "<div style='background-color: #e8f5e8; padding: 20px; border-radius: 8px; text-align: center;'>";
            echo "<h3>$classes_count</h3><p>Active Classes</p></div>";
            echo "</div>";
            
            if (!empty($class_distribution)) {
                echo "<h4>Class Distribution:</h4>";
                echo "<ul>";
                foreach ($class_distribution as $class_info) {
                    echo "<li><strong>{$class_info['class']}:</strong> {$class_info['student_count']} students</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>✏️ Edit Student</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_student">
                <input type="hidden" id="edit_student_id" name="student_id">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="edit_first_name">First Name:</label>
                        <input type="text" id="edit_first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_last_name">Last Name:</label>
                        <input type="text" id="edit_last_name" name="last_name" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Email:</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_phone">Phone:</label>
                    <input type="tel" id="edit_phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="edit_address">Address:</label>
                    <textarea id="edit_address" name="address" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn-success">Update Student</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>
    
    <script>
        function editStudent(student) {
            document.getElementById('edit_student_id').value = student.student_id;
            document.getElementById('edit_first_name').value = student.first_name;
            document.getElementById('edit_last_name').value = student.last_name;
            document.getElementById('edit_email').value = student.email;
            document.getElementById('edit_phone').value = student.phone || '';
            document.getElementById('edit_address').value = student.address || '';
            document.getElementById('editModal').style.display = 'block';
        }
        
        function deleteStudent(id, name) {
            if (confirm('Are you sure you want to delete student "' + name + '"? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_student">
                    <input type="hidden" name="student_id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
```

### Activity 2: Database Design and Relationships (30 minutes)

#### Create file: `database-design.php`
```php
<?php
// Extended database schema with relationships
$sql_commands = [
    // Create subjects table
    "CREATE TABLE IF NOT EXISTS subjects (
        subject_id INT AUTO_INCREMENT PRIMARY KEY,
        subject_name VARCHAR(100) NOT NULL,
        subject_code VARCHAR(10) UNIQUE NOT NULL,
        credits INT DEFAULT 1,
        description TEXT
    )",
    
    // Create teachers table
    "CREATE TABLE IF NOT EXISTS teachers (
        teacher_id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(15),
        department VARCHAR(50),
        hire_date DATE,
        salary DECIMAL(10,2)
    )",
    
    // Create classes table
    "CREATE TABLE IF NOT EXISTS classes (
        class_id INT AUTO_INCREMENT PRIMARY KEY,
        class_name VARCHAR(20) NOT NULL,
        class_teacher_id INT,
        room_number VARCHAR(10),
        max_students INT DEFAULT 40,
        FOREIGN KEY (class_teacher_id) REFERENCES teachers(teacher_id)
    )",
    
    // Update students table to reference classes
    "ALTER TABLE students 
     ADD COLUMN class_id INT,
     ADD FOREIGN KEY (class_id) REFERENCES classes(class_id)",
    
    // Create grades table with relationships
    "CREATE TABLE IF NOT EXISTS grades (
        grade_id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT,
        subject_id INT,
        teacher_id INT,
        marks_obtained DECIMAL(5,2) NOT NULL,
        total_marks DECIMAL(5,2) NOT NULL DEFAULT 100.00,
        exam_type ENUM('assignment', 'quiz', 'midterm', 'final') DEFAULT 'assignment',
        exam_date DATE NOT NULL,
        remarks TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
        FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
        FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id)
    )",
    
    // Create attendance table
    "CREATE TABLE IF NOT EXISTS attendance (
        attendance_id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT,
        subject_id INT,
        teacher_id INT,
        attendance_date DATE NOT NULL,
        status ENUM('present', 'absent', 'late') DEFAULT 'present',
        remarks TEXT,
        marked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
        FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
        FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id)
    )"
];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=school_management", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Schema Creation</h2>";
    
    foreach ($sql_commands as $index => $sql) {
        try {
            $pdo->exec($sql);
            echo "<p>✅ Command " . ($index + 1) . " executed successfully</p>";
        } catch (PDOException $e) {
            echo "<p>⚠️ Command " . ($index + 1) . " warning: " . $e->getMessage() . "</p>";
        }
    }
    
    // Insert sample data
    $sample_data = [
        // Insert subjects
        "INSERT IGNORE INTO subjects (subject_name, subject_code, credits, description) VALUES
        ('Mathematics', 'MATH101', 4, 'Advanced mathematics for grade XII'),
        ('Physics', 'PHY101', 4, 'Classical and modern physics concepts'),
        ('Chemistry', 'CHEM101', 4, 'Organic and inorganic chemistry'),
        ('Computer Science', 'CS101', 4, 'Programming and computer fundamentals'),
        ('English', 'ENG101', 3, 'English literature and language skills')",
        
        // Insert teachers
        "INSERT IGNORE INTO teachers (first_name, last_name, email, phone, department, hire_date, salary) VALUES
        ('Dr. Rajesh', 'Sharma', 'rajesh.sharma@school.edu', '9876543201', 'Mathematics', '2020-06-15', 75000.00),
        ('Ms. Priya', 'Singh', 'priya.singh@school.edu', '9876543202', 'Physics', '2019-08-20', 68000.00),
        ('Mr. Amit', 'Kumar', 'amit.kumar@school.edu', '9876543203', 'Chemistry', '2021-01-10', 70000.00),
        ('Mrs. Sunita', 'Patel', 'sunita.patel@school.edu', '9876543204', 'Computer Science', '2018-07-25', 80000.00),
        ('Dr. Kavita', 'Gupta', 'kavita.gupta@school.edu', '9876543205', 'English', '2020-03-12', 65000.00)",
        
        // Insert classes
        "INSERT IGNORE INTO classes (class_name, class_teacher_id, room_number, max_students) VALUES
        ('XII-A', 1, 'A-101', 35),
        ('XII-B', 2, 'A-102', 35),
        ('XI-A', 3, 'B-101', 40),
        ('XI-B', 4, 'B-102', 40)"
    ];
    
    echo "<h3>Sample Data Insertion</h3>";
    foreach ($sample_data as $index => $sql) {
        try {
            $pdo->exec($sql);
            echo "<p>✅ Sample data set " . ($index + 1) . " inserted successfully</p>";
        } catch (PDOException $e) {
            echo "<p>⚠️ Sample data " . ($index + 1) . " warning: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3>Database Tables Created Successfully!</h3>";
    
    // Display table structure
    $tables = ['students', 'subjects', 'teachers', 'classes', 'grades', 'attendance'];
    
    foreach ($tables as $table) {
        echo "<h4>Table: $table</h4>";
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>{$column['Field']}</td>";
            echo "<td>{$column['Type']}</td>";
            echo "<td>{$column['Null']}</td>";
            echo "<td>{$column['Key']}</td>";
            echo "<td>{$column['Default']}</td>";
            echo "<td>{$column['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

---

## Exercises

### Exercise 1: Basic CRUD Operations (20 minutes)
Create `book-library.php` that:
- Creates a books table with title, author, ISBN, publication year
- Implements add, view, update, and delete functionality
- Includes search by title or author
- Displays total book count and statistics

### Exercise 2: Student Grades System (25 minutes)
Build `grades-management.php` featuring:
- Students and subjects tables with relationships
- Grade entry and calculation system
- Report card generation for individual students
- Class performance analytics

### Exercise 3: Inventory Tracking (30 minutes)
Develop `inventory-system.php` that:
- Manages product inventory with categories
- Tracks stock levels, prices, and suppliers
- Implements low-stock alerts
- Generates inventory reports

### Exercise 4: Employee Management (35 minutes)
Create `employee-database.php` with:
- Employee records with departments and positions
- Salary management and payroll calculations
- Attendance tracking integration
- Employee performance metrics

---

## Database Security Best Practices

### 1. Prepared Statements
```php
// Always use prepared statements to prevent SQL injection
$sql = "SELECT * FROM students WHERE email = ? AND class = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email, $class]);

// Never concatenate user input directly
// BAD: $sql = "SELECT * FROM users WHERE id = " . $_GET['id'];
// GOOD: Use prepared statements as shown above
```

### 2. Input Validation
```php
// Validate and sanitize all inputs
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateRollNumber($roll) {
    return preg_match('/^[0-9]{7}$/', $roll);
}

// Check data before database operations
if (!validateEmail($_POST['email'])) {
    throw new Exception("Invalid email format");
}
```

### 3. Error Handling
```php
// Don't expose database errors to users
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} catch (PDOException $e) {
    // Log error for developers
    error_log("Database error: " . $e->getMessage());
    
    // Show generic message to users
    $error_message = "Sorry, there was a problem processing your request.";
}
```

---

## Assessment

### Knowledge Check:
1. What is the difference between MySQLi and PDO?
2. Why should you use prepared statements?
3. What is a foreign key and why is it important?
4. How do you handle database connection errors?
5. What are the four basic CRUD operations?

### Practical Assessment:
- [ ] Successfully connected PHP to MySQL database
- [ ] Created proper database schema with relationships
- [ ] Implemented all CRUD operations securely
- [ ] Used prepared statements consistently
- [ ] Applied proper error handling
- [ ] Demonstrated understanding of database design principles

---

## Homework Assignment

### **Complete School Management Database System**
Create a comprehensive database-driven application (`school-system.php`) with:

#### **Database Design:**
1. **Tables Required:**
   - students (extended with more fields)
   - teachers (with qualifications and experience)
   - subjects (with prerequisites and difficulty levels)
   - classes (with schedules and capacity)
   - enrollments (student-subject relationships)
   - grades (with weighted scoring system)
   - attendance (detailed tracking)

#### **Functionality:**
2. **Student Management:**
   - Complete registration and profile management
   - Academic history and transcript generation
   - Photo upload and document management

3. **Academic Operations:**
   - Course enrollment and withdrawal
   - Grade entry and calculation
   - Attendance marking and reporting
   - Progress tracking and alerts

4. **Reporting System:**
   - Individual student reports
   - Class performance analytics
   - Teacher workload analysis
   - Administrative summaries

#### **Advanced Features:**
- Data export functionality (CSV/PDF simulation)
- Advanced search and filtering
- Dashboard with key metrics
- User authentication simulation
- Backup and restore functionality

**Due:** Next class session  
**Assessment:** Database design, security implementation, functionality, user experience

---

## Next Lesson Preview
**Lesson 13: Advanced Database Operations**
- Complex queries with JOINs
- Data aggregation and analysis
- Advanced security measures
- Performance optimization techniques
- Building complete data-driven applications

---

*Database integration transforms static websites into dynamic, data-driven applications. Master these fundamentals to build powerful web systems.*
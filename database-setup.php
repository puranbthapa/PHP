<?php
/**
 * Database Setup and Demo
 * Creates database tables and demonstrates basic CRUD operations
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

// Database configuration
$config = [
    'host' => 'localhost',
    'dbname' => 'school_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];

try {
    // Connect to MySQL server (without database first)
    $dsn = "mysql:host={$config['host']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "<h1>Database Setup Demo</h1>";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['dbname']} CHARACTER SET {$config['charset']} COLLATE utf8mb4_unicode_ci");
    echo "<p>✓ Database '{$config['dbname']}' created or already exists</p>";
    
    // Connect to the specific database
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    // Create students table
    $studentsTable = "
    CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        age INT NOT NULL,
        grade VARCHAR(10) DEFAULT 'XII',
        total_points INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL DEFAULT NULL,
        INDEX idx_email (email),
        INDEX idx_grade (grade),
        INDEX idx_deleted (deleted_at)
    )";
    
    $pdo->exec($studentsTable);
    echo "<p>✓ Students table created</p>";
    
    // Create grades table
    $gradesTable = "
    CREATE TABLE IF NOT EXISTS grades (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        subject VARCHAR(50) NOT NULL,
        score DECIMAL(5,2) NOT NULL,
        max_score DECIMAL(5,2) DEFAULT 100.00,
        grade_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        INDEX idx_student (student_id),
        INDEX idx_subject (subject),
        INDEX idx_grade_date (grade_date)
    )";
    
    $pdo->exec($gradesTable);
    echo "<p>✓ Grades table created</p>";
    
    // Create point transfers table for transaction demo
    $transfersTable = "
    CREATE TABLE IF NOT EXISTS point_transfers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        from_student_id INT NOT NULL,
        to_student_id INT NOT NULL,
        points INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (from_student_id) REFERENCES students(id),
        FOREIGN KEY (to_student_id) REFERENCES students(id),
        INDEX idx_from_student (from_student_id),
        INDEX idx_to_student (to_student_id)
    )";
    
    $pdo->exec($transfersTable);
    echo "<p>✓ Point transfers table created</p>";
    
    // Insert sample data
    echo "<h2>Sample Data</h2>";
    
    // Check if we already have data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM students");
    $count = $stmt->fetch()['count'];
    
    if ($count == 0) {
        $sampleStudents = [
            ['Alice Johnson', 'alice@example.com', 17, 'XII-A', 95],
            ['Bob Smith', 'bob@example.com', 18, 'XII-B', 87],
            ['Charlie Brown', 'charlie@example.com', 17, 'XII-A', 92],
            ['Diana Ross', 'diana@example.com', 18, 'XII-C', 88],
            ['Eve Wilson', 'eve@example.com', 17, 'XII-B', 94]
        ];
        
        $insertStmt = $pdo->prepare("INSERT INTO students (name, email, age, grade, total_points) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($sampleStudents as $student) {
            $insertStmt->execute($student);
        }
        
        echo "<p>✓ Sample students inserted</p>";
        
        // Insert sample grades
        $sampleGrades = [
            [1, 'Mathematics', 95, 100, '2024-10-01'],
            [1, 'Physics', 92, 100, '2024-10-05'],
            [2, 'Mathematics', 87, 100, '2024-10-01'],
            [2, 'Physics', 89, 100, '2024-10-05'],
            [3, 'Mathematics', 90, 100, '2024-10-01'],
            [3, 'Chemistry', 94, 100, '2024-10-03']
        ];
        
        $gradeStmt = $pdo->prepare("INSERT INTO grades (student_id, subject, score, max_score, grade_date) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($sampleGrades as $grade) {
            $gradeStmt->execute($grade);
        }
        
        echo "<p>✓ Sample grades inserted</p>";
    } else {
        echo "<p>Sample data already exists ($count students)</p>";
    }
    
    // Demonstrate basic queries
    echo "<h2>Query Examples</h2>";
    
    // Simple select
    $stmt = $pdo->query("SELECT name, email, grade, total_points FROM students ORDER BY name");
    $students = $stmt->fetchAll();
    
    echo "<h3>All Students</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Name</th><th>Email</th><th>Grade</th><th>Points</th></tr>";
    foreach ($students as $student) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($student['name']) . "</td>";
        echo "<td>" . htmlspecialchars($student['email']) . "</td>";
        echo "<td>" . htmlspecialchars($student['grade']) . "</td>";
        echo "<td>" . htmlspecialchars($student['total_points']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Join query
    $stmt = $pdo->query("
        SELECT s.name, g.subject, g.score, g.max_score, 
               ROUND((g.score/g.max_score)*100, 1) as percentage
        FROM students s 
        JOIN grades g ON s.id = g.student_id 
        ORDER BY s.name, g.grade_date
    ");
    $grades = $stmt->fetchAll();
    
    echo "<h3>Student Grades</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Student</th><th>Subject</th><th>Score</th><th>Max</th><th>Percentage</th></tr>";
    foreach ($grades as $grade) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($grade['name']) . "</td>";
        echo "<td>" . htmlspecialchars($grade['subject']) . "</td>";
        echo "<td>" . htmlspecialchars($grade['score']) . "</td>";
        echo "<td>" . htmlspecialchars($grade['max_score']) . "</td>";
        echo "<td>" . htmlspecialchars($grade['percentage']) . "%</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Statistics
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total_students,
            AVG(age) as avg_age,
            AVG(total_points) as avg_points,
            MAX(total_points) as max_points
        FROM students 
        WHERE deleted_at IS NULL
    ");
    $stats = $stmt->fetch();
    
    echo "<h3>Statistics</h3>";
    echo "<ul>";
    echo "<li>Total Students: " . $stats['total_students'] . "</li>";
    echo "<li>Average Age: " . round($stats['avg_age'], 1) . " years</li>";
    echo "<li>Average Points: " . round($stats['avg_points'], 1) . "</li>";
    echo "<li>Maximum Points: " . $stats['max_points'] . "</li>";
    echo "</ul>";
    
    echo "<h2>Setup Complete!</h2>";
    echo "<p>Database and tables are ready. You can now:</p>";
    echo "<ul>";
    echo "<li>Test CRUD operations with <a href='student-manager-demo.php'>Student Manager Demo</a></li>";
    echo "<li>Try prepared statements with <a href='prepared-statements-demo.php'>Prepared Statements Demo</a></li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
    echo "<strong>Database Error:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ol>";
    echo "<li>Make sure XAMPP MySQL service is running</li>";
    echo "<li>Check database credentials in the configuration</li>";
    echo "<li>Ensure MySQL is accessible on localhost:3306</li>";
    echo "</ol>";
}
?>
<?php
/**
 * Prepared Statements Demo
 * Demonstrates safe vs unsafe database queries and SQL injection prevention
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
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "<h1>Prepared Statements vs SQL Injection Demo</h1>";
    
    // Sample malicious input
    $maliciousInput = "'; DROP TABLE students; --";
    $normalInput = "Alice";
    
    echo "<h2>1. Unsafe Query (DON'T DO THIS!)</h2>";
    echo "<p style='color: red;'><strong>Warning:</strong> This example shows what NOT to do. Never use direct string concatenation with user input!</p>";
    
    // Demonstrate unsafe query (commented out for safety)
    $unsafeQuery = "SELECT * FROM students WHERE name = '$maliciousInput'";
    echo "<strong>Unsafe Query:</strong> <code>" . htmlspecialchars($unsafeQuery) . "</code><br>";
    echo "<p>If this query were executed, it could potentially drop your entire students table!</p>";
    
    echo "<h2>2. Safe Query with Prepared Statements</h2>";
    
    // Safe prepared statement
    $safeQuery = "SELECT * FROM students WHERE name = ?";
    $stmt = $pdo->prepare($safeQuery);
    $stmt->execute([$maliciousInput]);
    $results = $stmt->fetchAll();
    
    echo "<strong>Safe Query:</strong> <code>" . htmlspecialchars($safeQuery) . "</code><br>";
    echo "<strong>Parameter:</strong> <code>" . htmlspecialchars($maliciousInput) . "</code><br>";
    echo "<strong>Results:</strong> " . count($results) . " rows found<br>";
    echo "<p style='color: green;'>✓ The malicious input is treated as literal text and cannot harm the database.</p>";
    
    echo "<h2>3. Named Parameters Demo</h2>";
    
    // Named parameters example
    $stmt = $pdo->prepare("SELECT * FROM students WHERE grade = :grade AND age >= :min_age");
    $stmt->execute([
        ':grade' => 'XII-A',
        ':min_age' => 17
    ]);
    $results = $stmt->fetchAll();
    
    echo "<strong>Query:</strong> <code>SELECT * FROM students WHERE grade = :grade AND age >= :min_age</code><br>";
    echo "<strong>Parameters:</strong> grade = 'XII-A', min_age = 17<br>";
    echo "<strong>Results:</strong> " . count($results) . " students found<br><br>";
    
    if (!empty($results)) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Name</th><th>Grade</th><th>Age</th><th>Points</th></tr>";
        foreach ($results as $student) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($student['name']) . "</td>";
            echo "<td>" . htmlspecialchars($student['grade']) . "</td>";
            echo "<td>" . $student['age'] . "</td>";
            echo "<td>" . $student['total_points'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
    
    echo "<h2>4. Transaction Demo</h2>";
    
    // Transaction example - transfer points between students
    function transferPoints($pdo, $fromId, $toId, $points) {
        try {
            $pdo->beginTransaction();
            
            // Check if source student has enough points
            $checkStmt = $pdo->prepare("SELECT total_points FROM students WHERE id = ?");
            $checkStmt->execute([$fromId]);
            $fromStudent = $checkStmt->fetch();
            
            if (!$fromStudent || $fromStudent['total_points'] < $points) {
                throw new Exception("Insufficient points for transfer");
            }
            
            // Deduct points from source
            $deductStmt = $pdo->prepare("UPDATE students SET total_points = total_points - ? WHERE id = ?");
            $deductStmt->execute([$points, $fromId]);
            
            // Add points to destination
            $addStmt = $pdo->prepare("UPDATE students SET total_points = total_points + ? WHERE id = ?");
            $addStmt->execute([$points, $toId]);
            
            // Log the transfer
            $logStmt = $pdo->prepare("INSERT INTO point_transfers (from_student_id, to_student_id, points) VALUES (?, ?, ?)");
            $logStmt->execute([$fromId, $toId, $points]);
            
            $pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $pdo->rollback();
            throw $e;
        }
    }
    
    // Find two students for demo
    $stmt = $pdo->prepare("SELECT id, name, total_points FROM students WHERE deleted_at IS NULL ORDER BY total_points DESC LIMIT 2");
    $stmt->execute();
    $students = $stmt->fetchAll();
    
    if (count($students) >= 2) {
        $student1 = $students[0];
        $student2 = $students[1];
        
        echo "<p><strong>Before transfer:</strong></p>";
        echo "<ul>";
        echo "<li>{$student1['name']}: {$student1['total_points']} points</li>";
        echo "<li>{$student2['name']}: {$student2['total_points']} points</li>";
        echo "</ul>";
        
        try {
            // Only transfer if student1 has more than 10 points
            if ($student1['total_points'] > 10) {
                transferPoints($pdo, $student1['id'], $student2['id'], 5);
                
                // Get updated balances
                $stmt = $pdo->prepare("SELECT name, total_points FROM students WHERE id IN (?, ?)");
                $stmt->execute([$student1['id'], $student2['id']]);
                $updated = $stmt->fetchAll();
                
                echo "<p><strong>After transfer (5 points):</strong></p>";
                echo "<ul>";
                foreach ($updated as $student) {
                    echo "<li>{$student['name']}: {$student['total_points']} points</li>";
                }
                echo "</ul>";
                echo "<p style='color: green;'>✓ Transaction completed successfully</p>";
            } else {
                echo "<p>Demo transfer skipped - insufficient points</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Transaction failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    echo "<h2>5. Performance Comparison</h2>";
    
    // Compare prepared vs unprepared statements
    $iterations = 100;
    
    // Prepared statement (reused)
    $start = microtime(true);
    $preparedStmt = $pdo->prepare("SELECT COUNT(*) as count FROM students WHERE age = ?");
    for ($i = 0; $i < $iterations; $i++) {
        $preparedStmt->execute([17 + ($i % 3)]);
    }
    $preparedTime = microtime(true) - $start;
    
    // Query each time (not recommended for repeated queries)
    $start = microtime(true);
    for ($i = 0; $i < $iterations; $i++) {
        $age = 17 + ($i % 3);
        $pdo->query("SELECT COUNT(*) as count FROM students WHERE age = $age");
    }
    $queryTime = microtime(true) - $start;
    
    echo "<p><strong>Performance Test ($iterations iterations):</strong></p>";
    echo "<ul>";
    echo "<li>Prepared Statement (reused): " . round($preparedTime * 1000, 2) . "ms</li>";
    echo "<li>Direct Query: " . round($queryTime * 1000, 2) . "ms</li>";
    echo "<li>Improvement: " . round(($queryTime - $preparedTime) / $queryTime * 100, 1) . "%</li>";
    echo "</ul>";
    
    echo "<h2>6. Error Handling Best Practices</h2>";
    
    try {
        // Intentional error - invalid column name
        $stmt = $pdo->prepare("SELECT nonexistent_column FROM students LIMIT 1");
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<p><strong>Caught PDO Exception:</strong></p>";
        echo "<ul>";
        echo "<li>Error Code: " . $e->getCode() . "</li>";
        echo "<li>Error Message: " . htmlspecialchars($e->getMessage()) . "</li>";
        echo "<li>File: " . $e->getFile() . " (Line " . $e->getLine() . ")</li>";
        echo "</ul>";
        echo "<p style='color: orange;'>In production, log this error and show a user-friendly message instead.</p>";
    }
    
    echo "<h2>Key Takeaways</h2>";
    echo "<ol>";
    echo "<li><strong>Always use prepared statements</strong> for dynamic queries</li>";
    echo "<li><strong>Never concatenate user input</strong> directly into SQL queries</li>";
    echo "<li><strong>Use transactions</strong> for operations that must succeed or fail together</li>";
    echo "<li><strong>Handle errors gracefully</strong> and log them for debugging</li>";
    echo "<li><strong>Reuse prepared statements</strong> for better performance</li>";
    echo "<li><strong>Use named parameters</strong> for complex queries with multiple parameters</li>";
    echo "</ol>";
    
} catch (PDOException $e) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
    echo "<strong>Database Connection Error:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
    echo "<p>Make sure to run <a href='database-setup.php'>Database Setup</a> first.</p>";
}
?>

<p><a href="student-manager-demo.php">← Student Manager Demo</a> | <a href="database-setup.php">Database Setup</a></p>
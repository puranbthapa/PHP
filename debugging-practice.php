<?php
/**
 * Debugging Practice
 * Contains intentional bugs for students to find and fix
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debugging Practice - Find and Fix the Bugs!</h1>";

// Debug helper function
function debugVar($label, $var) {
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0; border-left: 4px solid #007cba;'>";
    echo "<strong>$label:</strong><br>";
    echo "<pre>" . print_r($var, true) . "</pre>";
    echo "</div>";
}

// Bug #1: Undefined variable
echo "<h2>Bug #1: Variable Issues</h2>";
$name = "Alice";
// echo "Hello, $nme!"; // Typo in variable name - UNCOMMENT TO SEE BUG
echo "Hello, $name! (Bug #1 fixed)<br>";

// Bug #2: Array key issues
echo "<h2>Bug #2: Array Access</h2>";
$student = ['name' => 'Bob', 'age' => 17, 'grade' => 'A'];
// echo $student['scores']; // Key doesn't exist - UNCOMMENT TO SEE BUG
echo "Student: " . $student['name'] . ", Age: " . ($student['age'] ?? 'Unknown') . " (Bug #2 fixed)<br>";

// Bug #3: Division by zero
echo "<h2>Bug #3: Mathematical Errors</h2>";
function calculateAverage($scores) {
    if (empty($scores)) {
        return 0; // Handle empty array
    }
    return array_sum($scores) / count($scores);
}

$testScores1 = [85, 92, 78, 88];
$testScores2 = []; // Empty array

echo "Average of test scores 1: " . calculateAverage($testScores1) . "<br>";
echo "Average of test scores 2: " . calculateAverage($testScores2) . " (Bug #3 fixed)<br>";

// Bug #4: File operations without error checking
echo "<h2>Bug #4: File Operations</h2>";
function safeFileRead($filename) {
    if (!file_exists($filename)) {
        return "File not found: $filename";
    }
    
    $content = file_get_contents($filename);
    if ($content === false) {
        return "Error reading file: $filename";
    }
    
    return $content;
}

$result = safeFileRead('nonexistent.txt');
echo "File read result: " . htmlspecialchars($result) . " (Bug #4 fixed)<br>";

// Bug #5: Infinite loop (commented out for safety)
echo "<h2>Bug #5: Infinite Loop (Prevented)</h2>";
echo "Original buggy code (commented out):<br>";
echo "<code>
\$i = 0;<br>
while (\$i &lt; 10) {<br>
&nbsp;&nbsp;echo \$i;<br>
&nbsp;&nbsp;// Missing: \$i++; // This would cause infinite loop<br>
}
</code><br>";

// Fixed version:
echo "Fixed version:<br>";
$i = 0;
while ($i < 10) {
    echo $i . " ";
    $i++; // Increment to prevent infinite loop
}
echo "(Bug #5 fixed)<br>";

// Bug #6: SQL Injection vulnerability (simulated)
echo "<h2>Bug #6: Security Issue (Simulated SQL)</h2>";

function simulateUnsafeQuery($username) {
    // This simulates what NOT to do
    $query = "SELECT * FROM users WHERE username = '$username'";
    return "UNSAFE: $query";
}

function simulateSafeQuery($username) {
    // This simulates the safe approach
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $query = "SELECT * FROM users WHERE username = ?";
    return "SAFE: $query with parameter: " . $username;
}

$userInput = "admin'; DROP TABLE users; --";
echo simulateUnsafeQuery($userInput) . "<br>";
echo simulateSafeQuery($userInput) . " (Bug #6 fixed)<br>";

// Bug #7: Memory and performance issues
echo "<h2>Bug #7: Performance Issue</h2>";

function inefficientFunction($n) {
    // Inefficient: recreating array each time
    $result = [];
    for ($i = 0; $i < $n; $i++) {
        $result[] = $i * 2;
    }
    return $result;
}

function efficientFunction($n) {
    // More efficient: pre-allocate or use generator for large datasets
    $result = array_fill(0, $n, 0);
    for ($i = 0; $i < $n; $i++) {
        $result[$i] = $i * 2;
    }
    return $result;
}

$start = microtime(true);
$result1 = inefficientFunction(1000);
$time1 = microtime(true) - $start;

$start = microtime(true);
$result2 = efficientFunction(1000);
$time2 = microtime(true) - $start;

echo "Inefficient time: " . round($time1 * 1000, 4) . "ms<br>";
echo "Efficient time: " . round($time2 * 1000, 4) . "ms (Bug #7 fixed)<br>";

// Debugging tools demonstration
echo "<h2>Debugging Tools Demo</h2>";

$debugData = [
    'user' => ['id' => 1, 'name' => 'Alice'],
    'scores' => [95, 87, 92],
    'metadata' => ['created' => time(), 'active' => true]
];

debugVar("Sample Data Structure", $debugData);

// Stack trace example
function level1() { level2(); }
function level2() { level3(); }
function level3() {
    echo "<strong>Call Stack:</strong><br>";
    $trace = debug_backtrace();
    foreach ($trace as $i => $frame) {
        $function = $frame['function'] ?? 'main';
        $file = basename($frame['file'] ?? 'unknown');
        $line = $frame['line'] ?? 'unknown';
        echo "  $i. $function() in $file:$line<br>";
    }
}

level1();

// Memory usage
echo "<br><strong>Memory Usage:</strong><br>";
echo "Current: " . round(memory_get_usage() / 1024, 2) . " KB<br>";
echo "Peak: " . round(memory_get_peak_usage() / 1024, 2) . " KB<br>";

echo "<h2>Practice Exercises</h2>";
echo "<ol>";
echo "<li>Uncomment the buggy lines (marked with comments) and observe the errors</li>";
echo "<li>Practice using var_dump() and print_r() on complex data structures</li>";
echo "<li>Add error handling to the file reading function</li>";
echo "<li>Create a custom exception for the calculateAverage function</li>";
echo "<li>Add logging to track function calls and execution times</li>";
echo "</ol>";
?>
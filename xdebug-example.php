<?php
/**
 * Xdebug Example
 * Practice file for step-by-step debugging with breakpoints
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

// This file is designed for interactive debugging with Xdebug
// Set breakpoints on the lines marked with // BREAKPOINT

echo "<h1>Xdebug Practice</h1>";

// Example 1: Simple function debugging
function calculateTax($amount, $rate = 0.1) {
    // BREAKPOINT: Set breakpoint here and examine $amount and $rate
    $tax = $amount * $rate;
    
    // BREAKPOINT: Check the calculated tax value
    $total = $amount + $tax;
    
    return [
        'amount' => $amount,
        'tax' => $tax,
        'total' => $total
    ];
}

echo "<h2>Tax Calculation</h2>";
$result = calculateTax(100, 0.15); // BREAKPOINT: Step into this function
debugVar("Tax Result", $result);

// Example 2: Loop debugging
echo "<h2>Loop Processing</h2>";
$numbers = [1, 2, 3, 4, 5];
$processed = [];

foreach ($numbers as $index => $number) {
    // BREAKPOINT: Examine $index, $number, and $processed array
    $squared = $number * $number;
    $processed[$index] = [
        'original' => $number,
        'squared' => $squared
    ];
    
    // BREAKPOINT: Watch how the array grows
    echo "Processed: $number -> $squared<br>";
}

// Example 3: Conditional debugging
echo "<h2>Conditional Logic</h2>";
function gradeStudent($score) {
    // BREAKPOINT: Step through different score values
    if ($score >= 90) {
        $grade = 'A';
        $message = 'Excellent';
    } elseif ($score >= 80) {
        $grade = 'B';
        $message = 'Good';
    } elseif ($score >= 70) {
        $grade = 'C';
        $message = 'Average';
    } elseif ($score >= 60) {
        $grade = 'D';
        $message = 'Below Average';
    } else {
        $grade = 'F';
        $message = 'Fail';
    }
    
    // BREAKPOINT: Check final grade and message
    return compact('score', 'grade', 'message');
}

$testScores = [95, 82, 67, 54];
foreach ($testScores as $score) {
    $result = gradeStudent($score); // BREAKPOINT: Step into function
    echo "Score $score: Grade {$result['grade']} - {$result['message']}<br>";
}

// Example 4: Array manipulation
echo "<h2>Array Debugging</h2>";
$students = [
    ['name' => 'Alice', 'age' => 17, 'scores' => [95, 87, 92]],
    ['name' => 'Bob', 'age' => 18, 'scores' => [78, 85, 89]],
    ['name' => 'Charlie', 'age' => 17, 'scores' => [88, 91, 86]]
];

foreach ($students as $index => $student) {
    // BREAKPOINT: Examine student data structure
    $average = array_sum($student['scores']) / count($student['scores']);
    
    // BREAKPOINT: Check calculated average
    $students[$index]['average'] = round($average, 2);
    
    echo "Student: {$student['name']}, Average: $average<br>";
}

// Example 5: Error condition debugging
echo "<h2>Error Handling Debug</h2>";
function divideNumbers($a, $b) {
    // BREAKPOINT: Check input parameters
    if ($b == 0) {
        // BREAKPOINT: This path should trigger for division by zero
        throw new InvalidArgumentException("Cannot divide by zero");
    }
    
    $result = $a / $b; // BREAKPOINT: Normal division
    return $result;
}

$testCases = [
    [10, 2],   // Normal case
    [15, 3],   // Normal case
    [20, 0],   // Error case
    [8, 4]     // Normal case
];

foreach ($testCases as $case) {
    try {
        [$dividend, $divisor] = $case;
        // BREAKPOINT: Step into function with different parameters
        $result = divideNumbers($dividend, $divisor);
        echo "$dividend ÷ $divisor = $result<br>";
    } catch (Exception $e) {
        // BREAKPOINT: Examine exception details
        echo "Error: " . $e->getMessage() . "<br>";
    }
}

// Helper function for debugging output
function debugVar($label, $var) {
    echo "<div style='background: #f8f9fa; padding: 10px; margin: 10px 0; border: 1px solid #dee2e6;'>";
    echo "<strong>$label:</strong><br>";
    echo "<pre>" . htmlspecialchars(print_r($var, true)) . "</pre>";
    echo "</div>";
}

echo "<h2>Xdebug Practice Instructions</h2>";
echo "<ol>";
echo "<li>Install Xdebug and configure your IDE (VS Code with PHP Debug extension)</li>";
echo "<li>Set breakpoints on lines marked with // BREAKPOINT comments</li>";
echo "<li>Start debugging session and step through the code</li>";
echo "<li>Examine variable values at each breakpoint</li>";
echo "<li>Practice stepping into, over, and out of functions</li>";
echo "<li>Use the debug console to evaluate expressions</li>";
echo "</ol>";

echo "<h3>VS Code Debugging Tips:</h3>";
echo "<ul>";
echo "<li>F9: Toggle breakpoint</li>";
echo "<li>F5: Start debugging / Continue</li>";
echo "<li>F10: Step over</li>";
echo "<li>F11: Step into</li>";
echo "<li>Shift+F11: Step out</li>";
echo "<li>Use the Variables panel to inspect values</li>";
echo "<li>Use the Debug Console to evaluate expressions</li>";
echo "</ul>";
?>
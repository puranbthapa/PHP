# Lesson 6: PHP Functions & Scope
**Course:** PHP Web Development - Class XII  
**Duration:** 3 hours  
**Prerequisites:** Lessons 1-5 completed, understanding of variables, operators, control structures, and loops

---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Define and call user-defined functions in PHP
2. Understand function parameters, arguments, and return values
3. Work with variable scope (local, global, static)
4. Use built-in PHP functions effectively
5. Implement advanced function concepts (default parameters, variable functions)
6. Apply best practices for function design and organization

---

## Key Concepts

### 1. Introduction to Functions

#### What are Functions?
Functions are reusable blocks of code that perform specific tasks. They help in:
- **Code Reusability:** Write once, use many times
- **Modularity:** Break complex programs into smaller, manageable pieces
- **Maintainability:** Easier to debug and update code
- **Organization:** Better code structure and readability

#### Types of Functions in PHP:
1. **Built-in Functions:** Pre-defined by PHP (e.g., `strlen()`, `array_push()`)
2. **User-defined Functions:** Created by programmers for specific needs

### 2. Creating User-Defined Functions

#### Basic Function Syntax:
```php
function functionName($parameter1, $parameter2, ...) {
    // Function body
    return $value; // Optional
}
```

#### Simple Function Examples:
```php
<?php
// Function without parameters
function greetUser() {
    echo "Welcome to our PHP Course!<br>";
}

// Function with parameters
function greetPersonal($name, $course = "PHP") {
    echo "Hello $name! Welcome to the $course course.<br>";
}

// Function with return value
function calculateArea($length, $width) {
    $area = $length * $width;
    return $area;
}

// Function for mathematical operations
function calculator($num1, $num2, $operation) {
    switch ($operation) {
        case 'add':
            return $num1 + $num2;
        case 'subtract':
            return $num1 - $num2;
        case 'multiply':
            return $num1 * $num2;
        case 'divide':
            if ($num2 != 0) {
                return $num1 / $num2;
            } else {
                return "Error: Division by zero!";
            }
        default:
            return "Error: Invalid operation!";
    }
}

// Using the functions
greetUser();
greetPersonal("Alice");
greetPersonal("Bob", "JavaScript");

$area = calculateArea(10, 5);
echo "Area: $area square units<br>";

$result = calculator(15, 3, 'multiply');
echo "15 × 3 = $result<br>";
?>
```

#### Advanced Function Features:
```php
<?php
// Function with default parameter values
function createProfile($name, $age = 18, $city = "Unknown", $country = "India") {
    return [
        'name' => $name,
        'age' => $age,
        'city' => $city,
        'country' => $country
    ];
}

// Function with variable number of arguments
function calculateSum(...$numbers) {
    $total = 0;
    foreach ($numbers as $number) {
        $total += $number;
    }
    return $total;
}

// Function returning multiple values (using array)
function getStudentStats($grades) {
    $total = array_sum($grades);
    $count = count($grades);
    $average = $total / $count;
    $highest = max($grades);
    $lowest = min($grades);
    
    return [
        'total' => $total,
        'count' => $count,
        'average' => round($average, 2),
        'highest' => $highest,
        'lowest' => $lowest
    ];
}

// Using advanced functions
$profile1 = createProfile("Alice");
$profile2 = createProfile("Bob", 20, "Mumbai");
print_r($profile1);
print_r($profile2);

$sum1 = calculateSum(1, 2, 3, 4, 5);
$sum2 = calculateSum(10, 20);
echo "Sum 1: $sum1<br>";
echo "Sum 2: $sum2<br>";

$grades = [85, 92, 78, 88, 95, 73];
$stats = getStudentStats($grades);
echo "Statistics: " . json_encode($stats) . "<br>";
?>
```

### 3. Variable Scope

#### Understanding Scope Types:

#### Local Scope:
```php
<?php
function testLocal() {
    $localVar = "I am local to this function";
    echo $localVar . "<br>";
}

testLocal();
// echo $localVar; // This would cause an error - variable not accessible outside function
?>
```

#### Global Scope:
```php
<?php
$globalVar = "I am a global variable";

function testGlobal() {
    global $globalVar; // Need to declare global to access
    echo "Inside function: " . $globalVar . "<br>";
    
    $globalVar = "Modified inside function";
}

echo "Before function: " . $globalVar . "<br>";
testGlobal();
echo "After function: " . $globalVar . "<br>";

// Alternative way using $GLOBALS superglobal
function testGlobals() {
    echo "Using GLOBALS: " . $GLOBALS['globalVar'] . "<br>";
    $GLOBALS['globalVar'] = "Changed via GLOBALS";
}

testGlobals();
echo "Final value: " . $globalVar . "<br>";
?>
```

#### Static Scope:
```php
<?php
function countCalls() {
    static $count = 0; // Static variable retains value between calls
    $count++;
    echo "This function has been called $count time(s)<br>";
}

// Test static variable
countCalls(); // Output: 1 time(s)
countCalls(); // Output: 2 time(s)
countCalls(); // Output: 3 time(s)

// Practical example: Unique ID generator
function generateUniqueId($prefix = "ID") {
    static $counter = 1000;
    return $prefix . "_" . (++$counter);
}

echo "ID 1: " . generateUniqueId() . "<br>";
echo "ID 2: " . generateUniqueId("USER") . "<br>";
echo "ID 3: " . generateUniqueId("PROD") . "<br>";
?>
```

### 4. Function Parameters and Arguments

#### Pass by Value vs Pass by Reference:
```php
<?php
// Pass by value (default) - original variable unchanged
function modifyValue($number) {
    $number = $number * 2;
    echo "Inside function: $number<br>";
}

$original = 10;
echo "Before function: $original<br>";
modifyValue($original);
echo "After function: $original<br>"; // Still 10

echo "<hr>";

// Pass by reference - original variable is modified
function modifyReference(&$number) {
    $number = $number * 2;
    echo "Inside function: $number<br>";
}

$original2 = 10;
echo "Before function: $original2<br>";
modifyReference($original2);
echo "After function: $original2<br>"; // Now 20

// Practical example: Array modification
function addGrade(&$grades, $newGrade) {
    $grades[] = $newGrade;
    echo "Grade added. Total grades: " . count($grades) . "<br>";
}

$studentGrades = [85, 90, 78];
echo "Original grades: " . implode(", ", $studentGrades) . "<br>";
addGrade($studentGrades, 92);
echo "Updated grades: " . implode(", ", $studentGrades) . "<br>";
?>
```

#### Type Declarations and Return Types:
```php
<?php
// Type declarations (PHP 7+)
function calculatePercentage(float $obtained, float $total): float {
    if ($total <= 0) {
        throw new InvalidArgumentException("Total marks must be positive");
    }
    return ($obtained / $total) * 100;
}

function formatStudentName(string $firstName, string $lastName): string {
    return ucfirst(strtolower($firstName)) . " " . ucfirst(strtolower($lastName));
}

function isPassingGrade(int $marks): bool {
    return $marks >= 60;
}

// Using type-declared functions
try {
    $percentage = calculatePercentage(85.5, 100);
    echo "Percentage: " . round($percentage, 2) . "%<br>";
    
    $formattedName = formatStudentName("alice", "JOHNSON");
    echo "Formatted name: $formattedName<br>";
    
    $isPassing = isPassingGrade(75);
    echo "Is passing: " . ($isPassing ? "Yes" : "No") . "<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
?>
```

### 5. Built-in PHP Functions

#### String Functions:
```php
<?php
$text = "  Hello World, Welcome to PHP Programming!  ";

echo "<h3>String Functions Demo:</h3>";
echo "Original: '$text'<br>";
echo "Length: " . strlen($text) . "<br>";
echo "Trimmed: '" . trim($text) . "'<br>";
echo "Uppercase: " . strtoupper($text) . "<br>";
echo "Lowercase: " . strtolower($text) . "<br>";
echo "Word count: " . str_word_count(trim($text)) . "<br>";
echo "Position of 'PHP': " . strpos($text, "PHP") . "<br>";
echo "Replace 'World' with 'Universe': " . str_replace("World", "Universe", $text) . "<br>";

// Substring operations
$sentence = "PHP is a powerful scripting language";
echo "Substring (0,3): " . substr($sentence, 0, 3) . "<br>";
echo "Substring (7,8): " . substr($sentence, 7, 8) . "<br>";
echo "Last 8 characters: " . substr($sentence, -8) . "<br>";
?>
```

#### Array Functions:
```php
<?php
$fruits = ["apple", "banana", "orange", "grape", "mango"];
$numbers = [5, 2, 8, 1, 9, 3];

echo "<h3>Array Functions Demo:</h3>";
echo "Original fruits: " . implode(", ", $fruits) . "<br>";
echo "Array length: " . count($fruits) . "<br>";

// Adding elements
array_push($fruits, "pineapple", "kiwi");
echo "After push: " . implode(", ", $fruits) . "<br>";

// Removing elements
$lastFruit = array_pop($fruits);
echo "Popped: $lastFruit<br>";
echo "After pop: " . implode(", ", $fruits) . "<br>";

// Array sorting
echo "Numbers: " . implode(", ", $numbers) . "<br>";
sort($numbers);
echo "Sorted ascending: " . implode(", ", $numbers) . "<br>";
rsort($numbers);
echo "Sorted descending: " . implode(", ", $numbers) . "<br>";

// Array searching
echo "Is 'apple' in fruits? " . (in_array("apple", $fruits) ? "Yes" : "No") . "<br>";
echo "Key of 'orange': " . array_search("orange", $fruits) . "<br>";

// Array filtering
$evenNumbers = array_filter($numbers, function($n) { return $n % 2 == 0; });
echo "Even numbers: " . implode(", ", $evenNumbers) . "<br>";

// Array mapping
$squaredNumbers = array_map(function($n) { return $n * $n; }, array_slice($numbers, 0, 4));
echo "Squared (first 4): " . implode(", ", $squaredNumbers) . "<br>";
?>
```

#### Mathematical Functions:
```php
<?php
echo "<h3>Mathematical Functions Demo:</h3>";

$numbers = [2.7, -3.8, 4.2, -1.5, 5.9];
echo "Numbers: " . implode(", ", $numbers) . "<br>";

foreach ($numbers as $num) {
    echo "Number: $num | ";
    echo "abs: " . abs($num) . " | ";
    echo "ceil: " . ceil($num) . " | ";
    echo "floor: " . floor($num) . " | ";
    echo "round: " . round($num) . "<br>";
}

echo "<br>";
echo "Random number (1-100): " . rand(1, 100) . "<br>";
echo "Max of array: " . max($numbers) . "<br>";
echo "Min of array: " . min($numbers) . "<br>";
echo "Square root of 64: " . sqrt(64) . "<br>";
echo "2 power 8: " . pow(2, 8) . "<br>";
echo "Pi constant: " . pi() . "<br>";
echo "Sin(pi/2): " . sin(pi()/2) . "<br>";
?>
```

#### Date and Time Functions:
```php
<?php
echo "<h3>Date and Time Functions Demo:</h3>";

echo "Current timestamp: " . time() . "<br>";
echo "Current date: " . date('Y-m-d') . "<br>";
echo "Current time: " . date('H:i:s') . "<br>";
echo "Current datetime: " . date('Y-m-d H:i:s') . "<br>";

// Formatted dates
echo "Formatted date: " . date('l, F j, Y') . "<br>";
echo "12-hour format: " . date('g:i A') . "<br>";

// Date calculations
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$nextWeek = date('Y-m-d', strtotime('+1 week'));
$lastMonth = date('Y-m-d', strtotime('-1 month'));

echo "Tomorrow: $tomorrow<br>";
echo "Next week: $nextWeek<br>";
echo "Last month: $lastMonth<br>";

// Age calculation function
function calculateAge($birthDate) {
    $birthTimestamp = strtotime($birthDate);
    $ageInSeconds = time() - $birthTimestamp;
    $ageInYears = floor($ageInSeconds / (365 * 24 * 3600));
    return $ageInYears;
}

echo "Age for birthdate '2005-03-15': " . calculateAge('2005-03-15') . " years<br>";
?>
```

---

## Practical Activities

### Activity 1: Student Management Functions Library (90 minutes)

#### Create file: `student-functions.php`
```php
<?php
/**
 * Student Management Functions Library
 * A comprehensive collection of functions for managing student data
 */

// Global array to store student data (in real application, this would be a database)
$GLOBALS['students'] = [
    [
        'id' => 1,
        'name' => 'Alice Johnson',
        'roll' => '2025001',
        'class' => 'XII-A',
        'subjects' => [
            'Mathematics' => 85,
            'Physics' => 90,
            'Chemistry' => 82,
            'Biology' => 88,
            'English' => 87
        ]
    ],
    [
        'id' => 2,
        'name' => 'Bob Smith',
        'roll' => '2025002', 
        'class' => 'XII-A',
        'subjects' => [
            'Mathematics' => 78,
            'Physics' => 85,
            'Chemistry' => 79,
            'Biology' => 83,
            'English' => 81
        ]
    ],
    [
        'id' => 3,
        'name' => 'Carol Davis',
        'roll' => '2025003',
        'class' => 'XII-B',
        'subjects' => [
            'Mathematics' => 95,
            'Physics' => 93,
            'Chemistry' => 91,
            'Biology' => 89,
            'English' => 92
        ]
    ]
];

/**
 * Add a new student to the system
 * @param string $name Student's full name
 * @param string $roll Roll number
 * @param string $class Class section
 * @param array $subjects Associative array of subject => marks
 * @return bool|string Success message or error
 */
function addStudent($name, $roll, $class, $subjects = []) {
    global $students;
    
    // Validation
    if (empty($name) || empty($roll) || empty($class)) {
        return "Error: Name, roll number, and class are required.";
    }
    
    // Check for duplicate roll number
    if (findStudentByRoll($roll)) {
        return "Error: Student with roll number '$roll' already exists.";
    }
    
    // Generate new ID
    $newId = count($students) > 0 ? max(array_column($students, 'id')) + 1 : 1;
    
    // Add student
    $students[] = [
        'id' => $newId,
        'name' => trim($name),
        'roll' => $roll,
        'class' => $class,
        'subjects' => $subjects
    ];
    
    return "Student '$name' added successfully with ID: $newId";
}

/**
 * Find student by roll number
 * @param string $roll Roll number to search
 * @return array|null Student data or null if not found
 */
function findStudentByRoll($roll) {
    global $students;
    
    foreach ($students as $student) {
        if ($student['roll'] === $roll) {
            return $student;
        }
    }
    return null;
}

/**
 * Find student by ID
 * @param int $id Student ID
 * @return array|null Student data or null if not found
 */
function findStudentById($id) {
    global $students;
    
    foreach ($students as $student) {
        if ($student['id'] === $id) {
            return $student;
        }
    }
    return null;
}

/**
 * Update student information
 * @param int $id Student ID
 * @param array $updates Associative array of fields to update
 * @return bool|string Success message or error
 */
function updateStudent($id, $updates) {
    global $students;
    
    $studentIndex = -1;
    for ($i = 0; $i < count($students); $i++) {
        if ($students[$i]['id'] === $id) {
            $studentIndex = $i;
            break;
        }
    }
    
    if ($studentIndex === -1) {
        return "Error: Student with ID $id not found.";
    }
    
    // Update allowed fields
    $allowedFields = ['name', 'roll', 'class', 'subjects'];
    foreach ($updates as $field => $value) {
        if (in_array($field, $allowedFields)) {
            $students[$studentIndex][$field] = $value;
        }
    }
    
    return "Student information updated successfully.";
}

/**
 * Delete student by ID
 * @param int $id Student ID
 * @return bool|string Success message or error
 */
function deleteStudent($id) {
    global $students;
    
    for ($i = 0; $i < count($students); $i++) {
        if ($students[$i]['id'] === $id) {
            $name = $students[$i]['name'];
            array_splice($students, $i, 1);
            return "Student '$name' deleted successfully.";
        }
    }
    
    return "Error: Student with ID $id not found.";
}

/**
 * Calculate student's average marks
 * @param array $subjects Associative array of subject => marks
 * @return float Average marks
 */
function calculateAverage($subjects) {
    if (empty($subjects)) {
        return 0;
    }
    
    return array_sum($subjects) / count($subjects);
}

/**
 * Get grade based on percentage
 * @param float $percentage Percentage marks
 * @return string Grade letter
 */
function getGrade($percentage) {
    if ($percentage >= 90) return 'A+';
    elseif ($percentage >= 85) return 'A';
    elseif ($percentage >= 80) return 'B+';
    elseif ($percentage >= 75) return 'B';
    elseif ($percentage >= 70) return 'C+';
    elseif ($percentage >= 60) return 'C';
    elseif ($percentage >= 50) return 'D';
    else return 'F';
}

/**
 * Generate detailed student report
 * @param int $id Student ID
 * @return string|bool HTML formatted report or error message
 */
function generateStudentReport($id) {
    $student = findStudentById($id);
    
    if (!$student) {
        return "Error: Student with ID $id not found.";
    }
    
    $average = calculateAverage($student['subjects']);
    $grade = getGrade($average);
    
    $report = "<div style='border: 1px solid #ddd; padding: 20px; margin: 10px; border-radius: 8px; background-color: #f9f9f9;'>";
    $report .= "<h3>Student Report Card</h3>";
    $report .= "<p><strong>Name:</strong> {$student['name']}</p>";
    $report .= "<p><strong>Roll Number:</strong> {$student['roll']}</p>";
    $report .= "<p><strong>Class:</strong> {$student['class']}</p>";
    $report .= "<h4>Subject Wise Marks:</h4>";
    
    if (!empty($student['subjects'])) {
        $report .= "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        $report .= "<tr style='background-color: #e9ecef;'><th>Subject</th><th>Marks</th><th>Grade</th></tr>";
        
        foreach ($student['subjects'] as $subject => $marks) {
            $subjectGrade = getGrade($marks);
            $report .= "<tr>";
            $report .= "<td>$subject</td>";
            $report .= "<td>$marks</td>";
            $report .= "<td>$subjectGrade</td>";
            $report .= "</tr>";
        }
        
        $report .= "</table>";
        $report .= "<p><strong>Overall Average:</strong> " . number_format($average, 2) . "%</p>";
        $report .= "<p><strong>Overall Grade:</strong> $grade</p>";
    } else {
        $report .= "<p>No subject marks available.</p>";
    }
    
    $report .= "</div>";
    return $report;
}

/**
 * Get all students by class
 * @param string $class Class name (e.g., 'XII-A')
 * @return array Array of students in the specified class
 */
function getStudentsByClass($class) {
    global $students;
    
    return array_filter($students, function($student) use ($class) {
        return $student['class'] === $class;
    });
}

/**
 * Get class statistics
 * @param string $class Class name
 * @return array Statistics including average, top performer, etc.
 */
function getClassStatistics($class) {
    $classStudents = getStudentsByClass($class);
    
    if (empty($classStudents)) {
        return ['error' => "No students found in class $class"];
    }
    
    $averages = [];
    $topPerformer = null;
    $highestAverage = 0;
    
    foreach ($classStudents as $student) {
        $average = calculateAverage($student['subjects']);
        $averages[] = $average;
        
        if ($average > $highestAverage) {
            $highestAverage = $average;
            $topPerformer = $student;
        }
    }
    
    return [
        'class' => $class,
        'total_students' => count($classStudents),
        'class_average' => array_sum($averages) / count($averages),
        'highest_average' => $highestAverage,
        'lowest_average' => min($averages),
        'top_performer' => $topPerformer
    ];
}

/**
 * Search students by name (partial match)
 * @param string $searchTerm Search term
 * @return array Array of matching students
 */
function searchStudentsByName($searchTerm) {
    global $students;
    
    $searchTerm = strtolower($searchTerm);
    
    return array_filter($students, function($student) use ($searchTerm) {
        return strpos(strtolower($student['name']), $searchTerm) !== false;
    });
}

/**
 * Add marks for a subject to a student
 * @param int $studentId Student ID
 * @param string $subject Subject name
 * @param int $marks Marks obtained
 * @return bool|string Success message or error
 */
function addSubjectMarks($studentId, $subject, $marks) {
    global $students;
    
    if ($marks < 0 || $marks > 100) {
        return "Error: Marks must be between 0 and 100.";
    }
    
    for ($i = 0; $i < count($students); $i++) {
        if ($students[$i]['id'] === $studentId) {
            $students[$i]['subjects'][$subject] = $marks;
            return "Marks added successfully for {$students[$i]['name']} in $subject.";
        }
    }
    
    return "Error: Student with ID $studentId not found.";
}

/**
 * Get subject-wise class performance
 * @param string $subject Subject name
 * @return array Subject performance statistics
 */
function getSubjectPerformance($subject) {
    global $students;
    
    $subjectMarks = [];
    $studentsWithSubject = 0;
    
    foreach ($students as $student) {
        if (isset($student['subjects'][$subject])) {
            $subjectMarks[] = $student['subjects'][$subject];
            $studentsWithSubject++;
        }
    }
    
    if (empty($subjectMarks)) {
        return ['error' => "No data found for subject: $subject"];
    }
    
    return [
        'subject' => $subject,
        'total_students' => $studentsWithSubject,
        'average_marks' => array_sum($subjectMarks) / count($subjectMarks),
        'highest_marks' => max($subjectMarks),
        'lowest_marks' => min($subjectMarks),
        'pass_count' => count(array_filter($subjectMarks, function($m) { return $m >= 60; })),
        'fail_count' => count(array_filter($subjectMarks, function($m) { return $m < 60; }))
    ];
}

/**
 * Display all students in a formatted table
 * @return string HTML table of all students
 */
function displayAllStudents() {
    global $students;
    
    if (empty($students)) {
        return "<p>No students found in the system.</p>";
    }
    
    $html = "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
    $html .= "<thead style='background-color: #007bff; color: white;'>";
    $html .= "<tr><th>ID</th><th>Name</th><th>Roll</th><th>Class</th><th>Average</th><th>Grade</th><th>Actions</th></tr>";
    $html .= "</thead><tbody>";
    
    foreach ($students as $student) {
        $average = calculateAverage($student['subjects']);
        $grade = getGrade($average);
        
        $html .= "<tr>";
        $html .= "<td>{$student['id']}</td>";
        $html .= "<td>{$student['name']}</td>";
        $html .= "<td>{$student['roll']}</td>";
        $html .= "<td>{$student['class']}</td>";
        $html .= "<td>" . number_format($average, 1) . "%</td>";
        $html .= "<td>$grade</td>";
        $html .= "<td><a href='?action=view&id={$student['id']}'>View Report</a></td>";
        $html .= "</tr>";
    }
    
    $html .= "</tbody></table>";
    return $html;
}
?>
```

#### Create file: `student-management-demo.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Student Management System - Functions Demo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; }
        .section { background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .controls { background-color: #e9ecef; padding: 15px; margin: 10px 0; border-radius: 5px; }
        button { background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background-color: #0056b3; }
        .success { color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; }
        input, select { padding: 5px; margin: 5px; border: 1px solid #ddd; border-radius: 3px; }
    </style>
</head>
<body>
    <?php
    include 'student-functions.php';
    
    // Handle form submissions
    $message = "";
    
    if ($_POST) {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'add_student':
                $result = addStudent(
                    $_POST['name'], 
                    $_POST['roll'], 
                    $_POST['class']
                );
                $message = $result;
                break;
                
            case 'add_marks':
                $result = addSubjectMarks(
                    (int)$_POST['student_id'],
                    $_POST['subject'],
                    (int)$_POST['marks']
                );
                $message = $result;
                break;
                
            case 'update_student':
                $updates = [
                    'name' => $_POST['name'],
                    'class' => $_POST['class']
                ];
                $result = updateStudent((int)$_POST['student_id'], $updates);
                $message = $result;
                break;
        }
    }
    ?>
    
    <h1>🎓 Student Management System - Functions Demo</h1>
    
    <?php if ($message): ?>
        <div class="<?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <!-- Add Student Form -->
    <div class="section">
        <h2>➕ Add New Student</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add_student">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="roll" placeholder="Roll Number" required>
            <select name="class" required>
                <option value="">Select Class</option>
                <option value="XII-A">XII-A</option>
                <option value="XII-B">XII-B</option>
                <option value="XI-A">XI-A</option>
                <option value="XI-B">XI-B</option>
            </select>
            <button type="submit">Add Student</button>
        </form>
    </div>
    
    <!-- Add Marks Form -->
    <div class="section">
        <h2>📝 Add Subject Marks</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add_marks">
            <select name="student_id" required>
                <option value="">Select Student</option>
                <?php
                foreach ($GLOBALS['students'] as $student) {
                    echo "<option value='{$student['id']}'>{$student['name']} ({$student['roll']})</option>";
                }
                ?>
            </select>
            <input type="text" name="subject" placeholder="Subject Name" required>
            <input type="number" name="marks" min="0" max="100" placeholder="Marks" required>
            <button type="submit">Add Marks</button>
        </form>
    </div>
    
    <!-- Function Testing Section -->
    <div class="section">
        <h2>🔧 Function Testing</h2>
        <div class="controls">
            <h3>Search Functions:</h3>
            <p><strong>Search by Name "Alice":</strong></p>
            <?php
            $searchResults = searchStudentsByName("Alice");
            if (!empty($searchResults)) {
                foreach ($searchResults as $student) {
                    echo "Found: {$student['name']} - {$student['roll']}<br>";
                }
            } else {
                echo "No students found.";
            }
            ?>
        </div>
        
        <div class="controls">
            <h3>Class Statistics:</h3>
            <?php
            $stats = getClassStatistics('XII-A');
            if (isset($stats['error'])) {
                echo "<p class='error'>{$stats['error']}</p>";
            } else {
                echo "<p><strong>Class:</strong> {$stats['class']}</p>";
                echo "<p><strong>Total Students:</strong> {$stats['total_students']}</p>";
                echo "<p><strong>Class Average:</strong> " . number_format($stats['class_average'], 2) . "%</p>";
                echo "<p><strong>Top Performer:</strong> {$stats['top_performer']['name']} (" . 
                     number_format($stats['highest_average'], 2) . "%)</p>";
            }
            ?>
        </div>
        
        <div class="controls">
            <h3>Subject Performance Analysis:</h3>
            <?php
            $subjects = ['Mathematics', 'Physics', 'Chemistry'];
            foreach ($subjects as $subject) {
                $performance = getSubjectPerformance($subject);
                if (!isset($performance['error'])) {
                    echo "<h4>$subject:</h4>";
                    echo "<ul>";
                    echo "<li>Average: " . number_format($performance['average_marks'], 1) . "%</li>";
                    echo "<li>Highest: {$performance['highest_marks']}%</li>";
                    echo "<li>Pass Rate: {$performance['pass_count']}/{$performance['total_students']}</li>";
                    echo "</ul>";
                }
            }
            ?>
        </div>
    </div>
    
    <!-- Display All Students -->
    <div class="section">
        <h2>👥 All Students</h2>
        <?php echo displayAllStudents(); ?>
    </div>
    
    <!-- Individual Reports -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])): ?>
        <div class="section">
            <h2>📋 Student Report</h2>
            <?php echo generateStudentReport((int)$_GET['id']); ?>
        </div>
    <?php endif; ?>
</body>
</html>
```

### Activity 2: Mathematical Functions Calculator (45 minutes)

#### Create file: `math-calculator.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Mathematical Calculator</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .calculator { background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .result { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        input[type="number"] { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; width: 100px; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background-color: #0056b3; }
        .operation-group { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>🔢 Advanced Mathematical Calculator</h1>
    
    <?php
    // Basic arithmetic functions
    function add($a, $b) {
        return $a + $b;
    }
    
    function subtract($a, $b) {
        return $a - $b;
    }
    
    function multiply($a, $b) {
        return $a * $b;
    }
    
    function divide($a, $b) {
        if ($b == 0) {
            return "Error: Division by zero";
        }
        return $a / $b;
    }
    
    function power($base, $exponent) {
        return pow($base, $exponent);
    }
    
    function squareRoot($number) {
        if ($number < 0) {
            return "Error: Cannot calculate square root of negative number";
        }
        return sqrt($number);
    }
    
    // Advanced mathematical functions
    function factorial($n) {
        if ($n < 0) {
            return "Error: Factorial not defined for negative numbers";
        }
        if ($n == 0 || $n == 1) {
            return 1;
        }
        
        $result = 1;
        for ($i = 2; $i <= $n; $i++) {
            $result *= $i;
        }
        return $result;
    }
    
    function fibonacci($n) {
        if ($n < 0) {
            return "Error: Fibonacci not defined for negative numbers";
        }
        if ($n == 0) return 0;
        if ($n == 1) return 1;
        
        $sequence = [0, 1];
        for ($i = 2; $i <= $n; $i++) {
            $sequence[$i] = $sequence[$i-1] + $sequence[$i-2];
        }
        
        return $sequence[$n];
    }
    
    function isPrime($number) {
        if ($number < 2) return false;
        if ($number == 2) return true;
        if ($number % 2 == 0) return false;
        
        for ($i = 3; $i <= sqrt($number); $i += 2) {
            if ($number % $i == 0) {
                return false;
            }
        }
        return true;
    }
    
    function gcd($a, $b) {
        while ($b != 0) {
            $temp = $b;
            $b = $a % $b;
            $a = $temp;
        }
        return abs($a);
    }
    
    function lcm($a, $b) {
        return abs($a * $b) / gcd($a, $b);
    }
    
    // Statistical functions
    function calculateMean($numbers) {
        if (empty($numbers)) return 0;
        return array_sum($numbers) / count($numbers);
    }
    
    function calculateMedian($numbers) {
        if (empty($numbers)) return 0;
        
        sort($numbers);
        $count = count($numbers);
        $middle = floor($count / 2);
        
        if ($count % 2 == 0) {
            return ($numbers[$middle - 1] + $numbers[$middle]) / 2;
        } else {
            return $numbers[$middle];
        }
    }
    
    function calculateMode($numbers) {
        if (empty($numbers)) return [];
        
        $frequency = array_count_values($numbers);
        $maxFreq = max($frequency);
        
        return array_keys(array_filter($frequency, function($freq) use ($maxFreq) {
            return $freq == $maxFreq;
        }));
    }
    
    function calculateStandardDeviation($numbers) {
        if (count($numbers) <= 1) return 0;
        
        $mean = calculateMean($numbers);
        $squaredDifferences = array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $numbers);
        
        $variance = array_sum($squaredDifferences) / count($numbers);
        return sqrt($variance);
    }
    
    // Process calculations
    $result = "";
    if ($_POST) {
        $operation = $_POST['operation'] ?? '';
        
        try {
            switch ($operation) {
                case 'basic':
                    $num1 = floatval($_POST['num1']);
                    $num2 = floatval($_POST['num2']);
                    $op = $_POST['basic_op'];
                    
                    switch ($op) {
                        case 'add': $result = "Result: " . add($num1, $num2); break;
                        case 'subtract': $result = "Result: " . subtract($num1, $num2); break;
                        case 'multiply': $result = "Result: " . multiply($num1, $num2); break;
                        case 'divide': $result = "Result: " . divide($num1, $num2); break;
                        case 'power': $result = "Result: " . power($num1, $num2); break;
                        case 'gcd': $result = "GCD: " . gcd($num1, $num2); break;
                        case 'lcm': $result = "LCM: " . lcm($num1, $num2); break;
                    }
                    break;
                    
                case 'single':
                    $num = floatval($_POST['single_num']);
                    $op = $_POST['single_op'];
                    
                    switch ($op) {
                        case 'sqrt': $result = "Square root: " . squareRoot($num); break;
                        case 'factorial': 
                            $factResult = factorial($num);
                            $result = "Factorial: " . $factResult;
                            break;
                        case 'fibonacci':
                            $fibResult = fibonacci($num);
                            $result = "Fibonacci($num): " . $fibResult;
                            break;
                        case 'prime':
                            $primeResult = isPrime($num) ? "Yes" : "No";
                            $result = "Is $num prime? " . $primeResult;
                            break;
                    }
                    break;
                    
                case 'statistics':
                    $numbers_input = $_POST['numbers'];
                    $numbers = array_map('floatval', array_filter(explode(',', $numbers_input), 'is_numeric'));
                    
                    if (!empty($numbers)) {
                        $mean = calculateMean($numbers);
                        $median = calculateMedian($numbers);
                        $mode = calculateMode($numbers);
                        $std_dev = calculateStandardDeviation($numbers);
                        
                        $result = "<strong>Statistical Analysis:</strong><br>";
                        $result .= "Numbers: " . implode(', ', $numbers) . "<br>";
                        $result .= "Mean: " . round($mean, 2) . "<br>";
                        $result .= "Median: " . $median . "<br>";
                        $result .= "Mode: " . implode(', ', $mode) . "<br>";
                        $result .= "Standard Deviation: " . round($std_dev, 2) . "<br>";
                        $result .= "Range: " . (max($numbers) - min($numbers)) . "<br>";
                    } else {
                        $result = "Error: Please enter valid numbers separated by commas";
                    }
                    break;
            }
        } catch (Exception $e) {
            $result = "Error: " . $e->getMessage();
        }
    }
    ?>
    
    <?php if ($result): ?>
        <div class="<?php echo strpos($result, 'Error') !== false ? 'error' : 'result'; ?>">
            <?php echo $result; ?>
        </div>
    <?php endif; ?>
    
    <!-- Basic Operations -->
    <div class="calculator">
        <div class="operation-group">
            <h3>Basic Operations</h3>
            <form method="POST">
                <input type="hidden" name="operation" value="basic">
                <input type="number" name="num1" step="any" placeholder="First Number" required>
                <select name="basic_op" required>
                    <option value="">Select Operation</option>
                    <option value="add">Addition (+)</option>
                    <option value="subtract">Subtraction (-)</option>
                    <option value="multiply">Multiplication (×)</option>
                    <option value="divide">Division (÷)</option>
                    <option value="power">Power (^)</option>
                    <option value="gcd">GCD</option>
                    <option value="lcm">LCM</option>
                </select>
                <input type="number" name="num2" step="any" placeholder="Second Number" required>
                <button type="submit">Calculate</button>
            </form>
        </div>
        
        <div class="operation-group">
            <h3>Single Number Operations</h3>
            <form method="POST">
                <input type="hidden" name="operation" value="single">
                <input type="number" name="single_num" step="any" placeholder="Enter Number" required>
                <select name="single_op" required>
                    <option value="">Select Operation</option>
                    <option value="sqrt">Square Root</option>
                    <option value="factorial">Factorial</option>
                    <option value="fibonacci">Fibonacci</option>
                    <option value="prime">Check Prime</option>
                </select>
                <button type="submit">Calculate</button>
            </form>
        </div>
        
        <div class="operation-group">
            <h3>Statistical Analysis</h3>
            <form method="POST">
                <input type="hidden" name="operation" value="statistics">
                <input type="text" name="numbers" placeholder="Enter numbers separated by commas (e.g., 1,2,3,4,5)" style="width: 300px;" required>
                <button type="submit">Analyze</button>
            </form>
        </div>
    </div>
    
    <!-- Function Examples -->
    <div class="calculator">
        <h3>📊 Pre-calculated Examples</h3>
        <div class="operation-group">
            <h4>Factorial Examples:</h4>
            <?php
            for ($i = 0; $i <= 10; $i++) {
                echo "$i! = " . factorial($i) . "<br>";
            }
            ?>
        </div>
        
        <div class="operation-group">
            <h4>Fibonacci Sequence (First 15 numbers):</h4>
            <?php
            $fib_sequence = [];
            for ($i = 0; $i < 15; $i++) {
                $fib_sequence[] = fibonacci($i);
            }
            echo implode(', ', $fib_sequence);
            ?>
        </div>
        
        <div class="operation-group">
            <h4>Prime Numbers up to 50:</h4>
            <?php
            $primes = [];
            for ($i = 2; $i <= 50; $i++) {
                if (isPrime($i)) {
                    $primes[] = $i;
                }
            }
            echo implode(', ', $primes);
            ?>
        </div>
    </div>
</body>
</html>
```

---

## Exercises

### Exercise 1: Grade Calculator Functions (25 minutes)
Create `grade-calculator.php` that includes:
- Functions to calculate GPA from letter grades
- Percentage to letter grade conversion
- Class rank calculation functions
- Academic status determination (Honor Roll, Dean's List, etc.)

### Exercise 2: Text Processing Functions (20 minutes)
Build `text-processor.php` featuring:
- Word count and character analysis functions
- Text formatting utilities (title case, sentence case)
- String validation functions (email, phone, URL)
- Text encryption/decryption functions

### Exercise 3: Array Manipulation Library (30 minutes)
Develop `array-utilities.php` with:
- Array sorting and searching functions
- Array statistical functions (min, max, average)
- Array transformation utilities (merge, split, filter)
- Multidimensional array processing functions

### Exercise 4: Date/Time Functions Suite (25 minutes)
Create `datetime-utilities.php` containing:
- Age calculation from birthdate
- Business day calculations
- Date format conversion functions
- Event scheduling and reminder functions

---

## Assessment

### Knowledge Check:
1. What is the difference between function parameters and arguments?
2. Explain local, global, and static variable scope with examples.
3. When should you use pass-by-reference vs pass-by-value?
4. What are the benefits of using functions in programming?
5. How do you handle errors in functions?

### Practical Assessment:
- [ ] Created well-structured user-defined functions
- [ ] Demonstrated understanding of variable scope
- [ ] Used function parameters and return values correctly
- [ ] Applied built-in PHP functions appropriately
- [ ] Implemented error handling in functions

---

## Homework Assignment

### **Complete Library Management System Using Functions**
Create a modular system (`library-system.php`) with function-based architecture:

#### **Core Functions Required:**
1. **Book Management Functions:**
   - `addBook($title, $author, $isbn, $category)`
   - `searchBooks($query, $searchType)`
   - `updateBookInfo($isbn, $updates)`
   - `deleteBook($isbn)`

2. **Member Management Functions:**
   - `registerMember($name, $email, $phone)`
   - `updateMemberInfo($memberId, $updates)`
   - `findMember($identifier)`

3. **Transaction Functions:**
   - `issueBook($memberId, $isbn)`
   - `returnBook($memberId, $isbn)`
   - `calculateFine($issueDate, $returnDate)`
   - `getMemberHistory($memberId)`

4. **Report Generation Functions:**
   - `generateInventoryReport()`
   - `getOverdueBooks()`
   - `getPopularBooks($period)`
   - `getMemberStatistics()`

5. **Utility Functions:**
   - `validateISBN($isbn)`
   - `formatCurrency($amount)`
   - `calculateAge($birthDate)`

**Due:** Next class session  
**Assessment:** Function design, code organization, parameter handling, return value usage

---

## Next Lesson Preview
**Lesson 7: PHP Arrays - Indexed & Associative**
- Array creation and manipulation
- Multidimensional arrays
- Array functions and sorting
- Real-world array applications

---

*Functions are the building blocks of modular, maintainable code. Master these concepts to create efficient, reusable programming solutions.*
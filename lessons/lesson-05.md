# Lesson 5: Control Structures - Loops
**Course:** PHP Web Development - Class XII  
**Duration:** 2.5 hours  
**Prerequisites:** Lessons 1-4 completed, understanding of variables, operators, and conditional statements

---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Understand different types of loops in PHP (for, while, do-while, foreach)
2. Choose the appropriate loop type for different programming scenarios
3. Implement nested loops and complex iteration patterns
4. Use break and continue statements for loop control
5. Apply loops to solve real-world programming problems
6. Optimize loop performance and avoid common pitfalls

---

## Key Concepts

### 1. Introduction to Loops

#### What are Loops?
Loops are control structures that allow you to execute a block of code repeatedly based on a condition. They are essential for:
- Processing arrays and collections
- Repeating operations multiple times
- Generating patterns and sequences
- Automating repetitive tasks

#### Types of Loops in PHP:
1. **for loop** - When you know the number of iterations
2. **while loop** - When you check condition before execution
3. **do-while loop** - When you need at least one execution
4. **foreach loop** - For iterating through arrays and objects

### 2. The FOR Loop

#### Basic Syntax:
```php
for (initialization; condition; increment/decrement) {
    // Code to execute
}
```

#### Simple Examples:
```php
<?php
// Basic counting from 1 to 10
echo "<h3>Basic Counting:</h3>";
for ($i = 1; $i <= 10; $i++) {
    echo "Number: $i<br>";
}

// Counting backwards
echo "<h3>Countdown:</h3>";
for ($i = 10; $i >= 1; $i--) {
    echo "Countdown: $i<br>";
}

// Multiplication table
echo "<h3>Multiplication Table of 5:</h3>";
for ($i = 1; $i <= 10; $i++) {
    $result = 5 * $i;
    echo "5 × $i = $result<br>";
}

// Even numbers from 2 to 20
echo "<h3>Even Numbers:</h3>";
for ($i = 2; $i <= 20; $i += 2) {
    echo "$i ";
}
?>
```

#### Advanced FOR Loop Applications:
```php
<?php
// Generate HTML table with for loops
echo "<h3>Multiplication Table (1-10):</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>×</th>";

// Header row
for ($i = 1; $i <= 10; $i++) {
    echo "<th>$i</th>";
}
echo "</tr>";

// Table rows
for ($i = 1; $i <= 10; $i++) {
    echo "<tr><th>$i</th>";
    for ($j = 1; $j <= 10; $j++) {
        $product = $i * $j;
        echo "<td>$product</td>";
    }
    echo "</tr>";
}
echo "</table>";

// Generate a pattern
echo "<h3>Star Pattern:</h3>";
for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "* ";
    }
    echo "<br>";
}
?>
```

### 3. The WHILE Loop

#### Basic Syntax:
```php
while (condition) {
    // Code to execute
}
```

#### WHILE Loop Examples:
```php
<?php
// Basic while loop
echo "<h3>While Loop Counting:</h3>";
$count = 1;
while ($count <= 5) {
    echo "Iteration: $count<br>";
    $count++;
}

// Password verification simulation
echo "<h3>Password Verification Simulation:</h3>";
$attempts = 0;
$max_attempts = 3;
$correct_password = "secret123";
$user_input = "wrong"; // Simulate wrong password

while ($attempts < $max_attempts && $user_input !== $correct_password) {
    $attempts++;
    echo "Attempt $attempts: Password incorrect<br>";
    
    // Simulate different inputs
    if ($attempts == 3) {
        $user_input = "secret123"; // Correct on 3rd attempt
    }
}

if ($user_input === $correct_password) {
    echo "<span style='color: green;'>Access granted!</span><br>";
} else {
    echo "<span style='color: red;'>Account locked after $max_attempts attempts</span><br>";
}

// Sum of numbers until condition is met
echo "<h3>Sum Until Limit:</h3>";
$sum = 0;
$number = 1;
$limit = 50;

while ($sum < $limit) {
    $sum += $number;
    echo "Adding $number, current sum: $sum<br>";
    $number++;
}
echo "Final sum: $sum<br>";
?>
```

### 4. The DO-WHILE Loop

#### Basic Syntax:
```php
do {
    // Code to execute
} while (condition);
```

#### DO-WHILE Examples:
```php
<?php
// Menu system that runs at least once
echo "<h3>Menu System Simulation:</h3>";
$choice = 0;
$menu_shown = false;

do {
    if (!$menu_shown) {
        echo "=== MAIN MENU ===<br>";
        echo "1. View Students<br>";
        echo "2. Add Student<br>";
        echo "3. Edit Student<br>";
        echo "4. Delete Student<br>";
        echo "5. Exit<br>";
        echo "Enter your choice: ";
        $menu_shown = true;
    }
    
    // Simulate user input
    $choice = rand(1, 5);
    echo "<strong>$choice</strong><br>";
    
    switch ($choice) {
        case 1:
            echo "→ Displaying all students...<br>";
            break;
        case 2:
            echo "→ Adding new student...<br>";
            break;
        case 3:
            echo "→ Editing student information...<br>";
            break;
        case 4:
            echo "→ Deleting student record...<br>";
            break;
        case 5:
            echo "→ Exiting program. Goodbye!<br>";
            break;
        default:
            echo "→ Invalid choice. Please try again.<br>";
            $choice = 0; // Reset to continue loop
    }
    
    if ($choice != 5) {
        echo "<br>";
        $menu_shown = false; // Show menu again
    }
    
} while ($choice != 5);

// Input validation example
echo "<h3>Input Validation:</h3>";
$valid_input = false;
$input_value = "";

do {
    // Simulate different inputs
    static $attempt = 0;
    $attempt++;
    
    $test_inputs = ["abc", "-5", "0", "15", "101", "25"];
    $input_value = $test_inputs[$attempt - 1] ?? "25";
    
    echo "Input attempt $attempt: '$input_value' - ";
    
    if (is_numeric($input_value) && $input_value >= 1 && $input_value <= 100) {
        $valid_input = true;
        echo "<span style='color: green;'>Valid!</span><br>";
    } else {
        echo "<span style='color: red;'>Invalid! Please enter a number between 1 and 100.</span><br>";
    }
    
} while (!$valid_input && $attempt < 6);

if ($valid_input) {
    echo "Accepted value: $input_value<br>";
} else {
    echo "Maximum attempts reached.<br>";
}
?>
```

### 5. The FOREACH Loop

#### Basic Syntax:
```php
// For indexed arrays
foreach ($array as $value) {
    // Code using $value
}

// For associative arrays
foreach ($array as $key => $value) {
    // Code using $key and $value
}
```

#### FOREACH Loop Examples:
```php
<?php
// Simple array iteration
$fruits = ["apple", "banana", "orange", "grape", "mango"];
echo "<h3>Fruits List:</h3>";
foreach ($fruits as $fruit) {
    echo "• $fruit<br>";
}

// Associative array iteration
$student_grades = [
    "Mathematics" => 85,
    "Physics" => 92,
    "Chemistry" => 78,
    "Biology" => 88,
    "Computer Science" => 95
];

echo "<h3>Student Grade Report:</h3>";
$total_marks = 0;
$subject_count = 0;

foreach ($student_grades as $subject => $grade) {
    echo "$subject: $grade%<br>";
    $total_marks += $grade;
    $subject_count++;
}

$average = $total_marks / $subject_count;
echo "<strong>Average Grade: " . round($average, 2) . "%</strong><br>";

// Multi-dimensional array
$students = [
    [
        "name" => "Alice Johnson",
        "roll" => "2025001",
        "class" => "XII-A",
        "subjects" => ["Math" => 85, "Physics" => 90, "Chemistry" => 82]
    ],
    [
        "name" => "Bob Smith",
        "roll" => "2025002", 
        "class" => "XII-A",
        "subjects" => ["Math" => 78, "Physics" => 85, "Chemistry" => 79]
    ],
    [
        "name" => "Carol Davis",
        "roll" => "2025003",
        "class" => "XII-B", 
        "subjects" => ["Math" => 92, "Physics" => 88, "Chemistry" => 91]
    ]
];

echo "<h3>Complete Student Records:</h3>";
foreach ($students as $student) {
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<strong>{$student['name']}</strong> (Roll: {$student['roll']}) - Class: {$student['class']}<br>";
    
    $student_total = 0;
    $subject_count = count($student['subjects']);
    
    echo "Subjects: ";
    foreach ($student['subjects'] as $subject => $marks) {
        echo "$subject: $marks% ";
        $student_total += $marks;
    }
    
    $student_average = $student_total / $subject_count;
    echo "<br><em>Average: " . round($student_average, 2) . "%</em>";
    echo "</div>";
}
?>
```

### 6. Loop Control Statements

#### BREAK and CONTINUE:
```php
<?php
echo "<h3>Break Statement Example:</h3>";
// Find first number divisible by both 3 and 7
for ($i = 1; $i <= 100; $i++) {
    if ($i % 3 == 0 && $i % 7 == 0) {
        echo "First number divisible by both 3 and 7: $i<br>";
        break; // Exit the loop
    }
}

echo "<h3>Continue Statement Example:</h3>";
// Print odd numbers from 1 to 20
echo "Odd numbers from 1 to 20: ";
for ($i = 1; $i <= 20; $i++) {
    if ($i % 2 == 0) {
        continue; // Skip even numbers
    }
    echo "$i ";
}
echo "<br>";

echo "<h3>Nested Loop with Break:</h3>";
// Search for a specific value in 2D array
$matrix = [
    [1, 2, 3, 4],
    [5, 6, 7, 8], 
    [9, 10, 11, 12],
    [13, 14, 15, 16]
];

$search_value = 11;
$found = false;

for ($i = 0; $i < count($matrix); $i++) {
    for ($j = 0; $j < count($matrix[$i]); $j++) {
        echo "Checking position [$i][$j] = {$matrix[$i][$j]}<br>";
        if ($matrix[$i][$j] == $search_value) {
            echo "<strong>Found $search_value at position [$i][$j]!</strong><br>";
            $found = true;
            break 2; // Break out of both loops
        }
    }
}

if (!$found) {
    echo "Value $search_value not found in matrix.<br>";
}
?>
```

---

## Practical Activities

### Activity 1: Interactive Number Pattern Generator (45 minutes)

#### Create file: `pattern-generator.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Number Pattern Generator</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .pattern-container { background-color: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .pattern { font-family: monospace; font-size: 18px; line-height: 1.5; background-color: white; padding: 15px; border-radius: 5px; }
        .controls { background-color: #e9ecef; padding: 15px; margin: 10px 0; border-radius: 5px; }
        input[type="number"] { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .pattern-type { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🔢 Interactive Number Pattern Generator</h1>
    
    <form method="POST" action="">
        <div class="controls">
            <h3>Pattern Controls:</h3>
            <label>
                Pattern Size: 
                <input type="number" name="size" min="1" max="20" value="<?php echo $_POST['size'] ?? 5; ?>">
            </label>
            
            <div class="pattern-type">
                <strong>Select Pattern Type:</strong><br>
                <input type="radio" id="triangle" name="pattern" value="triangle" <?php echo (($_POST['pattern'] ?? 'triangle') == 'triangle') ? 'checked' : ''; ?>>
                <label for="triangle">Right Triangle</label><br>
                
                <input type="radio" id="pyramid" name="pattern" value="pyramid" <?php echo (($_POST['pattern'] ?? '') == 'pyramid') ? 'checked' : ''; ?>>
                <label for="pyramid">Pyramid</label><br>
                
                <input type="radio" id="diamond" name="pattern" value="diamond" <?php echo (($_POST['pattern'] ?? '') == 'diamond') ? 'checked' : ''; ?>>
                <label for="diamond">Diamond</label><br>
                
                <input type="radio" id="multiplication" name="pattern" value="multiplication" <?php echo (($_POST['pattern'] ?? '') == 'multiplication') ? 'checked' : ''; ?>>
                <label for="multiplication">Multiplication Table</label><br>
                
                <input type="radio" id="fibonacci" name="pattern" value="fibonacci" <?php echo (($_POST['pattern'] ?? '') == 'fibonacci') ? 'checked' : ''; ?>>
                <label for="fibonacci">Fibonacci Triangle</label>
            </div>
            
            <button type="submit">Generate Pattern</button>
        </div>
    </form>

    <?php
    if ($_POST) {
        $size = max(1, min(20, (int)($_POST['size'] ?? 5)));
        $pattern_type = $_POST['pattern'] ?? 'triangle';
        
        echo "<div class='pattern-container'>";
        echo "<h3>Generated Pattern:</h3>";
        echo "<div class='pattern'>";
        
        switch ($pattern_type) {
            case 'triangle':
                generateTriangle($size);
                break;
            case 'pyramid':
                generatePyramid($size);
                break;
            case 'diamond':
                generateDiamond($size);
                break;
            case 'multiplication':
                generateMultiplicationTable($size);
                break;
            case 'fibonacci':
                generateFibonacciTriangle($size);
                break;
        }
        
        echo "</div></div>";
    }
    
    // Pattern generation functions
    function generateTriangle($size) {
        echo "<strong>Right Triangle Pattern (Size: $size)</strong><br><br>";
        for ($i = 1; $i <= $size; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                echo sprintf("%3d ", $j);
            }
            echo "<br>";
        }
    }
    
    function generatePyramid($size) {
        echo "<strong>Pyramid Pattern (Size: $size)</strong><br><br>";
        for ($i = 1; $i <= $size; $i++) {
            // Leading spaces
            for ($j = 1; $j <= ($size - $i); $j++) {
                echo "&nbsp;&nbsp;&nbsp;";
            }
            
            // Ascending numbers
            for ($j = 1; $j <= $i; $j++) {
                echo sprintf("%2d ", $j);
            }
            
            // Descending numbers
            for ($j = $i - 1; $j >= 1; $j--) {
                echo sprintf("%2d ", $j);
            }
            
            echo "<br>";
        }
    }
    
    function generateDiamond($size) {
        echo "<strong>Diamond Pattern (Size: $size)</strong><br><br>";
        
        // Upper half (including middle)
        for ($i = 1; $i <= $size; $i++) {
            // Leading spaces
            for ($j = 1; $j <= ($size - $i); $j++) {
                echo "&nbsp;&nbsp;";
            }
            
            // Numbers
            for ($j = 1; $j <= $i; $j++) {
                echo "$j ";
            }
            for ($j = $i - 1; $j >= 1; $j--) {
                echo "$j ";
            }
            
            echo "<br>";
        }
        
        // Lower half
        for ($i = $size - 1; $i >= 1; $i--) {
            // Leading spaces
            for ($j = 1; $j <= ($size - $i); $j++) {
                echo "&nbsp;&nbsp;";
            }
            
            // Numbers
            for ($j = 1; $j <= $i; $j++) {
                echo "$j ";
            }
            for ($j = $i - 1; $j >= 1; $j--) {
                echo "$j ";
            }
            
            echo "<br>";
        }
    }
    
    function generateMultiplicationTable($size) {
        echo "<strong>Multiplication Table (${size}x${size})</strong><br><br>";
        
        // Header row
        echo "<span style='color: #007bff; font-weight: bold;'>";
        echo "&nbsp;&nbsp;&nbsp;&times;&nbsp;";
        for ($i = 1; $i <= $size; $i++) {
            echo sprintf("%4d", $i);
        }
        echo "</span><br>";
        
        // Separator line
        for ($i = 0; $i <= $size; $i++) {
            echo "----";
        }
        echo "<br>";
        
        // Table rows
        for ($i = 1; $i <= $size; $i++) {
            echo "<span style='color: #007bff; font-weight: bold;'>" . sprintf("%3d", $i) . "&nbsp;</span>|";
            for ($j = 1; $j <= $size; $j++) {
                $product = $i * $j;
                echo sprintf("%4d", $product);
            }
            echo "<br>";
        }
    }
    
    function generateFibonacciTriangle($size) {
        echo "<strong>Fibonacci Triangle (Size: $size)</strong><br><br>";
        
        // Generate Fibonacci sequence
        $fib = [0, 1];
        $needed_numbers = ($size * ($size + 1)) / 2; // Triangular number
        
        for ($i = 2; $i < $needed_numbers; $i++) {
            $fib[$i] = $fib[$i-1] + $fib[$i-2];
        }
        
        $index = 0;
        for ($i = 1; $i <= $size; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                echo sprintf("%8d ", $fib[$index]);
                $index++;
            }
            echo "<br>";
        }
    }
    ?>
</body>
</html>
```

### Activity 2: Student Grade Processing System (60 minutes)

#### Create file: `grade-processor.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Student Grade Processing System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; }
        .section { background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .grade-A { background-color: #d4edda !important; color: #155724; }
        .grade-B { background-color: #d1ecf1 !important; color: #0c5460; }
        .grade-C { background-color: #fff3cd !important; color: #856404; }
        .grade-D { background-color: #f8d7da !important; color: #721c24; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .stat-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; color: #007bff; }
        .chart-container { background: white; padding: 20px; border-radius: 8px; margin: 15px 0; }
    </style>
</head>
<body>
    <h1>🎓 Student Grade Processing System</h1>
    
    <?php
    // Sample student data with multiple subjects
    $students = [
        [
            "name" => "Alice Johnson",
            "roll" => "2025001",
            "class" => "XII-A",
            "subjects" => [
                "Mathematics" => 92,
                "Physics" => 88,
                "Chemistry" => 85,
                "Biology" => 90,
                "Computer Science" => 95,
                "English" => 87
            ]
        ],
        [
            "name" => "Bob Smith", 
            "roll" => "2025002",
            "class" => "XII-A",
            "subjects" => [
                "Mathematics" => 78,
                "Physics" => 82,
                "Chemistry" => 79,
                "Biology" => 85,
                "Computer Science" => 88,
                "English" => 81
            ]
        ],
        [
            "name" => "Carol Davis",
            "roll" => "2025003", 
            "class" => "XII-B",
            "subjects" => [
                "Mathematics" => 95,
                "Physics" => 93,
                "Chemistry" => 91,
                "Biology" => 89,
                "Computer Science" => 97,
                "English" => 92
            ]
        ],
        [
            "name" => "David Wilson",
            "roll" => "2025004",
            "class" => "XII-B", 
            "subjects" => [
                "Mathematics" => 72,
                "Physics" => 75,
                "Chemistry" => 68,
                "Biology" => 78,
                "Computer Science" => 82,
                "English" => 74
            ]
        ],
        [
            "name" => "Emma Brown",
            "roll" => "2025005",
            "class" => "XII-A",
            "subjects" => [
                "Mathematics" => 89,
                "Physics" => 91,
                "Chemistry" => 87,
                "Biology" => 85,
                "Computer Science" => 90,
                "English" => 88
            ]
        ]
    ];
    
    // Function to calculate grade based on percentage
    function getGrade($percentage) {
        if ($percentage >= 90) return 'A';
        elseif ($percentage >= 80) return 'B';
        elseif ($percentage >= 70) return 'C';
        elseif ($percentage >= 60) return 'D';
        else return 'F';
    }
    
    // Function to get grade class for styling
    function getGradeClass($grade) {
        switch($grade) {
            case 'A': return 'grade-A';
            case 'B': return 'grade-B'; 
            case 'C': return 'grade-C';
            default: return 'grade-D';
        }
    }
    
    // Process student data and calculate statistics
    $processed_students = [];
    $all_subjects = [];
    $class_totals = [];
    $subject_totals = [];
    
    foreach ($students as $student) {
        $total_marks = 0;
        $subject_count = count($student['subjects']);
        
        // Calculate student totals
        foreach ($student['subjects'] as $subject => $marks) {
            $total_marks += $marks;
            $all_subjects[$subject] = true;
            
            // Subject-wise totals
            if (!isset($subject_totals[$subject])) {
                $subject_totals[$subject] = ['total' => 0, 'count' => 0];
            }
            $subject_totals[$subject]['total'] += $marks;
            $subject_totals[$subject]['count']++;
        }
        
        $average = $total_marks / $subject_count;
        $grade = getGrade($average);
        
        $processed_students[] = [
            'original' => $student,
            'total' => $total_marks,
            'average' => $average,
            'grade' => $grade
        ];
        
        // Class-wise totals
        $class = $student['class'];
        if (!isset($class_totals[$class])) {
            $class_totals[$class] = ['students' => [], 'total' => 0];
        }
        $class_totals[$class]['students'][] = $average;
        $class_totals[$class]['total'] += $average;
    }
    
    $all_subjects = array_keys($all_subjects);
    ?>
    
    <!-- Overall Statistics -->
    <div class="section">
        <h2>📊 Overall Statistics</h2>
        <div class="stats-grid">
            <?php
            $total_students = count($processed_students);
            $overall_average = 0;
            $grade_distribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0];
            
            foreach ($processed_students as $student) {
                $overall_average += $student['average'];
                $grade_distribution[$student['grade']]++;
            }
            
            $overall_average /= $total_students;
            ?>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_students; ?></div>
                <div>Total Students</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($overall_average, 1); ?>%</div>
                <div>Overall Average</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo count($all_subjects); ?></div>
                <div>Subjects</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $grade_distribution['A']; ?></div>
                <div>A Grade Students</div>
            </div>
        </div>
    </div>
    
    <!-- Individual Student Reports -->
    <div class="section">
        <h2>👥 Individual Student Performance</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Roll No.</th>
                    <th>Class</th>
                    <?php foreach ($all_subjects as $subject): ?>
                        <th><?php echo $subject; ?></th>
                    <?php endforeach; ?>
                    <th>Total</th>
                    <th>Average</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($processed_students as $student_data): ?>
                    <?php 
                    $student = $student_data['original'];
                    $grade_class = getGradeClass($student_data['grade']);
                    ?>
                    <tr class="<?php echo $grade_class; ?>">
                        <td><strong><?php echo $student['name']; ?></strong></td>
                        <td><?php echo $student['roll']; ?></td>
                        <td><?php echo $student['class']; ?></td>
                        
                        <?php foreach ($all_subjects as $subject): ?>
                            <td>
                                <?php 
                                if (isset($student['subjects'][$subject])) {
                                    echo $student['subjects'][$subject] . '%';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                        
                        <td><strong><?php echo $student_data['total']; ?></strong></td>
                        <td><strong><?php echo number_format($student_data['average'], 1); ?>%</strong></td>
                        <td><strong><?php echo $student_data['grade']; ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Subject-wise Analysis -->
    <div class="section">
        <h2>📚 Subject-wise Performance Analysis</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Average Score</th>
                    <th>Highest Score</th>
                    <th>Lowest Score</th>
                    <th>Students Above 80%</th>
                    <th>Pass Rate (≥60%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_subjects as $subject): ?>
                    <?php
                    $subject_scores = [];
                    foreach ($students as $student) {
                        if (isset($student['subjects'][$subject])) {
                            $subject_scores[] = $student['subjects'][$subject];
                        }
                    }
                    
                    $subject_avg = array_sum($subject_scores) / count($subject_scores);
                    $highest = max($subject_scores);
                    $lowest = min($subject_scores);
                    
                    $above_80 = 0;
                    $passed = 0;
                    
                    foreach ($subject_scores as $score) {
                        if ($score >= 80) $above_80++;
                        if ($score >= 60) $passed++;
                    }
                    
                    $pass_rate = ($passed / count($subject_scores)) * 100;
                    ?>
                    <tr>
                        <td><strong><?php echo $subject; ?></strong></td>
                        <td><?php echo number_format($subject_avg, 1); ?>%</td>
                        <td><?php echo $highest; ?>%</td>
                        <td><?php echo $lowest; ?>%</td>
                        <td><?php echo $above_80; ?> / <?php echo count($subject_scores); ?></td>
                        <td><?php echo number_format($pass_rate, 1); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Class-wise Comparison -->
    <div class="section">
        <h2>🏫 Class-wise Performance Comparison</h2>
        <?php
        foreach ($class_totals as $class_name => $class_data) {
            $class_average = $class_data['total'] / count($class_data['students']);
            $class_students = $class_data['students'];
            
            echo "<div class='chart-container'>";
            echo "<h3>Class: $class_name</h3>";
            echo "<p><strong>Class Average:</strong> " . number_format($class_average, 1) . "%</p>";
            echo "<p><strong>Number of Students:</strong> " . count($class_students) . "</p>";
            
            // Grade distribution for this class
            $class_grades = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0];
            foreach ($class_students as $avg) {
                $class_grades[getGrade($avg)]++;
            }
            
            echo "<p><strong>Grade Distribution:</strong> ";
            foreach ($class_grades as $grade => $count) {
                if ($count > 0) {
                    echo "$grade: $count students | ";
                }
            }
            echo "</p>";
            
            // Performance trend
            sort($class_students);
            echo "<p><strong>Performance Range:</strong> " . 
                 number_format($class_students[0], 1) . "% - " . 
                 number_format($class_students[count($class_students)-1], 1) . "%</p>";
            
            echo "</div>";
        }
        ?>
    </div>
    
    <!-- Grade Distribution Chart -->
    <div class="section">
        <h2>📈 Overall Grade Distribution</h2>
        <div class="chart-container">
            <?php
            echo "<table style='width: auto; margin: 0 auto;'>";
            echo "<thead><tr><th>Grade</th><th>Count</th><th>Percentage</th><th>Visual</th></tr></thead>";
            echo "<tbody>";
            
            foreach ($grade_distribution as $grade => $count) {
                $percentage = ($count / $total_students) * 100;
                $bar_width = $percentage * 3; // Scale for visual representation
                
                echo "<tr>";
                echo "<td><strong>$grade</strong></td>";
                echo "<td>$count</td>";
                echo "<td>" . number_format($percentage, 1) . "%</td>";
                echo "<td>";
                if ($count > 0) {
                    echo "<div style='background-color: #007bff; height: 20px; width: {$bar_width}px; border-radius: 3px;'></div>";
                }
                echo "</td>";
                echo "</tr>";
            }
            
            echo "</tbody></table>";
            ?>
        </div>
    </div>
    
    <!-- Performance Insights -->
    <div class="section">
        <h2>💡 Performance Insights</h2>
        <?php
        // Find top performer
        $top_student = null;
        $highest_avg = 0;
        foreach ($processed_students as $student_data) {
            if ($student_data['average'] > $highest_avg) {
                $highest_avg = $student_data['average'];
                $top_student = $student_data['original'];
            }
        }
        
        // Find most challenging subject
        $lowest_subject_avg = 100;
        $most_challenging = "";
        foreach ($all_subjects as $subject) {
            $subject_scores = [];
            foreach ($students as $student) {
                if (isset($student['subjects'][$subject])) {
                    $subject_scores[] = $student['subjects'][$subject];
                }
            }
            $avg = array_sum($subject_scores) / count($subject_scores);
            if ($avg < $lowest_subject_avg) {
                $lowest_subject_avg = $avg;
                $most_challenging = $subject;
            }
        }
        
        echo "<ul>";
        echo "<li><strong>Top Performer:</strong> {$top_student['name']} with " . number_format($highest_avg, 1) . "% average</li>";
        echo "<li><strong>Most Challenging Subject:</strong> $most_challenging with " . number_format($lowest_subject_avg, 1) . "% average</li>";
        echo "<li><strong>Overall Class Performance:</strong> " . 
             ($overall_average >= 80 ? "Excellent" : 
             ($overall_average >= 70 ? "Good" : 
             ($overall_average >= 60 ? "Average" : "Needs Improvement"))) . "</li>";
        
        $students_needing_help = 0;
        foreach ($processed_students as $student_data) {
            if ($student_data['average'] < 70) {
                $students_needing_help++;
            }
        }
        
        if ($students_needing_help > 0) {
            echo "<li><strong>Students Needing Additional Support:</strong> $students_needing_help students scoring below 70%</li>";
        } else {
            echo "<li><strong>Great News:</strong> All students are performing well (70% and above)!</li>";
        }
        
        echo "</ul>";
        ?>
    </div>
</body>
</html>
```

---

## Exercises

### Exercise 1: Prime Number Generator (20 minutes)
Create `prime-generator.php` that:
- Generates prime numbers up to a given limit using loops
- Displays the generation process step by step
- Counts and displays total primes found
- Shows performance timing for the algorithm

### Exercise 2: Factorial Calculator (15 minutes)
Build `factorial-calculator.php` featuring:
- Calculate factorial using both for and while loops
- Display step-by-step calculation process
- Handle large numbers with appropriate formatting
- Compare iterative vs recursive approaches

### Exercise 3: Array Statistics Calculator (25 minutes)
Develop `array-statistics.php` that:
- Processes arrays of numbers using foreach loops
- Calculates mean, median, mode, and standard deviation
- Finds outliers and data distribution
- Generates visual representation of data

### Exercise 4: Shopping Cart Simulator (30 minutes)
Create `shopping-cart.php` with:
- Add/remove items using loops for cart management
- Calculate subtotals, taxes, and final amounts
- Apply discount rules based on quantity/total
- Generate detailed receipt with itemized listing

---

## Assessment

### Knowledge Check:
1. When would you use a for loop vs. a while loop?
2. What is the difference between while and do-while loops?
3. How do break and continue statements work in nested loops?
4. What are the advantages of foreach loops for array processing?
5. How can you optimize loop performance?

### Practical Assessment:
- [ ] Correctly implemented different types of loops
- [ ] Used appropriate loop control statements
- [ ] Applied nested loops for complex patterns
- [ ] Processed arrays efficiently with foreach
- [ ] Demonstrated understanding of loop optimization

---

## Homework Assignment

### **Complete Inventory Management System**
Create a comprehensive system (`inventory-management.php`) using various loop types:

#### **Requirements:**
1. **Product Management:**
   - Use for loops to display product catalogs
   - Implement while loops for user input validation
   - Apply foreach loops for cart processing

2. **Inventory Operations:**
   - Stock level monitoring with loop-based alerts
   - Batch updates using nested loops
   - Report generation with statistical calculations

3. **Sales Processing:**
   - Transaction processing with do-while menus
   - Receipt generation using pattern loops
   - Sales analytics with data aggregation loops

4. **Advanced Features:**
   - Search functionality with optimized loops
   - Sorting algorithms using loop comparisons
   - Data export with formatted output loops

**Due:** Next class session  
**Assessment:** Loop variety, efficiency, code organization, practical application

---

## Next Lesson Preview
**Lesson 6: PHP Functions & Scope**
- Function definition and parameters
- Return values and variable scope
- Built-in vs user-defined functions
- Advanced function concepts and best practices

---

*Loops are fundamental building blocks that enable powerful automation and data processing. Master these concepts to create efficient, scalable applications.*
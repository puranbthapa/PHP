# Lesson 3: PHP Operators & Expressions
**Course:** PHP Web Development - Class XII  
**Duration:** 2 hours  
**Prerequisites:** Lessons 1-2 completed, understanding of variables and data types

---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Use arithmetic operators for mathematical calculations
2. Apply comparison operators for decision making
3. Implement logical operators for complex conditions
4. Understand operator precedence and associativity
5. Build complex expressions combining multiple operators

---

## Key Concepts

### 1. Arithmetic Operators

#### Basic Operations:
```php
<?php
    $a = 10;
    $b = 3;
    
    echo "Addition: " . ($a + $b);        // 13
    echo "Subtraction: " . ($a - $b);     // 7
    echo "Multiplication: " . ($a * $b);   // 30
    echo "Division: " . ($a / $b);        // 3.333...
    echo "Modulus: " . ($a % $b);         // 1
    echo "Exponentiation: " . ($a ** $b); // 1000
?>
```

#### Practical Examples:
```php
<?php
    // Grade calculation
    $total_marks = 450;
    $max_marks = 500;
    $percentage = ($total_marks / $max_marks) * 100;
    echo "Percentage: " . round($percentage, 2) . "%";
    
    // Time calculations
    $seconds = 3665;
    $hours = intval($seconds / 3600);
    $minutes = intval(($seconds % 3600) / 60);
    $remaining_seconds = $seconds % 60;
    echo "Time: {$hours}h {$minutes}m {$remaining_seconds}s";
?>
```

### 2. Assignment Operators

#### Basic and Compound Assignment:
```php
<?php
    $x = 10;          // Basic assignment
    
    $x += 5;          // $x = $x + 5; Result: 15
    $x -= 3;          // $x = $x - 3; Result: 12
    $x *= 2;          // $x = $x * 2; Result: 24
    $x /= 4;          // $x = $x / 4; Result: 6
    $x %= 5;          // $x = $x % 5; Result: 1
    
    // String concatenation assignment
    $name = "John";
    $name .= " Doe";  // $name = $name . " Doe"; Result: "John Doe"
?>
```

### 3. Comparison Operators

#### Equality and Relational Operators:
```php
<?php
    $a = 5;
    $b = "5";
    $c = 10;
    
    // Equality comparisons
    var_dump($a == $b);   // true (loose comparison)
    var_dump($a === $b);  // false (strict comparison)
    var_dump($a != $c);   // true
    var_dump($a !== $b);  // true (strict inequality)
    
    // Relational comparisons
    var_dump($a < $c);    // true
    var_dump($a <= $b);   // true
    var_dump($c > $a);    // true
    var_dump($c >= $a);   // true
    
    // Spaceship operator (PHP 7+)
    echo $a <=> $c;       // -1 (less than)
    echo $c <=> $a;       // 1 (greater than)
    echo $a <=> $b;       // 0 (equal)
?>
```

### 4. Logical Operators

#### Boolean Logic:
```php
<?php
    $age = 17;
    $has_license = true;
    $has_permission = false;
    
    // AND operator
    if ($age >= 16 && $has_license) {
        echo "Can drive";
    }
    
    // OR operator  
    if ($has_license || $has_permission) {
        echo "Some driving privilege";
    }
    
    // NOT operator
    if (!$has_permission) {
        echo "No permission granted";
    }
    
    // XOR operator (exclusive or)
    if ($has_license xor $has_permission) {
        echo "Has exactly one privilege";
    }
?>
```

### 5. Increment/Decrement Operators

#### Pre and Post Operations:
```php
<?php
    $counter = 5;
    
    echo ++$counter;  // Pre-increment: increment first, then use (6)
    echo $counter++;  // Post-increment: use first, then increment (6, then becomes 7)
    echo --$counter;  // Pre-decrement: decrement first, then use (6)
    echo $counter--;  // Post-decrement: use first, then decrement (6, then becomes 5)
    
    // Practical example: Loop counter
    for ($i = 1; $i <= 5; $i++) {
        echo "Iteration: " . $i . "<br>";
    }
?>
```

---

## Practical Activities

### Activity 1: Calculator Application (30 minutes)

#### Create file: `calculator.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>PHP Calculator</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .calculator { background-color: #f4f4f4; padding: 20px; border-radius: 10px; }
        .result { background-color: #e8f5e8; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .operation { margin: 10px 0; padding: 10px; background-color: white; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="calculator">
        <h1>PHP Calculator Demo</h1>
        
        <?php
            // Input values
            $num1 = 25;
            $num2 = 7;
            
            echo "<h3>Input Numbers:</h3>";
            echo "<p>First Number: <strong>$num1</strong></p>";
            echo "<p>Second Number: <strong>$num2</strong></p>";
            
            echo "<h3>Arithmetic Operations:</h3>";
            
            // Addition
            $addition = $num1 + $num2;
            echo "<div class='operation'>";
            echo "<strong>Addition:</strong> $num1 + $num2 = <span class='result'>$addition</span>";
            echo "</div>";
            
            // Subtraction
            $subtraction = $num1 - $num2;
            echo "<div class='operation'>";
            echo "<strong>Subtraction:</strong> $num1 - $num2 = <span class='result'>$subtraction</span>";
            echo "</div>";
            
            // Multiplication
            $multiplication = $num1 * $num2;
            echo "<div class='operation'>";
            echo "<strong>Multiplication:</strong> $num1 × $num2 = <span class='result'>$multiplication</span>";
            echo "</div>";
            
            // Division
            if ($num2 != 0) {
                $division = $num1 / $num2;
                echo "<div class='operation'>";
                echo "<strong>Division:</strong> $num1 ÷ $num2 = <span class='result'>" . round($division, 2) . "</span>";
                echo "</div>";
            } else {
                echo "<div class='operation'>";
                echo "<strong>Division:</strong> Cannot divide by zero!";
                echo "</div>";
            }
            
            // Modulus
            $modulus = $num1 % $num2;
            echo "<div class='operation'>";
            echo "<strong>Modulus:</strong> $num1 % $num2 = <span class='result'>$modulus</span>";
            echo "</div>";
            
            // Exponentiation
            $power = $num1 ** 2;
            echo "<div class='operation'>";
            echo "<strong>Power:</strong> $num1² = <span class='result'>$power</span>";
            echo "</div>";
            
            echo "<h3>Comparison Results:</h3>";
            
            // Comparisons
            echo "<div class='operation'>";
            echo "<strong>Equal:</strong> $num1 == $num2 is " . ($num1 == $num2 ? 'true' : 'false');
            echo "</div>";
            
            echo "<div class='operation'>";
            echo "<strong>Greater than:</strong> $num1 > $num2 is " . ($num1 > $num2 ? 'true' : 'false');
            echo "</div>";
            
            echo "<div class='operation'>";
            echo "<strong>Less than:</strong> $num1 < $num2 is " . ($num1 < $num2 ? 'true' : 'false');
            echo "</div>";
            
            // Compound operations
            echo "<h3>Compound Operations:</h3>";
            
            $compound_result = ($num1 + $num2) * 2 - ($num1 % $num2);
            echo "<div class='operation'>";
            echo "<strong>Complex:</strong> ($num1 + $num2) × 2 - ($num1 % $num2) = <span class='result'>$compound_result</span>";
            echo "</div>";
        ?>
    </div>
</body>
</html>
```

### Activity 2: Grade Evaluation System (25 minutes)

#### Create file: `grade-system.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Student Grade Evaluation</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; }
        .student { background-color: #f9f9f9; margin: 15px 0; padding: 20px; border-radius: 8px; }
        .grade-a { border-left: 5px solid #28a745; }
        .grade-b { border-left: 5px solid #17a2b8; }
        .grade-c { border-left: 5px solid #ffc107; }
        .grade-d { border-left: 5px solid #fd7e14; }
        .grade-f { border-left: 5px solid #dc3545; }
        .stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Student Grade Evaluation System</h1>
    
    <?php
        // Student data with marks in 5 subjects
        $students = [
            ["name" => "Rahul Sharma", "marks" => [85, 92, 78, 88, 90]],
            ["name" => "Priya Patel", "marks" => [95, 89, 94, 96, 92]],
            ["name" => "Arjun Singh", "marks" => [72, 75, 70, 78, 74]],
            ["name" => "Sneha Gupta", "marks" => [88, 85, 91, 87, 89]],
            ["name" => "Vikash Kumar", "marks" => [65, 68, 62, 70, 67]]
        ];
        
        $subjects = ["Mathematics", "Physics", "Chemistry", "English", "Computer Science"];
        $total_students = count($students);
        $class_total_marks = 0;
        
        foreach ($students as $student) {
            $name = $student["name"];
            $marks = $student["marks"];
            
            // Calculate totals and percentage
            $total_marks = array_sum($marks);
            $max_marks = count($marks) * 100;
            $percentage = ($total_marks / $max_marks) * 100;
            $class_total_marks += $total_marks;
            
            // Determine grade using comparison operators
            if ($percentage >= 90) {
                $grade = "A+";
                $grade_class = "grade-a";
            } elseif ($percentage >= 80) {
                $grade = "A";
                $grade_class = "grade-a";
            } elseif ($percentage >= 70) {
                $grade = "B";
                $grade_class = "grade-b";
            } elseif ($percentage >= 60) {
                $grade = "C";
                $grade_class = "grade-c";
            } elseif ($percentage >= 50) {
                $grade = "D";
                $grade_class = "grade-d";
            } else {
                $grade = "F";
                $grade_class = "grade-f";
            }
            
            // Display student information
            echo "<div class='student $grade_class'>";
            echo "<h3>$name</h3>";
            echo "<p><strong>Subject-wise Marks:</strong></p>";
            echo "<ul>";
            for ($i = 0; $i < count($subjects); $i++) {
                echo "<li>{$subjects[$i]}: {$marks[$i]}/100</li>";
            }
            echo "</ul>";
            echo "<p><strong>Total Marks:</strong> $total_marks/$max_marks</p>";
            echo "<p><strong>Percentage:</strong> " . round($percentage, 2) . "%</p>";
            echo "<p><strong>Grade:</strong> <span style='font-size: 20px; font-weight: bold;'>$grade</span></p>";
            
            // Additional analysis using logical operators
            $has_failed_subject = false;
            $all_above_average = true;
            
            foreach ($marks as $mark) {
                if ($mark < 40) {
                    $has_failed_subject = true;
                }
                if ($mark < 75) {
                    $all_above_average = false;
                }
            }
            
            echo "<p><strong>Analysis:</strong> ";
            if ($has_failed_subject) {
                echo "<span style='color: red;'>Has failing grades in some subjects</span>";
            } elseif ($all_above_average) {
                echo "<span style='color: green;'>Excellent performance in all subjects</span>";
            } else {
                echo "<span style='color: blue;'>Good overall performance</span>";
            }
            echo "</p>";
            echo "</div>";
        }
        
        // Class statistics
        $class_average = ($class_total_marks / ($total_students * 500)) * 100;
        
        echo "<div class='stats'>";
        echo "<h3>Class Statistics</h3>";
        echo "<p><strong>Total Students:</strong> $total_students</p>";
        echo "<p><strong>Class Average:</strong> " . round($class_average, 2) . "%</p>";
        
        // Count grades
        $grade_counts = ["A+" => 0, "A" => 0, "B" => 0, "C" => 0, "D" => 0, "F" => 0];
        
        foreach ($students as $student) {
            $total = array_sum($student["marks"]);
            $percentage = ($total / 500) * 100;
            
            if ($percentage >= 90) $grade_counts["A+"]++;
            elseif ($percentage >= 80) $grade_counts["A"]++;
            elseif ($percentage >= 70) $grade_counts["B"]++;
            elseif ($percentage >= 60) $grade_counts["C"]++;
            elseif ($percentage >= 50) $grade_counts["D"]++;
            else $grade_counts["F"]++;
        }
        
        echo "<p><strong>Grade Distribution:</strong></p>";
        echo "<ul>";
        foreach ($grade_counts as $grade => $count) {
            $percentage_of_class = ($count / $total_students) * 100;
            echo "<li>Grade $grade: $count students (" . round($percentage_of_class, 1) . "%)</li>";
        }
        echo "</ul>";
        echo "</div>";
    ?>
</body>
</html>
```

### Activity 3: Operator Precedence Demonstration (20 minutes)

#### Create file: `precedence-demo.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Operator Precedence in PHP</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; }
        .example { background-color: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007bff; }
        .code { font-family: 'Courier New', monospace; background-color: #e9ecef; padding: 5px; border-radius: 3px; }
        .result { font-weight: bold; color: #28a745; }
    </style>
</head>
<body>
    <h1>PHP Operator Precedence Examples</h1>
    
    <?php
        echo "<h2>1. Arithmetic Precedence</h2>";
        
        $a = 2;
        $b = 3;
        $c = 4;
        
        echo "<div class='example'>";
        echo "<p>Given: \$a = $a, \$b = $b, \$c = $c</p>";
        
        $result1 = $a + $b * $c;
        echo "<p class='code'>\$a + \$b * \$c = $a + $b * $c = <span class='result'>$result1</span></p>";
        echo "<p>Explanation: Multiplication has higher precedence than addition</p>";
        
        $result2 = ($a + $b) * $c;
        echo "<p class='code'>(\$a + \$b) * \$c = ($a + $b) * $c = <span class='result'>$result2</span></p>";
        echo "<p>Explanation: Parentheses override precedence</p>";
        echo "</div>";
        
        echo "<h2>2. Comparison and Logical Precedence</h2>";
        
        $x = 5;
        $y = 10;
        $z = 15;
        
        echo "<div class='example'>";
        echo "<p>Given: \$x = $x, \$y = $y, \$z = $z</p>";
        
        $result3 = $x < $y && $y < $z;
        echo "<p class='code'>\$x < \$y && \$y < \$z = $x < $y && $y < $z = <span class='result'>" . ($result3 ? 'true' : 'false') . "</span></p>";
        echo "<p>Explanation: Comparison operators are evaluated before logical operators</p>";
        
        $result4 = $x < $y || $y > $z && $z > $x;
        echo "<p class='code'>\$x < \$y || \$y > \$z && \$z > \$x = <span class='result'>" . ($result4 ? 'true' : 'false') . "</span></p>";
        echo "<p>Explanation: && has higher precedence than ||</p>";
        echo "</div>";
        
        echo "<h2>3. Assignment Precedence</h2>";
        
        echo "<div class='example'>";
        $m = 5;
        $n = $m += 3;
        echo "<p class='code'>\$m = 5; \$n = \$m += 3;</p>";
        echo "<p>Result: \$m = <span class='result'>$m</span>, \$n = <span class='result'>$n</span></p>";
        echo "<p>Explanation: Assignment is right-associative</p>";
        echo "</div>";
        
        echo "<h2>4. Complex Expression Examples</h2>";
        
        $grade1 = 85;
        $grade2 = 92;
        $grade3 = 78;
        $attendance = 95;
        
        echo "<div class='example'>";
        echo "<p>Student Evaluation: Grade1=$grade1, Grade2=$grade2, Grade3=$grade3, Attendance=$attendance%</p>";
        
        // Complex condition
        $average = ($grade1 + $grade2 + $grade3) / 3;
        $is_honors = $average >= 85 && $attendance >= 90 && $grade1 >= 80 && $grade2 >= 80 && $grade3 >= 80;
        
        echo "<p class='code'>Average = ($grade1 + $grade2 + $grade3) / 3 = <span class='result'>" . round($average, 2) . "</span></p>";
        echo "<p class='code'>Honors Student = (average >= 85 && attendance >= 90 && all grades >= 80) = <span class='result'>" . ($is_honors ? 'YES' : 'NO') . "</span></p>";
        echo "</div>";
        
        echo "<h2>5. Increment/Decrement Precedence</h2>";
        
        echo "<div class='example'>";
        $counter = 5;
        echo "<p>Initial: \$counter = $counter</p>";
        
        $result5 = ++$counter * 2;
        echo "<p class='code'>++\$counter * 2 = <span class='result'>$result5</span> (counter is now $counter)</p>";
        
        $counter = 5; // Reset
        $result6 = $counter++ * 2;
        echo "<p class='code'>\$counter++ * 2 = <span class='result'>$result6</span> (counter is now $counter)</p>";
        echo "</div>";
        
        echo "<h2>Operator Precedence Table (Highest to Lowest)</h2>";
        echo "<div class='example'>";
        echo "<ol>";
        echo "<li>() [] -> :: (Parentheses, Array access, Object access)</li>";
        echo "<li>++ -- (Increment/Decrement)</li>";
        echo "<li>** (Exponentiation)</li>";
        echo "<li>* / % (Multiplication, Division, Modulus)</li>";
        echo "<li>+ - . (Addition, Subtraction, String concatenation)</li>";
        echo "<li>< <= > >= (Comparison)</li>";
        echo "<li>== != === !== <=> (Equality)</li>";
        echo "<li>&& (Logical AND)</li>";
        echo "<li>|| (Logical OR)</li>";
        echo "<li>= += -= *= /= %= .= (Assignment)</li>";
        echo "</ol>";
        echo "</div>";
    ?>
</body>
</html>
```

---

## Exercises

### Exercise 1: BMI Calculator (20 minutes)
Create `bmi-calculator.php` that:
- Takes height (in meters) and weight (in kg) as variables
- Calculates BMI using the formula: weight / (height²)
- Categorizes BMI using comparison operators:
  - Underweight: BMI < 18.5
  - Normal: 18.5 ≤ BMI < 25
  - Overweight: 25 ≤ BMI < 30
  - Obese: BMI ≥ 30
- Displays results with appropriate health recommendations

### Exercise 2: Time Converter (15 minutes)
Build `time-converter.php` that:
- Converts seconds to hours, minutes, and remaining seconds
- Converts minutes to hours and remaining minutes
- Handles leap year calculations
- Uses modulus operator for all conversions

### Exercise 3: Shopping Cart Calculator (25 minutes)
Create `shopping-cart.php` featuring:
- Multiple products with prices and quantities
- Calculate subtotal for each item
- Apply discount based on total amount:
  - 5% discount if total > ₹1000
  - 10% discount if total > ₹2500
  - 15% discount if total > ₹5000
- Calculate tax (18% GST)
- Display final bill with all calculations

### Exercise 4: Student Performance Analyzer (30 minutes)
Develop `performance-analyzer.php` that:
- Analyzes 10 students with 5 subject marks each
- Uses logical operators to determine:
  - Honor roll students (all subjects > 85 AND average > 90)
  - At-risk students (any subject < 50 OR average < 60)
  - Consistent performers (all subjects within 10 points of average)
- Calculate class statistics and grade distribution

---

## Common Operator Mistakes

### 1. Confusion between = and ==
```php
// Wrong - Assignment instead of comparison
if ($grade = 90) {  // Always true, assigns 90 to $grade
    echo "Excellent!";
}

// Correct
if ($grade == 90) {  // Compares $grade with 90
    echo "Excellent!";
}
```

### 2. Loose vs Strict Comparison
```php
$a = 5;
$b = "5";

var_dump($a == $b);   // true (type conversion)
var_dump($a === $b);  // false (no type conversion)
```

### 3. Operator Precedence Issues
```php
// Problematic
$result = $a + $b * $c;  // Multiplication first

// Clear intent
$result = ($a + $b) * $c;  // Addition first
```

---

## Assessment

### Practical Assessment Checklist:
- [ ] Used arithmetic operators correctly
- [ ] Applied comparison operators for decision making
- [ ] Implemented logical operators in complex conditions
- [ ] Demonstrated understanding of operator precedence
- [ ] Created expressions with proper parentheses usage
- [ ] Handled different data types in operations

### Knowledge Check Questions:
1. What's the difference between `==` and `===` operators?
2. Which operator has higher precedence: `&&` or `||`?
3. What does the modulus operator (%) return?
4. Explain the difference between `++$x` and `$x++`
5. How do you ensure correct order in complex expressions?

---

## Homework Assignment

### **Scientific Calculator Project**
Create a comprehensive calculator application (`scientific-calculator.php`) with:

#### **Basic Operations:**
- Addition, subtraction, multiplication, division
- Modulus and exponentiation
- Square root and factorial functions

#### **Advanced Features:**
- Percentage calculations
- Compound interest calculator
- Area and perimeter calculations for basic shapes
- Temperature conversions (Celsius, Fahrenheit, Kelvin)

#### **Input Validation:**
- Check for division by zero
- Validate numeric inputs
- Handle negative numbers appropriately

#### **User Interface:**
- Clean, organized layout
- Display formulas used
- Show step-by-step calculations
- Include example calculations

**Due:** Next class session  
**Format:** Single PHP file with embedded CSS
**Bonus:** Add trigonometric functions (sin, cos, tan)

---

## Next Lesson Preview
**Lesson 4: Control Structures - Conditional Statements**
- if, else, elseif statements in detail
- switch-case for multiple conditions
- Ternary operator for concise conditions
- Nested conditionals and best practices

---

*This lesson establishes the foundation for mathematical operations and logical decision-making in PHP, essential skills for building dynamic web applications.*
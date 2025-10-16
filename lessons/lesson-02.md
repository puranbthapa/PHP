# Lesson 2: PHP Syntax & Variables
**Course:** PHP Web Development - Class XII  
**Duration:** 2.5 hours  
**Prerequisites:** Lesson 1 completed, XAMPP installed

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Follow PHP syntax rules and conventions
2. Create and manipulate variables correctly
3. Understand different PHP data types
4. Use constants and global variables
5. Apply proper naming conventions

---

## Key Concepts

### 1. PHP Syntax Rules

#### Basic Syntax:
```php
<?php
    // PHP code goes here
    // Every statement ends with semicolon
    echo "Hello World";
?>
```

#### Important Rules:
- PHP is **case-sensitive** for variables
- PHP is **case-insensitive** for keywords, functions, and class names
- Variable names must start with `$` symbol
- Statements end with semicolon `;`
- Whitespace is generally ignored

### 2. Variables in PHP

#### Variable Declaration:
```php
<?php
    // Variables start with $ symbol
    $student_name = "Alice";
    $age = 17;
    $height = 5.6;
    $is_present = true;
    
    // Variable names are case-sensitive
    $name = "John";
    $Name = "Jane";  // Different variable
    $NAME = "Jack";  // Another different variable
?>
```

#### Variable Naming Rules:
- Must start with letter or underscore
- Can contain letters, numbers, and underscores
- Cannot start with a number
- Cannot contain spaces or special characters
- Case-sensitive

```php
<?php
    // Valid variable names:
    $name = "Valid";
    $student_age = 17;
    $_private = "Valid";
    $var123 = "Valid";
    
    // Invalid variable names:
    // $2name = "Invalid";     // Starts with number
    // $student-name = "Invalid"; // Contains hyphen
    // $student name = "Invalid"; // Contains space
?>
```

### 3. PHP Data Types

#### 1. String
```php
<?php
    $greeting = "Hello World";
    $name = 'John Doe';
    
    // String concatenation
    $full_message = $greeting . " " . $name;
    echo $full_message; // Output: Hello World John Doe
?>
```

#### 2. Integer
```php
<?php
    $age = 17;
    $year = 2025;
    $negative = -50;
    
    // Integer operations
    $sum = $age + 5;
    echo $sum; // Output: 22
?>
```

#### 3. Float (Double)
```php
<?php
    $height = 5.8;
    $weight = 65.5;
    $pi = 3.14159;
    
    // Float calculations
    $bmi = $weight / ($height * $height);
    echo round($bmi, 2); // Round to 2 decimal places
?>
```

#### 4. Boolean
```php
<?php
    $is_student = true;
    $is_absent = false;
    
    // Boolean in conditions
    if ($is_student) {
        echo "Welcome student!";
    }
?>
```

#### 5. Array (Introduction)
```php
<?php
    // Indexed array
    $subjects = array("PHP", "MySQL", "HTML", "CSS");
    
    // Alternative syntax
    $grades = ["A", "B+", "A-", "B"];
    
    echo $subjects[0]; // Output: PHP
?>
```

---

## Practical Activities

### Activity 1: Variable Practice (30 minutes)

#### Create file: `variables-demo.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>PHP Variables Demo</title>
</head>
<body>
    <h1>Student Information System</h1>
    
    <?php
        // Student information variables
        $student_name = "Rahul Sharma";
        $roll_number = 15;
        $class = "XII-B";
        $percentage = 85.5;
        $is_scholarship = true;
        
        // Display information
        echo "<h2>Student Details:</h2>";
        echo "<p><strong>Name:</strong> " . $student_name . "</p>";
        echo "<p><strong>Roll Number:</strong> " . $roll_number . "</p>";
        echo "<p><strong>Class:</strong> " . $class . "</p>";
        echo "<p><strong>Percentage:</strong> " . $percentage . "%</p>";
        
        // Conditional display
        if ($is_scholarship) {
            echo "<p><strong>Status:</strong> Scholarship Student</p>";
        }
        
        // Calculate and display grade
        if ($percentage >= 90) {
            $grade = "A+";
        } elseif ($percentage >= 80) {
            $grade = "A";
        } elseif ($percentage >= 70) {
            $grade = "B";
        } else {
            $grade = "C";
        }
        
        echo "<p><strong>Grade:</strong> " . $grade . "</p>";
    ?>
</body>
</html>
```

### Activity 2: Data Type Exploration (25 minutes)

#### Create file: `data-types.php`
```php
<?php
    echo "<h2>PHP Data Types Demonstration</h2>";
    
    // String examples
    $message1 = "Hello from PHP!";
    $message2 = 'Single quotes work too!';
    echo "<h3>Strings:</h3>";
    echo "<p>Double quotes: " . $message1 . "</p>";
    echo "<p>Single quotes: " . $message2 . "</p>";
    
    // Integer examples
    $current_year = 2025;
    $birth_year = 2007;
    $age = $current_year - $birth_year;
    echo "<h3>Integers:</h3>";
    echo "<p>Current Year: " . $current_year . "</p>";
    echo "<p>Birth Year: " . $birth_year . "</p>";
    echo "<p>Calculated Age: " . $age . "</p>";
    
    // Float examples
    $price = 99.99;
    $tax_rate = 0.18;
    $total = $price + ($price * $tax_rate);
    echo "<h3>Floats:</h3>";
    echo "<p>Price: ₹" . $price . "</p>";
    echo "<p>Tax Rate: " . ($tax_rate * 100) . "%</p>";
    echo "<p>Total: ₹" . round($total, 2) . "</p>";
    
    // Boolean examples
    $is_weekend = false;
    $has_homework = true;
    echo "<h3>Booleans:</h3>";
    echo "<p>Is Weekend: " . ($is_weekend ? "Yes" : "No") . "</p>";
    echo "<p>Has Homework: " . ($has_homework ? "Yes" : "No") . "</p>";
    
    // Check data types
    echo "<h3>Data Type Checking:</h3>";
    echo "<p>Type of \$message1: " . gettype($message1) . "</p>";
    echo "<p>Type of \$age: " . gettype($age) . "</p>";
    echo "<p>Type of \$price: " . gettype($price) . "</p>";
    echo "<p>Type of \$is_weekend: " . gettype($is_weekend) . "</p>";
?>
```

### Activity 3: Constants and Scope (20 minutes)

#### Create file: `constants-demo.php`
```php
<?php
    // Define constants
    define("SCHOOL_NAME", "ABC Senior Secondary School");
    define("CURRENT_SESSION", "2024-25");
    define("PI", 3.14159);
    
    // Constants cannot be changed
    echo "<h2>PHP Constants</h2>";
    echo "<p>School Name: " . SCHOOL_NAME . "</p>";
    echo "<p>Current Session: " . CURRENT_SESSION . "</p>";
    echo "<p>Value of PI: " . PI . "</p>";
    
    // Global variables
    $global_message = "I am accessible everywhere";
    
    function display_info() {
        // Using global keyword
        global $global_message;
        
        echo "<h3>Inside Function:</h3>";
        echo "<p>Global message: " . $global_message . "</p>";
        echo "<p>School Name: " . SCHOOL_NAME . "</p>";
        
        // Local variable
        $local_message = "I am only in this function";
        echo "<p>Local message: " . $local_message . "</p>";
    }
    
    display_info();
    
    echo "<h3>Outside Function:</h3>";
    echo "<p>Global message: " . $global_message . "</p>";
    // echo $local_message; // This would cause an error
?>
```

---

## Variable Operations and Functions

### String Operations:
```php
<?php
    $first_name = "John";
    $last_name = "Doe";
    
    // Concatenation
    $full_name = $first_name . " " . $last_name;
    
    // String length
    echo "Length of name: " . strlen($full_name);
    
    // String functions
    echo "Uppercase: " . strtoupper($full_name);
    echo "Lowercase: " . strtolower($full_name);
    echo "First 3 characters: " . substr($full_name, 0, 3);
?>
```

### Variable Testing Functions:
```php
<?php
    $test_var = "Hello";
    
    // Check if variable exists
    if (isset($test_var)) {
        echo "Variable exists";
    }
    
    // Check if variable is empty
    if (!empty($test_var)) {
        echo "Variable is not empty";
    }
    
    // Check data types
    if (is_string($test_var)) {
        echo "It's a string";
    }
    
    if (is_numeric("123")) {
        echo "123 is numeric";
    }
?>
```

---

## Exercises

### Exercise 1: Personal Information Card (20 minutes)
Create `personal-info.php` that stores and displays:
- Your full name
- Age (calculate from birth year)
- Height in feet and inches
- Favorite subjects (at least 3)
- Whether you have a computer at home

Format the output as an attractive information card.

### Exercise 2: Simple Calculator (25 minutes)
Create `calculator.php` that:
- Defines two numbers as variables
- Performs all basic operations (+, -, *, /)
- Displays results in a formatted table
- Includes proper error handling for division by zero

### Exercise 3: Grade Calculator (20 minutes)
Create `grade-system.php` that:
- Takes marks in 5 subjects
- Calculates total and percentage
- Determines grade based on percentage
- Displays all information in organized format

**Grading Scale:**
- 90-100%: A+
- 80-89%: A
- 70-79%: B+
- 60-69%: B
- 50-59%: C
- Below 50%: F

---

## Common Mistakes and Debugging

### 1. Forgetting Dollar Sign:
```php
// Wrong
name = "John";

// Correct
$name = "John";
```

### 2. Case Sensitivity Issues:
```php
$studentName = "Alice";
echo $studentname; // Error! Variable not defined
```

### 3. Missing Semicolons:
```php
// Wrong
echo "Hello World"
echo "Second line";

// Correct
echo "Hello World";
echo "Second line";
```

### 4. Incorrect Concatenation:
```php
// Wrong
echo "Hello" + "World";

// Correct
echo "Hello" . "World";
```

---

## Assessment

### Knowledge Check:
1. What symbol must all PHP variables start with?
2. Are PHP variable names case-sensitive?
3. Which data type would you use to store a student's grade percentage?
4. How do you concatenate strings in PHP?
5. What's the difference between a variable and a constant?

### Practical Assessment Checklist:
- [ ] Created variables with proper naming conventions
- [ ] Used different data types correctly
- [ ] Demonstrated string concatenation
- [ ] Applied variable scope concepts
- [ ] Completed all exercises successfully

---

## Homework Assignment

### Student Management System Variables
Create a PHP file `student-database.php` that demonstrates a simple student record system:

1. **Create variables for 3 students:**
   - Name, roll number, age, class, percentage
   - Use arrays to group related information

2. **Display Features:**
   - Student information cards
   - Calculate and show average class percentage
   - Identify highest and lowest scorers
   - Show grade distribution

3. **Constants:**
   - School name, academic year, passing marks

4. **Bonus Challenge:**
   - Add subject-wise marks
   - Calculate subject averages
   - Determine merit list order

**Due:** Next class  
**Format:** Well-commented PHP file with proper HTML structure

---

## Additional Resources

### PHP Manual Sections:
- Variables: https://www.php.net/manual/en/language.variables.php
- Data Types: https://www.php.net/manual/en/language.types.php
- Constants: https://www.php.net/manual/en/language.constants.php

### Practice Exercises:
- W3Schools PHP Variables: https://www.w3schools.com/php/php_variables.asp
- PHP Exercise Sets: Various online coding platforms

---

## Next Lesson Preview
**Lesson 3: PHP Operators & Expressions**
- Arithmetic operations and mathematical functions
- Comparison and logical operators
- Assignment operators and shortcuts
- Operator precedence and complex expressions

---

## Teacher Notes

### Common Student Issues:
1. Forgetting $ symbol for variables
2. Confusion between = and ==
3. String vs numeric operations
4. Variable scope understanding

### Time Management:
- 30 min: Concept explanation
- 60 min: Hands-on activities  
- 45 min: Exercises and practice
- 15 min: Review and homework explanation

### Extension Activities:
- Variable debugging exercises
- Advanced string manipulation
- Introduction to superglobal variables

---

*This lesson builds essential PHP programming foundations. Ensure students master variable creation and manipulation before advancing to operators and expressions.*
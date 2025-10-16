# Lesson 7: PHP Arrays - Indexed & Associative
**Course:** PHP Web Development - Class XII  
**Duration:** 3 hours  
**Prerequisites:** Lessons 1-6 completed, understanding of variables and loops

---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Create and manipulate indexed arrays effectively
2. Work with associative arrays for structured data storage
3. Implement multidimensional arrays for complex data relationships
4. Use array functions for data processing and manipulation
5. Loop through arrays using various iteration methods
6. Apply arrays to solve real-world programming problems

---

## Key Concepts

### 1. Indexed Arrays

#### Creating Indexed Arrays:
```php
<?php
    // Method 1: Using array() function
    $subjects = array("Mathematics", "Physics", "Chemistry", "English", "Computer Science");
    
    // Method 2: Using square brackets (PHP 5.4+)
    $grades = ["A+", "A", "B+", "A", "A+"];
    
    // Method 3: Assigning individual elements
    $marks[0] = 95;
    $marks[1] = 88;
    $marks[2] = 92;
    $marks[3] = 87;
    $marks[4] = 94;
    
    // Accessing elements
    echo $subjects[0];  // Output: Mathematics
    echo $grades[2];    // Output: B+
    echo $marks[4];     // Output: 94
?>
```

#### Array Properties and Functions:
```php
<?php
    $students = ["Alice", "Bob", "Charlie", "Diana", "Eve"];
    
    // Array length
    echo count($students);  // Output: 5
    echo sizeof($students); // Alternative function
    
    // Check if array
    var_dump(is_array($students)); // Output: bool(true)
    
    // Check if element exists
    if (isset($students[2])) {
        echo "Third student: " . $students[2];
    }
?>
```

### 2. Associative Arrays

#### Creating Associative Arrays:
```php
<?php
    // Method 1: Using array() function
    $student_info = array(
        "name" => "John Doe",
        "roll_number" => 101,
        "class" => "XII-A",
        "percentage" => 88.5,
        "subjects" => 5
    );
    
    // Method 2: Using square brackets
    $grades = [
        "Mathematics" => 95,
        "Physics" => 88,
        "Chemistry" => 92,
        "English" => 87,
        "Computer Science" => 94
    ];
    
    // Accessing elements
    echo $student_info["name"];           // Output: John Doe
    echo $grades["Mathematics"];          // Output: 95
?>
```

#### Dynamic Array Operations:
```php
<?php
    $inventory = [];
    
    // Adding elements
    $inventory["laptops"] = 25;
    $inventory["desktops"] = 15;
    $inventory["tablets"] = 30;
    
    // Modifying elements
    $inventory["laptops"] += 5;  // Now 30
    
    // Removing elements
    unset($inventory["tablets"]);
    
    // Check if key exists
    if (array_key_exists("laptops", $inventory)) {
        echo "Laptops available: " . $inventory["laptops"];
    }
?>
```

### 3. Multidimensional Arrays

#### Two-Dimensional Arrays:
```php
<?php
    // Class roster with student details
    $class_roster = [
        ["name" => "Alice Johnson", "roll" => 101, "marks" => [95, 88, 92, 87, 94]],
        ["name" => "Bob Smith", "roll" => 102, "marks" => [78, 82, 75, 89, 91]],
        ["name" => "Charlie Brown", "roll" => 103, "marks" => [65, 70, 68, 72, 74]],
        ["name" => "Diana Prince", "roll" => 104, "marks" => [88, 85, 90, 87, 89]]
    ];
    
    // Accessing nested data
    echo $class_roster[0]["name"];        // Alice Johnson
    echo $class_roster[1]["marks"][2];    // 75
    
    // Complex nested structure
    $school_data = [
        "XII-A" => [
            "students" => 30,
            "subjects" => ["Math", "Physics", "Chemistry", "English", "CS"],
            "class_teacher" => "Mr. Smith"
        ],
        "XII-B" => [
            "students" => 28,
            "subjects" => ["Math", "Biology", "Chemistry", "English", "Psychology"],
            "class_teacher" => "Ms. Johnson"
        ]
    ];
?>
```

### 4. Array Functions

#### Essential Array Functions:
```php
<?php
    $numbers = [64, 23, 89, 12, 95, 47, 38];
    
    // Sorting functions
    sort($numbers);           // Sort ascending
    rsort($numbers);          // Sort descending
    
    $grades = ["Math" => 95, "Physics" => 88, "Chemistry" => 92];
    asort($grades);           // Sort by values, maintain key association
    ksort($grades);           // Sort by keys
    
    // Array manipulation
    $fruits = ["apple", "banana"];
    array_push($fruits, "orange", "grape");    // Add to end
    array_unshift($fruits, "mango");           // Add to beginning
    $last_fruit = array_pop($fruits);          // Remove from end
    $first_fruit = array_shift($fruits);       // Remove from beginning
    
    // Array searching
    $subjects = ["Math", "Physics", "Chemistry", "English"];
    $key = array_search("Physics", $subjects);  // Returns 1
    $exists = in_array("Chemistry", $subjects); // Returns true
    
    // Array merging and slicing
    $array1 = [1, 2, 3];
    $array2 = [4, 5, 6];
    $merged = array_merge($array1, $array2);   // [1, 2, 3, 4, 5, 6]
    $slice = array_slice($merged, 2, 3);       // [3, 4, 5]
?>
```

---

## Practical Activities

### Activity 1: Student Record Management System (45 minutes)

#### Create file: `student-records.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Student Record Management System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; background-color: #f5f5f5; }
        .container { background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .student-card { background-color: #f8f9fa; margin: 15px 0; padding: 20px; border-radius: 8px; border-left: 5px solid #007bff; }
        .grade-excellent { border-left-color: #28a745; }
        .grade-good { border-left-color: #17a2b8; }
        .grade-average { border-left-color: #ffc107; }
        .grade-poor { border-left-color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background-color: #e9ecef; padding: 20px; border-radius: 8px; text-align: center; }
        .subject-analysis { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; }
        h1, h2, h3 { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Advanced Student Record Management System</h1>
        
        <?php
            // Comprehensive student database using multidimensional arrays
            $students = [
                [
                    "personal_info" => [
                        "name" => "Aarav Sharma",
                        "roll_number" => 2025001,
                        "class" => "XII-A",
                        "date_of_birth" => "2007-03-15",
                        "father_name" => "Rajesh Sharma",
                        "mother_name" => "Priya Sharma",
                        "address" => "123 MG Road, Mumbai",
                        "phone" => "9876543210"
                    ],
                    "academic_info" => [
                        "subjects" => [
                            "Mathematics" => ["theory" => 95, "practical" => 98, "total" => 96],
                            "Physics" => ["theory" => 88, "practical" => 92, "total" => 90],
                            "Chemistry" => ["theory" => 92, "practical" => 89, "total" => 90],
                            "English" => ["theory" => 87, "practical" => 85, "total" => 86],
                            "Computer Science" => ["theory" => 94, "practical" => 97, "total" => 95]
                        ],
                        "attendance" => 96,
                        "assignments_completed" => 28,
                        "total_assignments" => 30,
                        "extracurricular" => ["Debate Club", "Science Olympiad", "Chess Club"]
                    ]
                ],
                [
                    "personal_info" => [
                        "name" => "Diya Patel",
                        "roll_number" => 2025002,
                        "class" => "XII-A",
                        "date_of_birth" => "2007-08-22",
                        "father_name" => "Amit Patel",
                        "mother_name" => "Neha Patel",
                        "address" => "456 Park Avenue, Delhi",
                        "phone" => "9876543211"
                    ],
                    "academic_info" => [
                        "subjects" => [
                            "Mathematics" => ["theory" => 78, "practical" => 82, "total" => 80],
                            "Physics" => ["theory" => 82, "practical" => 79, "total" => 80],
                            "Chemistry" => ["theory" => 75, "practical" => 78, "total" => 76],
                            "English" => ["theory" => 89, "practical" => 91, "total" => 90],
                            "Computer Science" => ["theory" => 91, "practical" => 94, "total" => 92]
                        ],
                        "attendance" => 92,
                        "assignments_completed" => 29,
                        "total_assignments" => 30,
                        "extracurricular" => ["Drama Club", "Basketball Team", "Environment Club"]
                    ]
                ],
                [
                    "personal_info" => [
                        "name" => "Arjun Singh",
                        "roll_number" => 2025003,
                        "class" => "XII-B",
                        "date_of_birth" => "2007-01-10",
                        "father_name" => "Vikram Singh",
                        "mother_name" => "Kavita Singh",
                        "address" => "789 Lake View, Bangalore",
                        "phone" => "9876543212"
                    ],
                    "academic_info" => [
                        "subjects" => [
                            "Mathematics" => ["theory" => 65, "practical" => 70, "total" => 67],
                            "Physics" => ["theory" => 70, "practical" => 68, "total" => 69],
                            "Chemistry" => ["theory" => 68, "practical" => 72, "total" => 70],
                            "English" => ["theory" => 72, "practical" => 75, "total" => 73],
                            "Computer Science" => ["theory" => 74, "practical" => 78, "total" => 76]
                        ],
                        "attendance" => 87,
                        "assignments_completed" => 25,
                        "total_assignments" => 30,
                        "extracurricular" => ["Football Team", "Music Band"]
                    ]
                ],
                [
                    "personal_info" => [
                        "name" => "Priya Gupta",
                        "roll_number" => 2025004,
                        "class" => "XII-B",
                        "date_of_birth" => "2007-11-05",
                        "father_name" => "Suresh Gupta",
                        "mother_name" => "Anjali Gupta",
                        "address" => "321 Garden Street, Pune",
                        "phone" => "9876543213"
                    ],
                    "academic_info" => [
                        "subjects" => [
                            "Mathematics" => ["theory" => 45, "practical" => 52, "total" => 48],
                            "Physics" => ["theory" => 52, "practical" => 48, "total" => 50],
                            "Chemistry" => ["theory" => 48, "practical" => 51, "total" => 49],
                            "English" => ["theory" => 55, "practical" => 58, "total" => 56],
                            "Computer Science" => ["theory" => 58, "practical" => 62, "total" => 60]
                        ],
                        "attendance" => 78,
                        "assignments_completed" => 20,
                        "total_assignments" => 30,
                        "extracurricular" => ["Art Club"]
                    ]
                ]
            ];
            
            // Initialize statistics arrays
            $class_stats = [];
            $subject_totals = [];
            $grade_distribution = ["A+" => 0, "A" => 0, "B" => 0, "C" => 0, "D" => 0, "F" => 0];
            
            // Subject names for easier reference
            $subject_names = ["Mathematics", "Physics", "Chemistry", "English", "Computer Science"];
            
            echo "<h2>📋 Individual Student Profiles</h2>";
            
            // Process each student
            foreach ($students as $index => $student) {
                $personal = $student["personal_info"];
                $academic = $student["academic_info"];
                
                // Calculate overall performance
                $total_marks = 0;
                $max_marks = count($academic["subjects"]) * 100;
                $subject_performance = [];
                
                foreach ($academic["subjects"] as $subject => $marks) {
                    $total_marks += $marks["total"];
                    $subject_performance[$subject] = $marks["total"];
                    
                    // Add to subject totals for class analysis
                    if (!isset($subject_totals[$subject])) {
                        $subject_totals[$subject] = [];
                    }
                    $subject_totals[$subject][] = $marks["total"];
                }
                
                $percentage = ($total_marks / $max_marks) * 100;
                $assignment_percentage = ($academic["assignments_completed"] / $academic["total_assignments"]) * 100;
                
                // Determine grade and card styling
                if ($percentage >= 90) {
                    $grade = "A+";
                    $card_class = "grade-excellent";
                    $grade_distribution["A+"]++;
                } elseif ($percentage >= 80) {
                    $grade = "A";
                    $card_class = "grade-excellent";
                    $grade_distribution["A"]++;
                } elseif ($percentage >= 70) {
                    $grade = "B";
                    $card_class = "grade-good";
                    $grade_distribution["B"]++;
                } elseif ($percentage >= 60) {
                    $grade = "C";
                    $card_class = "grade-average";
                    $grade_distribution["C"]++;
                } elseif ($percentage >= 50) {
                    $grade = "D";
                    $card_class = "grade-average";
                    $grade_distribution["D"]++;
                } else {
                    $grade = "F";
                    $card_class = "grade-poor";
                    $grade_distribution["F"]++;
                }
                
                // Calculate age from date of birth
                $birth_date = new DateTime($personal["date_of_birth"]);
                $current_date = new DateTime();
                $age = $birth_date->diff($current_date)->y;
                
                // Display student card
                echo "<div class='student-card $card_class'>";
                echo "<h3>👤 {$personal['name']} (Roll: {$personal['roll_number']})</h3>";
                
                echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 30px;'>";
                
                // Personal Information Column
                echo "<div>";
                echo "<h4>📋 Personal Information</h4>";
                echo "<table>";
                echo "<tr><td><strong>Class:</strong></td><td>{$personal['class']}</td></tr>";
                echo "<tr><td><strong>Age:</strong></td><td>$age years</td></tr>";
                echo "<tr><td><strong>Date of Birth:</strong></td><td>{$personal['date_of_birth']}</td></tr>";
                echo "<tr><td><strong>Father's Name:</strong></td><td>{$personal['father_name']}</td></tr>";
                echo "<tr><td><strong>Mother's Name:</strong></td><td>{$personal['mother_name']}</td></tr>";
                echo "<tr><td><strong>Address:</strong></td><td>{$personal['address']}</td></tr>";
                echo "<tr><td><strong>Contact:</strong></td><td>{$personal['phone']}</td></tr>";
                echo "</table>";
                echo "</div>";
                
                // Academic Performance Column
                echo "<div>";
                echo "<h4>📚 Academic Performance</h4>";
                echo "<table>";
                echo "<tr><th>Subject</th><th>Theory</th><th>Practical</th><th>Total</th></tr>";
                foreach ($academic["subjects"] as $subject => $marks) {
                    $status = "";
                    if ($marks["total"] >= 90) $status = " 🌟";
                    elseif ($marks["total"] < 50) $status = " ⚠️";
                    
                    echo "<tr>";
                    echo "<td>$subject</td>";
                    echo "<td>{$marks['theory']}</td>";
                    echo "<td>{$marks['practical']}</td>";
                    echo "<td><strong>{$marks['total']}</strong>$status</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                echo "<div style='margin-top: 15px;'>";
                echo "<p><strong>Overall Percentage:</strong> " . round($percentage, 2) . "% (Grade: <strong>$grade</strong>)</p>";
                echo "<p><strong>Attendance:</strong> {$academic['attendance']}%</p>";
                echo "<p><strong>Assignment Completion:</strong> {$academic['assignments_completed']}/{$academic['total_assignments']} (" . round($assignment_percentage, 1) . "%)</p>";
                echo "<p><strong>Extracurricular Activities:</strong> " . implode(", ", $academic["extracurricular"]) . "</p>";
                echo "</div>";
                echo "</div>";
                
                echo "</div>";
                
                // Performance Analysis
                echo "<div style='margin-top: 20px; padding: 15px; background-color: #ffffff; border-radius: 5px;'>";
                echo "<h4>📊 Performance Analysis</h4>";
                
                // Find strongest and weakest subjects
                $strongest_subject = array_keys($subject_performance, max($subject_performance))[0];
                $weakest_subject = array_keys($subject_performance, min($subject_performance))[0];
                
                echo "<p><strong>Strongest Subject:</strong> $strongest_subject ({$subject_performance[$strongest_subject]}%)</p>";
                echo "<p><strong>Needs Improvement:</strong> $weakest_subject ({$subject_performance[$weakest_subject]}%)</p>";
                
                // Recommendations
                echo "<h5>💡 Recommendations:</h5>";
                if ($percentage >= 85) {
                    echo "<p style='color: green;'>🏆 Excellent performance! Consider advanced courses and competitions.</p>";
                } elseif ($percentage >= 75) {
                    echo "<p style='color: blue;'>👍 Good work! Focus on weaker subjects to reach excellence.</p>";
                } elseif ($percentage >= 60) {
                    echo "<p style='color: orange;'>📈 Satisfactory progress. Need consistent effort in all subjects.</p>";
                } else {
                    echo "<p style='color: red;'>🚨 Requires immediate attention. Consider remedial classes.</p>";
                }
                
                if ($academic["attendance"] < 85) {
                    echo "<p style='color: red;'>⚠️ Attendance concern: Improve attendance for better performance.</p>";
                }
                
                if ($assignment_percentage < 80) {
                    echo "<p style='color: orange;'>📝 Assignment completion needs improvement.</p>";
                }
                echo "</div>";
                
                echo "</div>";
            }
            
            // Class Statistics
            echo "<h2>📊 Class Analytics & Statistics</h2>";
            
            // Calculate class averages
            $class_average = 0;
            $total_students = count($students);
            
            foreach ($students as $student) {
                $student_total = 0;
                foreach ($student["academic_info"]["subjects"] as $marks) {
                    $student_total += $marks["total"];
                }
                $class_average += ($student_total / 500) * 100;
            }
            $class_average /= $total_students;
            
            // Display overall statistics
            echo "<div class='stats-grid'>";
            echo "<div class='stat-card'>";
            echo "<h3>$total_students</h3>";
            echo "<p>Total Students</p>";
            echo "</div>";
            echo "<div class='stat-card'>";
            echo "<h3>" . round($class_average, 2) . "%</h3>";
            echo "<p>Class Average</p>";
            echo "</div>";
            echo "<div class='stat-card'>";
            echo "<h3>" . ($grade_distribution["A+"] + $grade_distribution["A"]) . "</h3>";
            echo "<p>A Grade Students</p>";
            echo "</div>";
            echo "<div class='stat-card'>";
            echo "<h3>" . $grade_distribution["F"] . "</h3>";
            echo "<p>Students Need Help</p>";
            echo "</div>";
            echo "</div>";
            
            // Grade Distribution
            echo "<h3>🎯 Grade Distribution</h3>";
            echo "<table>";
            echo "<tr><th>Grade</th><th>Number of Students</th><th>Percentage</th></tr>";
            foreach ($grade_distribution as $grade => $count) {
                $percentage = ($count / $total_students) * 100;
                echo "<tr><td><strong>$grade</strong></td><td>$count</td><td>" . round($percentage, 1) . "%</td></tr>";
            }
            echo "</table>";
            
            // Subject-wise Analysis
            echo "<h3>📚 Subject-wise Class Performance</h3>";
            echo "<div class='subject-analysis'>";
            foreach ($subject_names as $subject) {
                if (isset($subject_totals[$subject])) {
                    $subject_marks = $subject_totals[$subject];
                    $subject_average = array_sum($subject_marks) / count($subject_marks);
                    $highest = max($subject_marks);
                    $lowest = min($subject_marks);
                    
                    echo "<div class='stat-card'>";
                    echo "<h4>$subject</h4>";
                    echo "<p><strong>Class Average:</strong> " . round($subject_average, 2) . "%</p>";
                    echo "<p><strong>Highest Score:</strong> $highest%</p>";
                    echo "<p><strong>Lowest Score:</strong> $lowest%</p>";
                    
                    // Subject difficulty assessment
                    if ($subject_average >= 85) {
                        echo "<p style='color: green;'><strong>Status:</strong> Strong Performance</p>";
                    } elseif ($subject_average >= 75) {
                        echo "<p style='color: blue;'><strong>Status:</strong> Good Performance</p>";
                    } elseif ($subject_average >= 65) {
                        echo "<p style='color: orange;'><strong>Status:</strong> Needs Attention</p>";
                    } else {
                        echo "<p style='color: red;'><strong>Status:</strong> Critical - Review Required</p>";
                    }
                    echo "</div>";
                }
            }
            echo "</div>";
            
            // Additional Analytics
            echo "<h3>🔍 Advanced Analytics</h3>";
            echo "<div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px;'>";
            
            // Performance correlation analysis
            $high_performers = 0;
            $consistent_performers = 0;
            foreach ($students as $student) {
                $marks = array_column($student["academic_info"]["subjects"], "total");
                $avg = array_sum($marks) / count($marks);
                $std_dev = sqrt(array_sum(array_map(function($x) use ($avg) { return pow($x - $avg, 2); }, $marks)) / count($marks));
                
                if ($avg >= 85) $high_performers++;
                if ($std_dev <= 10) $consistent_performers++;
            }
            
            echo "<h4>📈 Performance Insights</h4>";
            echo "<ul>";
            echo "<li><strong>High Performers (85%+ average):</strong> $high_performers students</li>";
            echo "<li><strong>Consistent Performers (low variation):</strong> $consistent_performers students</li>";
            echo "<li><strong>Class Strength:</strong> " . array_keys($subject_totals, max(array_map('array_sum', $subject_totals)))[0] ?? "Mathematics" . "</li>";
            echo "<li><strong>Improvement Area:</strong> " . array_keys($subject_totals, min(array_map('array_sum', $subject_totals)))[0] ?? "Physics" . "</li>";
            echo "</ul>";
            
            echo "<h4>🎯 Recommendations for Teachers</h4>";
            echo "<ul>";
            if ($class_average < 70) {
                echo "<li>Consider reviewing curriculum pace and teaching methods</li>";
                echo "<li>Implement additional support sessions for struggling students</li>";
            }
            if ($grade_distribution["F"] > 0) {
                echo "<li>Immediate intervention required for {$grade_distribution['F']} failing student(s)</li>";
            }
            echo "<li>Recognize and encourage high-performing students</li>";
            echo "<li>Focus on subjects with lower class averages</li>";
            echo "</ul>";
            echo "</div>";
        ?>
    </div>
</body>
</html>
```

### Activity 2: Library Management System (35 minutes)

#### Create file: `library-system.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; }
        .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .book-card { background-color: #f8f9fa; margin: 10px 0; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; }
        .available { border-left-color: #28a745; }
        .issued { border-left-color: #ffc107; }
        .overdue { border-left-color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        .search-form { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Digital Library Management System</h1>
        
        <?php
            // Library database using multidimensional arrays
            $books = [
                [
                    "id" => "B001",
                    "title" => "Introduction to Algorithms",
                    "author" => "Thomas H. Cormen",
                    "category" => "Computer Science",
                    "isbn" => "9780262033848",
                    "copies_total" => 5,
                    "copies_available" => 2,
                    "publication_year" => 2009,
                    "publisher" => "MIT Press",
                    "location" => "CS-001"
                ],
                [
                    "id" => "B002",
                    "title" => "Clean Code",
                    "author" => "Robert C. Martin",
                    "category" => "Software Engineering",
                    "isbn" => "9780132350884",
                    "copies_total" => 3,
                    "copies_available" => 0,
                    "publication_year" => 2008,
                    "publisher" => "Prentice Hall",
                    "location" => "SE-015"
                ],
                [
                    "id" => "B003",
                    "title" => "Mathematics for Class XII",
                    "author" => "R.D. Sharma",
                    "category" => "Mathematics",
                    "isbn" => "9788194436225",
                    "copies_total" => 10,
                    "copies_available" => 7,
                    "publication_year" => 2020,
                    "publisher" => "Dhanpat Rai",
                    "location" => "MATH-101"
                ],
                [
                    "id" => "B004",
                    "title" => "Physics Concepts and Applications",
                    "author" => "H.C. Verma",
                    "category" => "Physics",
                    "isbn" => "9788177091878",
                    "copies_total" => 8,
                    "copies_available" => 3,
                    "publication_year" => 2018,
                    "publisher" => "Bharati Bhawan",
                    "location" => "PHY-205"
                ],
                [
                    "id" => "B005",
                    "title" => "Organic Chemistry",
                    "author" => "Morrison & Boyd",
                    "category" => "Chemistry",
                    "isbn" => "9780136436690",
                    "copies_total" => 6,
                    "copies_available" => 1,
                    "publication_year" => 2019,
                    "publisher" => "Pearson",
                    "location" => "CHEM-150"
                ]
            ];
            
            // Issue records
            $issue_records = [
                ["book_id" => "B001", "student_name" => "Alice Johnson", "roll_number" => "2025001", "issue_date" => "2025-10-01", "due_date" => "2025-10-15", "returned" => false],
                ["book_id" => "B001", "student_name" => "Bob Smith", "roll_number" => "2025002", "issue_date" => "2025-09-25", "due_date" => "2025-10-09", "returned" => true],
                ["book_id" => "B002", "student_name" => "Charlie Brown", "roll_number" => "2025003", "issue_date" => "2025-09-20", "due_date" => "2025-10-04", "returned" => false],
                ["book_id" => "B002", "student_name" => "Diana Prince", "roll_number" => "2025004", "issue_date" => "2025-10-05", "due_date" => "2025-10-19", "returned" => false],
                ["book_id" => "B004", "student_name" => "Eve Wilson", "roll_number" => "2025005", "issue_date" => "2025-09-15", "due_date" => "2025-09-29", "returned" => false]
            ];
            
            // Search functionality simulation
            $search_term = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
            $category_filter = isset($_GET['category']) ? $_GET['category'] : '';
            
            echo "<div class='search-form'>";
            echo "<h3>🔍 Search Books</h3>";
            echo "<form method='GET'>";
            echo "<input type='text' name='search' placeholder='Search by title, author, or ISBN' value='$search_term' style='padding: 8px; width: 300px; margin-right: 10px;'>";
            echo "<select name='category' style='padding: 8px; margin-right: 10px;'>";
            echo "<option value=''>All Categories</option>";
            
            // Get unique categories
            $categories = array_unique(array_column($books, 'category'));
            foreach ($categories as $category) {
                $selected = ($category_filter == $category) ? 'selected' : '';
                echo "<option value='$category' $selected>$category</option>";
            }
            
            echo "</select>";
            echo "<button type='submit' style='padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px;'>Search</button>";
            echo "</form>";
            echo "</div>";
            
            // Filter books based on search
            $filtered_books = $books;
            if (!empty($search_term) || !empty($category_filter)) {
                $filtered_books = array_filter($books, function($book) use ($search_term, $category_filter) {
                    $matches_search = empty($search_term) || 
                                    strpos(strtolower($book['title']), $search_term) !== false ||
                                    strpos(strtolower($book['author']), $search_term) !== false ||
                                    strpos(strtolower($book['isbn']), $search_term) !== false;
                    
                    $matches_category = empty($category_filter) || $book['category'] == $category_filter;
                    
                    return $matches_search && $matches_category;
                });
            }
            
            // Display book catalog
            echo "<h2>📖 Book Catalog</h2>";
            if (empty($filtered_books)) {
                echo "<p>No books found matching your criteria.</p>";
            } else {
                foreach ($filtered_books as $book) {
                    $availability_class = "";
                    $availability_status = "";
                    
                    if ($book['copies_available'] > 0) {
                        $availability_class = "available";
                        $availability_status = "✅ Available ({$book['copies_available']}/{$book['copies_total']} copies)";
                    } else {
                        $availability_class = "issued";
                        $availability_status = "❌ All copies issued";
                    }
                    
                    echo "<div class='book-card $availability_class'>";
                    echo "<div style='display: grid; grid-template-columns: 2fr 1fr; gap: 20px;'>";
                    
                    // Book details
                    echo "<div>";
                    echo "<h3>{$book['title']}</h3>";
                    echo "<p><strong>Author:</strong> {$book['author']}</p>";
                    echo "<p><strong>Category:</strong> {$book['category']}</p>";
                    echo "<p><strong>ISBN:</strong> {$book['isbn']}</p>";
                    echo "<p><strong>Publisher:</strong> {$book['publisher']} ({$book['publication_year']})</p>";
                    echo "<p><strong>Location:</strong> {$book['location']}</p>";
                    echo "</div>";
                    
                    // Availability and actions
                    echo "<div>";
                    echo "<p><strong>Book ID:</strong> {$book['id']}</p>";
                    echo "<p><strong>Status:</strong> $availability_status</p>";
                    
                    // Find current issues for this book
                    $current_issues = array_filter($issue_records, function($record) use ($book) {
                        return $record['book_id'] == $book['id'] && !$record['returned'];
                    });
                    
                    if (!empty($current_issues)) {
                        echo "<h5>📋 Currently Issued To:</h5>";
                        echo "<ul>";
                        foreach ($current_issues as $issue) {
                            $due_date = new DateTime($issue['due_date']);
                            $current_date = new DateTime();
                            $is_overdue = $current_date > $due_date;
                            
                            echo "<li>";
                            echo "{$issue['student_name']} (Roll: {$issue['roll_number']})<br>";
                            echo "Due: {$issue['due_date']}";
                            if ($is_overdue) {
                                $days_overdue = $current_date->diff($due_date)->days;
                                echo " <span style='color: red;'>(Overdue by $days_overdue days)</span>";
                            }
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                    
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            
            // Statistics and Analytics
            echo "<h2>📊 Library Statistics</h2>";
            
            // Calculate statistics using array functions
            $total_books = count($books);
            $total_copies = array_sum(array_column($books, 'copies_total'));
            $available_copies = array_sum(array_column($books, 'copies_available'));
            $issued_copies = $total_copies - $available_copies;
            
            // Category distribution
            $category_stats = [];
            foreach ($books as $book) {
                $category = $book['category'];
                if (!isset($category_stats[$category])) {
                    $category_stats[$category] = ['titles' => 0, 'copies' => 0, 'available' => 0];
                }
                $category_stats[$category]['titles']++;
                $category_stats[$category]['copies'] += $book['copies_total'];
                $category_stats[$category]['available'] += $book['copies_available'];
            }
            
            echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0;'>";
            echo "<div style='background-color: #e3f2fd; padding: 20px; border-radius: 8px; text-align: center;'>";
            echo "<h3>$total_books</h3><p>Unique Titles</p></div>";
            echo "<div style='background-color: #e8f5e8; padding: 20px; border-radius: 8px; text-align: center;'>";
            echo "<h3>$total_copies</h3><p>Total Copies</p></div>";
            echo "<div style='background-color: #fff3cd; padding: 20px; border-radius: 8px; text-align: center;'>";
            echo "<h3>$available_copies</h3><p>Available Copies</p></div>";
            echo "<div style='background-color: #f8d7da; padding: 20px; border-radius: 8px; text-align: center;'>";
            echo "<h3>$issued_copies</h3><p>Issued Copies</p></div>";
            echo "</div>";
            
            // Category-wise breakdown
            echo "<h3>📚 Category-wise Distribution</h3>";
            echo "<table>";
            echo "<tr><th>Category</th><th>Titles</th><th>Total Copies</th><th>Available</th><th>Utilization %</th></tr>";
            foreach ($category_stats as $category => $stats) {
                $utilization = (($stats['copies'] - $stats['available']) / $stats['copies']) * 100;
                echo "<tr>";
                echo "<td><strong>$category</strong></td>";
                echo "<td>{$stats['titles']}</td>";
                echo "<td>{$stats['copies']}</td>";
                echo "<td>{$stats['available']}</td>";
                echo "<td>" . round($utilization, 1) . "%</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Recent activity and overdue books
            echo "<h3>⚠️ Overdue Books Alert</h3>";
            $overdue_books = [];
            $current_date = new DateTime();
            
            foreach ($issue_records as $record) {
                if (!$record['returned']) {
                    $due_date = new DateTime($record['due_date']);
                    if ($current_date > $due_date) {
                        $days_overdue = $current_date->diff($due_date)->days;
                        $record['days_overdue'] = $days_overdue;
                        $overdue_books[] = $record;
                    }
                }
            }
            
            if (empty($overdue_books)) {
                echo "<p style='color: green;'>✅ No overdue books at the moment!</p>";
            } else {
                echo "<table>";
                echo "<tr><th>Book ID</th><th>Student Name</th><th>Roll Number</th><th>Due Date</th><th>Days Overdue</th><th>Fine (₹2/day)</th></tr>";
                foreach ($overdue_books as $overdue) {
                    $fine = $overdue['days_overdue'] * 2;
                    echo "<tr style='background-color: #f8d7da;'>";
                    echo "<td>{$overdue['book_id']}</td>";
                    echo "<td>{$overdue['student_name']}</td>";
                    echo "<td>{$overdue['roll_number']}</td>";
                    echo "<td>{$overdue['due_date']}</td>";
                    echo "<td>{$overdue['days_overdue']} days</td>";
                    echo "<td>₹$fine</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                $total_fine = array_sum(array_map(function($book) { return $book['days_overdue'] * 2; }, $overdue_books));
                echo "<p><strong>Total Overdue Fine Collectible: ₹$total_fine</strong></p>";
            }
            
            // Most popular books (by issue frequency)
            $book_popularity = [];
            foreach ($issue_records as $record) {
                if (!isset($book_popularity[$record['book_id']])) {
                    $book_popularity[$record['book_id']] = 0;
                }
                $book_popularity[$record['book_id']]++;
            }
            
            // Sort by popularity
            arsort($book_popularity);
            
            echo "<h3>🏆 Most Popular Books</h3>";
            echo "<table>";
            echo "<tr><th>Book ID</th><th>Title</th><th>Times Issued</th></tr>";
            $count = 0;
            foreach ($book_popularity as $book_id => $issue_count) {
                if ($count >= 5) break; // Show top 5
                
                $book = array_filter($books, function($b) use ($book_id) {
                    return $b['id'] == $book_id;
                });
                $book = reset($book);
                
                echo "<tr>";
                echo "<td>$book_id</td>";
                echo "<td>{$book['title']}</td>";
                echo "<td>$issue_count times</td>";
                echo "</tr>";
                $count++;
            }
            echo "</table>";
        ?>
    </div>
</body>
</html>
```

---

## Exercises

### Exercise 1: Inventory Management (25 minutes)
Create `inventory-manager.php` that:
- Manages product inventory using associative arrays
- Tracks product details: name, price, quantity, supplier
- Implements search and filter functionality
- Calculates total inventory value and low-stock alerts

### Exercise 2: Class Schedule Generator (20 minutes)
Build `schedule-generator.php` featuring:
- Weekly timetable using multidimensional arrays
- Different schedules for different classes
- Teacher assignment tracking
- Room allocation management

### Exercise 3: Quiz Management System (30 minutes)
Develop `quiz-system.php` that:
- Stores questions and answers in nested arrays
- Implements multiple-choice and true/false questions
- Calculates scores and provides feedback
- Tracks student attempts and progress

### Exercise 4: Shopping Cart Application (35 minutes)
Create `shopping-cart.php` with:
- Product catalog with categories and prices
- Shopping cart functionality using sessions and arrays
- Order calculation with taxes and discounts
- Customer order history tracking

---

## Array Best Practices

### 1. Choosing Array Types
```php
// Use indexed arrays for simple lists
$colors = ["red", "green", "blue"];

// Use associative arrays for structured data
$student = ["name" => "John", "age" => 17, "grade" => "A"];

// Use multidimensional for complex relationships
$class = [
    "students" => [
        ["name" => "Alice", "marks" => [90, 85, 88]],
        ["name" => "Bob", "marks" => [78, 82, 75]]
    ]
];
```

### 2. Array Validation
```php
// Always check if array key exists
if (array_key_exists('email', $user_data)) {
    echo $user_data['email'];
}

// Use isset for quick checks
if (isset($grades['Math'])) {
    echo $grades['Math'];
}

// Validate array structure
if (is_array($students) && count($students) > 0) {
    foreach ($students as $student) {
        if (isset($student['name'], $student['marks'])) {
            // Process student data
        }
    }
}
```

### 3. Performance Considerations
```php
// Use array_key_exists() for associative arrays
if (array_key_exists($key, $large_array)) {
    // Key exists
}

// Use in_array() with strict comparison
if (in_array($value, $array, true)) {
    // Value found with type checking
}

// Consider array size for operations
if (count($array) > 1000) {
    // Use more efficient algorithms for large arrays
}
```

---

## Assessment

### Practical Assessment:
- [ ] Created and manipulated indexed arrays correctly
- [ ] Used associative arrays for structured data
- [ ] Implemented multidimensional array operations
- [ ] Applied appropriate array functions
- [ ] Demonstrated efficient array iteration
- [ ] Solved complex problems using arrays

### Knowledge Questions:
1. What's the difference between indexed and associative arrays?
2. How do you add elements to the beginning vs end of an array?
3. Which function would you use to search for a value in an array?
4. How do you sort an associative array by values?
5. What's the best way to check if an array key exists?

---

## Homework Assignment

### **Student Information Management Portal**
Create a comprehensive web application (`student-portal.php`) featuring:

#### **Core Features:**
1. **Student Database:**
   - Use multidimensional arrays to store complete student profiles
   - Include personal info, academic records, attendance, and activities
   - Support for multiple classes and subjects

2. **Search and Filter System:**
   - Search by name, roll number, or class
   - Filter by performance levels, attendance, or activities
   - Advanced filtering with multiple criteria

3. **Analytics Dashboard:**
   - Class performance statistics
   - Subject-wise analysis
   - Attendance patterns
   - Grade distribution charts (textual representation)

4. **Report Generation:**
   - Individual student reports
   - Class summary reports
   - Performance trend analysis
   - Parent notification lists

#### **Advanced Features:**
- Data import/export simulation (arrays to CSV format)
- Bulk operations on student records
- Performance comparison tools
- Academic calendar integration

**Due:** Next class session  
**Format:** Single PHP file with comprehensive array usage  
**Assessment:** Complexity, efficiency, user experience, code quality

---

## Next Lesson Preview
**Lesson 8: PHP Functions - Built-in & User-defined**
- Creating reusable code with custom functions
- Understanding parameter passing and return values
- Working with built-in PHP functions
- Variable scope and function best practices

---

*Arrays are fundamental to data organization in PHP. Master these concepts to build sophisticated data-driven applications.*
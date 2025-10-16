# Lesson 4: Control Structures - Conditional Statements
**Course:** PHP Web Development - Class XII  
**Duration:** 2.5 hours  
**Prerequisites:** Lessons 1-3 completed, understanding of operators and expressions

---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Implement decision-making using if-else statements
2. Use elseif for multiple condition checking
3. Apply switch-case statements for menu-driven programs
4. Utilize ternary operator for concise conditional expressions
5. Design nested conditional structures effectively
6. Choose appropriate conditional constructs for different scenarios

---

## Key Concepts

### 1. Basic if Statement

#### Simple Condition:
```php
<?php
    $age = 17;
    
    if ($age >= 18) {
        echo "You are eligible to vote!";
    }
    
    // Example: Grade checking
    $percentage = 85;
    if ($percentage >= 90) {
        echo "Excellent performance!";
    }
?>
```

### 2. if-else Statement

#### Two-way Branching:
```php
<?php
    $temperature = 25;
    
    if ($temperature > 30) {
        echo "It's hot today! Stay hydrated.";
    } else {
        echo "Pleasant weather today.";
    }
    
    // Student pass/fail example
    $marks = 45;
    if ($marks >= 50) {
        echo "Congratulations! You passed.";
    } else {
        echo "Sorry, you need to improve. Keep trying!";
    }
?>
```

### 3. elseif for Multiple Conditions

#### Multi-way Branching:
```php
<?php
    $percentage = 78;
    
    if ($percentage >= 90) {
        $grade = "A+";
        $message = "Outstanding!";
    } elseif ($percentage >= 80) {
        $grade = "A";
        $message = "Excellent!";
    } elseif ($percentage >= 70) {
        $grade = "B";
        $message = "Good!";
    } elseif ($percentage >= 60) {
        $grade = "C";
        $message = "Satisfactory";
    } elseif ($percentage >= 50) {
        $grade = "D";
        $message = "Pass";
    } else {
        $grade = "F";
        $message = "Fail - Need improvement";
    }
    
    echo "Grade: $grade ($message)";
?>
```

### 4. switch Statement

#### Menu-driven Logic:
```php
<?php
    $day = date('N'); // 1=Monday, 7=Sunday
    
    switch ($day) {
        case 1:
            echo "Monday - Start of the work week!";
            break;
        case 2:
            echo "Tuesday - Keep pushing forward!";
            break;
        case 3:
            echo "Wednesday - Midweek motivation!";
            break;
        case 4:
            echo "Thursday - Almost there!";
            break;
        case 5:
            echo "Friday - Weekend is near!";
            break;
        case 6:
        case 7:
            echo "Weekend - Time to relax!";
            break;
        default:
            echo "Invalid day";
    }
?>
```

### 5. Ternary Operator

#### Concise Conditional:
```php
<?php
    $age = 17;
    $status = ($age >= 18) ? "Adult" : "Minor";
    echo "Status: $status";
    
    // Nested ternary (use sparingly)
    $marks = 75;
    $result = ($marks >= 90) ? "Excellent" : 
              (($marks >= 70) ? "Good" : 
              (($marks >= 50) ? "Pass" : "Fail"));
    echo "Result: $result";
    
    // Practical example
    $is_weekend = (date('N') >= 6) ? true : false;
    echo $is_weekend ? "Enjoy your weekend!" : "Have a productive day!";
?>
```

---

## Practical Activities

### Activity 1: Student Management System (40 minutes)

#### Create file: `student-management.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; background-color: #f5f5f5; }
        .container { background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .student-card { background-color: #f8f9fa; margin: 15px 0; padding: 20px; border-radius: 8px; border-left: 5px solid #007bff; }
        .grade-excellent { border-left-color: #28a745; }
        .grade-good { border-left-color: #17a2b8; }
        .grade-average { border-left-color: #ffc107; }
        .grade-poor { border-left-color: #dc3545; }
        .stats { display: flex; justify-content: space-around; margin: 20px 0; }
        .stat-card { background-color: #e9ecef; padding: 15px; border-radius: 5px; text-align: center; flex: 1; margin: 0 5px; }
        h1, h2 { color: #333; }
        .highlight { font-weight: bold; font-size: 1.1em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Student Performance Management System</h1>
        
        <?php
            // Student database
            $students = [
                ["name" => "Aarav Sharma", "roll" => 101, "class" => "XII-A", "subjects" => ["Math" => 95, "Physics" => 88, "Chemistry" => 92, "English" => 87, "CS" => 94]],
                ["name" => "Diya Patel", "roll" => 102, "class" => "XII-A", "subjects" => ["Math" => 78, "Physics" => 82, "Chemistry" => 75, "English" => 89, "CS" => 91]],
                ["name" => "Arjun Singh", "roll" => 103, "class" => "XII-B", "subjects" => ["Math" => 65, "Physics" => 70, "Chemistry" => 68, "English" => 72, "CS" => 74]],
                ["name" => "Priya Gupta", "roll" => 104, "class" => "XII-B", "subjects" => ["Math" => 45, "Physics" => 52, "Chemistry" => 48, "English" => 55, "CS" => 58]],
                ["name" => "Rohit Kumar", "roll" => 105, "class" => "XII-A", "subjects" => ["Math" => 88, "Physics" => 85, "Chemistry" => 90, "English" => 83, "CS" => 87]]
            ];
            
            // Initialize counters
            $total_students = count($students);
            $pass_count = 0;
            $fail_count = 0;
            $honor_roll = 0;
            $at_risk = 0;
            $total_class_percentage = 0;
            
            echo "<h2>📊 Individual Student Analysis</h2>";
            
            foreach ($students as $student) {
                $name = $student["name"];
                $roll = $student["roll"];
                $class = $student["class"];
                $subjects = $student["subjects"];
                
                // Calculate totals and statistics
                $total_marks = array_sum($subjects);
                $max_possible = count($subjects) * 100;
                $percentage = ($total_marks / $max_possible) * 100;
                $total_class_percentage += $percentage;
                
                // Determine grade and status
                if ($percentage >= 90) {
                    $grade = "A+";
                    $status = "Excellent";
                    $card_class = "grade-excellent";
                    $honor_roll++;
                } elseif ($percentage >= 80) {
                    $grade = "A";
                    $status = "Very Good";
                    $card_class = "grade-excellent";
                } elseif ($percentage >= 70) {
                    $grade = "B";
                    $status = "Good";
                    $card_class = "grade-good";
                } elseif ($percentage >= 60) {
                    $grade = "C";
                    $status = "Satisfactory";
                    $card_class = "grade-average";
                } elseif ($percentage >= 50) {
                    $grade = "D";
                    $status = "Pass";
                    $card_class = "grade-average";
                    $pass_count++;
                } else {
                    $grade = "F";
                    $status = "Fail";
                    $card_class = "grade-poor";
                    $fail_count++;
                    $at_risk++;
                }
                
                // Count overall pass/fail
                if ($percentage >= 50) {
                    $pass_count++;
                } else {
                    $fail_count++;
                }
                
                // Check for subject failures
                $failed_subjects = [];
                $distinction_subjects = [];
                foreach ($subjects as $subject => $marks) {
                    if ($marks < 40) {
                        $failed_subjects[] = $subject;
                    }
                    if ($marks >= 90) {
                        $distinction_subjects[] = $subject;
                    }
                }
                
                // Determine special conditions
                $has_subject_failure = count($failed_subjects) > 0;
                $all_above_average = min($subjects) >= 75;
                $consistent_performance = (max($subjects) - min($subjects)) <= 15;
                
                // Display student card
                echo "<div class='student-card $card_class'>";
                echo "<h3>👤 $name (Roll: $roll, Class: $class)</h3>";
                
                echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 20px;'>";
                
                // Subject marks
                echo "<div>";
                echo "<h4>📚 Subject-wise Performance:</h4>";
                echo "<ul>";
                foreach ($subjects as $subject => $marks) {
                    $subject_status = "";
                    if ($marks >= 90) {
                        $subject_status = " 🌟 (Distinction)";
                    } elseif ($marks < 40) {
                        $subject_status = " ❌ (Fail)";
                    } elseif ($marks >= 75) {
                        $subject_status = " ✅ (Good)";
                    }
                    echo "<li>$subject: $marks/100$subject_status</li>";
                }
                echo "</ul>";
                echo "</div>";
                
                // Performance summary
                echo "<div>";
                echo "<h4>📈 Performance Summary:</h4>";
                echo "<p><strong>Total Marks:</strong> $total_marks/$max_possible</p>";
                echo "<p><strong>Percentage:</strong> " . round($percentage, 2) . "%</p>";
                echo "<p><strong>Grade:</strong> <span class='highlight'>$grade</span></p>";
                echo "<p><strong>Status:</strong> <span class='highlight'>$status</span></p>";
                
                // Special indicators
                if ($has_subject_failure) {
                    echo "<p style='color: red;'><strong>⚠️ Warning:</strong> Failed in: " . implode(", ", $failed_subjects) . "</p>";
                }
                
                if (count($distinction_subjects) > 0) {
                    echo "<p style='color: green;'><strong>🏆 Distinction in:</strong> " . implode(", ", $distinction_subjects) . "</p>";
                }
                
                if ($all_above_average) {
                    echo "<p style='color: blue;'><strong>🎯 All subjects above 75%</strong></p>";
                }
                
                if ($consistent_performance) {
                    echo "<p style='color: purple;'><strong>⚖️ Consistent performer</strong></p>";
                }
                echo "</div>";
                echo "</div>";
                
                // Recommendations using nested conditions
                echo "<h4>💡 Recommendations:</h4>";
                if ($percentage >= 90) {
                    echo "<p style='color: green;'>🌟 <strong>Excellent work!</strong> Continue maintaining this high standard. Consider advanced courses.</p>";
                } elseif ($percentage >= 80) {
                    echo "<p style='color: blue;'>👍 <strong>Great performance!</strong> Focus on weaker subjects to reach excellence.</p>";
                } elseif ($percentage >= 70) {
                    if ($has_subject_failure) {
                        echo "<p style='color: orange;'>⚠️ <strong>Good overall but needs attention.</strong> Focus on failed subjects immediately.</p>";
                    } else {
                        echo "<p style='color: green;'>📈 <strong>Good progress!</strong> With more effort, you can reach the next level.</p>";
                    }
                } elseif ($percentage >= 50) {
                    if ($has_subject_failure) {
                        echo "<p style='color: red;'>🚨 <strong>Critical attention needed!</strong> Immediate remedial classes required.</p>";
                    } else {
                        echo "<p style='color: orange;'>📚 <strong>Additional study needed.</strong> Consider extra classes and practice.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>🆘 <strong>Urgent intervention required!</strong> Complete academic support plan needed.</p>";
                }
                
                echo "</div>";
            }
            
            // Class statistics
            $class_average = $total_class_percentage / $total_students;
            
            echo "<h2>📊 Class Statistics & Analytics</h2>";
            echo "<div class='stats'>";
            echo "<div class='stat-card'>";
            echo "<h3>$total_students</h3>";
            echo "<p>Total Students</p>";
            echo "</div>";
            echo "<div class='stat-card'>";
            echo "<h3>$pass_count</h3>";
            echo "<p>Students Passed</p>";
            echo "</div>";
            echo "<div class='stat-card'>";
            echo "<h3>$fail_count</h3>";
            echo "<p>Students Failed</p>";
            echo "</div>";
            echo "<div class='stat-card'>";
            echo "<h3>$honor_roll</h3>";
            echo "<p>Honor Roll (A+)</p>";
            echo "</div>";
            echo "</div>";
            
            echo "<div style='background-color: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
            echo "<h3>🎯 Detailed Class Analysis</h3>";
            echo "<p><strong>Class Average:</strong> " . round($class_average, 2) . "%</p>";
            
            $pass_rate = ($pass_count / $total_students) * 100;
            echo "<p><strong>Pass Rate:</strong> " . round($pass_rate, 2) . "%</p>";
            
            // Class performance evaluation
            if ($class_average >= 85) {
                echo "<p style='color: green;'><strong>🏆 Class Performance:</strong> Outstanding! The class is performing exceptionally well.</p>";
            } elseif ($class_average >= 75) {
                echo "<p style='color: blue;'><strong>👍 Class Performance:</strong> Very good class performance overall.</p>";
            } elseif ($class_average >= 65) {
                echo "<p style='color: orange;'><strong>📈 Class Performance:</strong> Good, but there's room for improvement.</p>";
            } else {
                echo "<p style='color: red;'><strong>⚠️ Class Performance:</strong> Needs significant improvement. Consider class-wide interventions.</p>";
            }
            
            // Teacher recommendations based on class performance
            echo "<h4>👨‍🏫 Teacher Action Plan:</h4>";
            if ($at_risk > 0) {
                echo "<p>• <strong>$at_risk students</strong> need immediate attention</p>";
                echo "<p>• Schedule remedial classes for struggling students</p>";
            }
            if ($honor_roll > 0) {
                echo "<p>• <strong>$honor_roll students</strong> eligible for advanced programs</p>";
                echo "<p>• Consider enrichment activities for high performers</p>";
            }
            if ($pass_rate < 80) {
                echo "<p>• Review teaching methodology and pace</p>";
                echo "<p>• Conduct individual counseling sessions</p>";
            }
            echo "</div>";
        ?>
    </div>
</body>
</html>
```

### Activity 2: Interactive Menu System (30 minutes)

#### Create file: `interactive-menu.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>School Management Menu</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; background-color: #f8f9fa; }
        .menu-container { background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .menu-item { background-color: #007bff; color: white; padding: 15px; margin: 10px 0; border-radius: 5px; text-align: center; cursor: pointer; }
        .menu-item:hover { background-color: #0056b3; }
        .output { background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #28a745; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        select, input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%; }
        button { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="menu-container">
        <h1>🏫 School Management System</h1>
        <p>Select an option to proceed:</p>
        
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="menu_option">Choose Operation:</label>
                <select name="menu_option" id="menu_option" required>
                    <option value="">-- Select Option --</option>
                    <option value="1">Student Grade Calculator</option>
                    <option value="2">Attendance Tracker</option>
                    <option value="3">Fee Calculator</option>
                    <option value="4">Library Book Status</option>
                    <option value="5">Timetable Information</option>
                    <option value="6">Student ID Generator</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="input_data">Enter Required Data:</label>
                <input type="text" name="input_data" id="input_data" placeholder="Enter data as per selected option">
            </div>
            
            <button type="submit">Process Request</button>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $option = $_POST['menu_option'];
            $input_data = $_POST['input_data'];
            
            echo "<div class='output'>";
            echo "<h3>📋 Processing Result</h3>";
            
            switch ($option) {
                case "1":
                    echo "<h4>🎓 Student Grade Calculator</h4>";
                    // Expecting input like: 85,92,78,88,90 (5 subject marks)
                    if (!empty($input_data)) {
                        $marks = explode(",", $input_data);
                        if (count($marks) == 5) {
                            $total = array_sum($marks);
                            $percentage = $total / 5;
                            
                            if ($percentage >= 90) {
                                $grade = "A+";
                                $status = "Excellent";
                            } elseif ($percentage >= 80) {
                                $grade = "A";
                                $status = "Very Good";
                            } elseif ($percentage >= 70) {
                                $grade = "B";
                                $status = "Good";
                            } elseif ($percentage >= 60) {
                                $grade = "C";
                                $status = "Satisfactory";
                            } elseif ($percentage >= 50) {
                                $grade = "D";
                                $status = "Pass";
                            } else {
                                $grade = "F";
                                $status = "Fail";
                            }
                            
                            echo "<p><strong>Subject Marks:</strong> " . implode(", ", $marks) . "</p>";
                            echo "<p><strong>Total:</strong> $total/500</p>";
                            echo "<p><strong>Percentage:</strong> " . round($percentage, 2) . "%</p>";
                            echo "<p><strong>Grade:</strong> $grade</p>";
                            echo "<p><strong>Status:</strong> $status</p>";
                        } else {
                            echo "<p style='color: red;'>❌ Please enter exactly 5 subject marks separated by commas.</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>❌ Please enter marks (format: 85,92,78,88,90)</p>";
                    }
                    break;
                    
                case "2":
                    echo "<h4>📅 Attendance Tracker</h4>";
                    // Expecting input like: 85 (attendance percentage)
                    if (!empty($input_data) && is_numeric($input_data)) {
                        $attendance = (float)$input_data;
                        
                        if ($attendance >= 95) {
                            $status = "Excellent Attendance";
                            $reward = "Eligible for attendance certificate";
                            $color = "green";
                        } elseif ($attendance >= 90) {
                            $status = "Very Good Attendance";
                            $reward = "No action needed";
                            $color = "blue";
                        } elseif ($attendance >= 75) {
                            $status = "Satisfactory Attendance";
                            $reward = "Monitor closely";
                            $color = "orange";
                        } else {
                            $status = "Poor Attendance";
                            $reward = "Warning letter required";
                            $color = "red";
                        }
                        
                        echo "<p><strong>Attendance:</strong> $attendance%</p>";
                        echo "<p style='color: $color;'><strong>Status:</strong> $status</p>";
                        echo "<p><strong>Action:</strong> $reward</p>";
                        
                        $required_days = 75;
                        if ($attendance < 75) {
                            $shortage = $required_days - $attendance;
                            echo "<p style='color: red;'><strong>Shortage:</strong> $shortage% below minimum requirement</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>❌ Please enter a valid attendance percentage (0-100)</p>";
                    }
                    break;
                    
                case "3":
                    echo "<h4>💰 Fee Calculator</h4>";
                    // Expecting input like: 12000 (annual fee)
                    if (!empty($input_data) && is_numeric($input_data)) {
                        $annual_fee = (float)$input_data;
                        
                        // Calculate different components
                        $tuition_fee = $annual_fee * 0.70;  // 70%
                        $lab_fee = $annual_fee * 0.15;      // 15%
                        $library_fee = $annual_fee * 0.10;   // 10%
                        $sports_fee = $annual_fee * 0.05;    // 5%
                        
                        $quarterly_fee = $annual_fee / 4;
                        $monthly_fee = $annual_fee / 12;
                        
                        // Determine payment plan recommendation
                        if ($annual_fee > 50000) {
                            $payment_plan = "Premium Plan - Quarterly payment recommended";
                            $discount = $annual_fee * 0.05; // 5% discount for early payment
                        } elseif ($annual_fee > 25000) {
                            $payment_plan = "Standard Plan - Half-yearly payment available";
                            $discount = $annual_fee * 0.03; // 3% discount
                        } else {
                            $payment_plan = "Basic Plan - Monthly or quarterly payment";
                            $discount = $annual_fee * 0.02; // 2% discount
                        }
                        
                        echo "<p><strong>Annual Fee:</strong> ₹" . number_format($annual_fee) . "</p>";
                        echo "<h5>Fee Breakdown:</h5>";
                        echo "<ul>";
                        echo "<li>Tuition Fee: ₹" . number_format($tuition_fee) . " (70%)</li>";
                        echo "<li>Lab Fee: ₹" . number_format($lab_fee) . " (15%)</li>";
                        echo "<li>Library Fee: ₹" . number_format($library_fee) . " (10%)</li>";
                        echo "<li>Sports Fee: ₹" . number_format($sports_fee) . " (5%)</li>";
                        echo "</ul>";
                        echo "<p><strong>Quarterly Fee:</strong> ₹" . number_format($quarterly_fee) . "</p>";
                        echo "<p><strong>Monthly Fee:</strong> ₹" . number_format($monthly_fee) . "</p>";
                        echo "<p><strong>Payment Plan:</strong> $payment_plan</p>";
                        echo "<p style='color: green;'><strong>Early Payment Discount:</strong> ₹" . number_format($discount) . "</p>";
                    } else {
                        echo "<p style='color: red;'>❌ Please enter a valid annual fee amount</p>";
                    }
                    break;
                    
                case "4":
                    echo "<h4>📚 Library Book Status</h4>";
                    // Expecting input like: 5 (days overdue) or 0 (on time)
                    if (is_numeric($input_data)) {
                        $days_overdue = (int)$input_data;
                        
                        if ($days_overdue == 0) {
                            echo "<p style='color: green;'>✅ <strong>Status:</strong> Book returned on time</p>";
                            echo "<p><strong>Fine:</strong> ₹0</p>";
                            echo "<p><strong>Action:</strong> No action required</p>";
                        } elseif ($days_overdue <= 3) {
                            $fine = $days_overdue * 2;
                            echo "<p style='color: orange;'>⚠️ <strong>Status:</strong> Slightly overdue</p>";
                            echo "<p><strong>Days Overdue:</strong> $days_overdue</p>";
                            echo "<p><strong>Fine:</strong> ₹$fine (₹2 per day)</p>";
                            echo "<p><strong>Action:</strong> Gentle reminder</p>";
                        } elseif ($days_overdue <= 7) {
                            $fine = ($days_overdue * 5);
                            echo "<p style='color: red;'>❌ <strong>Status:</strong> Overdue</p>";
                            echo "<p><strong>Days Overdue:</strong> $days_overdue</p>";
                            echo "<p><strong>Fine:</strong> ₹$fine (₹5 per day)</p>";
                            echo "<p><strong>Action:</strong> Written warning issued</p>";
                        } else {
                            $fine = ($days_overdue * 10);
                            echo "<p style='color: darkred;'>🚨 <strong>Status:</strong> Seriously overdue</p>";
                            echo "<p><strong>Days Overdue:</strong> $days_overdue</p>";
                            echo "<p><strong>Fine:</strong> ₹$fine (₹10 per day)</p>";
                            echo "<p><strong>Action:</strong> Library privileges suspended</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>❌ Please enter number of days overdue (0 if on time)</p>";
                    }
                    break;
                    
                case "5":
                    echo "<h4>🕐 Timetable Information</h4>";
                    // Expecting input like day number (1-7) or day name
                    $input_lower = strtolower(trim($input_data));
                    
                    // Convert day name to number if needed
                    $day_number = $input_data;
                    if (!is_numeric($input_data)) {
                        switch ($input_lower) {
                            case "monday": $day_number = 1; break;
                            case "tuesday": $day_number = 2; break;
                            case "wednesday": $day_number = 3; break;
                            case "thursday": $day_number = 4; break;
                            case "friday": $day_number = 5; break;
                            case "saturday": $day_number = 6; break;
                            case "sunday": $day_number = 7; break;
                            default: $day_number = 0;
                        }
                    }
                    
                    switch ($day_number) {
                        case 1:
                            echo "<p><strong>Monday Schedule:</strong></p>";
                            echo "<ul><li>Period 1: Mathematics</li><li>Period 2: Physics</li><li>Period 3: Chemistry</li><li>Period 4: English</li><li>Period 5: Computer Science</li></ul>";
                            break;
                        case 2:
                            echo "<p><strong>Tuesday Schedule:</strong></p>";
                            echo "<ul><li>Period 1: Physics</li><li>Period 2: Chemistry</li><li>Period 3: Mathematics</li><li>Period 4: Physical Education</li><li>Period 5: English</li></ul>";
                            break;
                        case 3:
                            echo "<p><strong>Wednesday Schedule:</strong></p>";
                            echo "<ul><li>Period 1: Chemistry</li><li>Period 2: Mathematics</li><li>Period 3: Computer Science</li><li>Period 4: Physics</li><li>Period 5: Library Period</li></ul>";
                            break;
                        case 4:
                            echo "<p><strong>Thursday Schedule:</strong></p>";
                            echo "<ul><li>Period 1: English</li><li>Period 2: Computer Science</li><li>Period 3: Physics</li><li>Period 4: Mathematics</li><li>Period 5: Chemistry</li></ul>";
                            break;
                        case 5:
                            echo "<p><strong>Friday Schedule:</strong></p>";
                            echo "<ul><li>Period 1: Mathematics</li><li>Period 2: English</li><li>Period 3: Physics</li><li>Period 4: Chemistry</li><li>Period 5: Computer Science</li></ul>";
                            break;
                        case 6:
                            echo "<p><strong>Saturday:</strong></p>";
                            echo "<p>Half day - Extra-curricular activities and sports</p>";
                            break;
                        case 7:
                            echo "<p><strong>Sunday:</strong></p>";
                            echo "<p>Holiday - School closed</p>";
                            break;
                        default:
                            echo "<p style='color: red;'>❌ Please enter a valid day (1-7 or day name like 'Monday')</p>";
                    }
                    break;
                    
                case "6":
                    echo "<h4>🆔 Student ID Generator</h4>";
                    // Expecting input like: John Doe (student name)
                    if (!empty($input_data)) {
                        $name = trim($input_data);
                        $current_year = date('Y');
                        $random_number = rand(1000, 9999);
                        
                        // Generate different ID formats based on name length
                        $name_parts = explode(" ", $name);
                        if (count($name_parts) >= 2) {
                            $first_initial = strtoupper(substr($name_parts[0], 0, 1));
                            $last_initial = strtoupper(substr($name_parts[1], 0, 1));
                            $id_format_1 = "STU" . $current_year . $first_initial . $last_initial . $random_number;
                            $id_format_2 = $current_year . "-" . $first_initial . $last_initial . "-" . $random_number;
                        } else {
                            $name_initial = strtoupper(substr($name, 0, 2));
                            $id_format_1 = "STU" . $current_year . $name_initial . $random_number;
                            $id_format_2 = $current_year . "-" . $name_initial . "-" . $random_number;
                        }
                        
                        echo "<p><strong>Student Name:</strong> $name</p>";
                        echo "<p><strong>Generated IDs:</strong></p>";
                        echo "<ul>";
                        echo "<li>Format 1: $id_format_1</li>";
                        echo "<li>Format 2: $id_format_2</li>";
                        echo "</ul>";
                        echo "<p><strong>Issue Date:</strong> " . date('Y-m-d') . "</p>";
                        echo "<p><strong>Valid Until:</strong> " . date('Y-m-d', strtotime('+4 years')) . "</p>";
                    } else {
                        echo "<p style='color: red;'>❌ Please enter student's full name</p>";
                    }
                    break;
                    
                default:
                    echo "<p style='color: red;'>❌ Invalid option selected</p>";
            }
            echo "</div>";
        }
        ?>
        
        <div style="margin-top: 30px; padding: 15px; background-color: #e3f2fd; border-radius: 5px;">
            <h4>📝 Input Guidelines:</h4>
            <ul>
                <li><strong>Grade Calculator:</strong> Enter 5 marks separated by commas (e.g., 85,92,78,88,90)</li>
                <li><strong>Attendance Tracker:</strong> Enter attendance percentage (e.g., 87.5)</li>
                <li><strong>Fee Calculator:</strong> Enter annual fee amount (e.g., 25000)</li>
                <li><strong>Library Status:</strong> Enter days overdue (0 if on time)</li>
                <li><strong>Timetable:</strong> Enter day number (1-7) or day name (e.g., Monday)</li>
                <li><strong>ID Generator:</strong> Enter full student name (e.g., John Doe)</li>
            </ul>
        </div>
    </div>
</body>
</html>
```

---

## Exercises

### Exercise 1: Traffic Light System (15 minutes)
Create `traffic-light.php` that:
- Takes a color input (red, yellow, green)
- Uses switch statement to display appropriate message
- Shows waiting time for each light
- Handles invalid color inputs

### Exercise 2: Weather Advisory System (20 minutes)
Build `weather-advisory.php` featuring:
- Temperature input and weather condition
- Multiple nested if statements for advisories
- Clothing recommendations based on temperature and season
- Activity suggestions for different weather conditions

### Exercise 3: Student Eligibility Checker (25 minutes)
Create `eligibility-checker.php` that determines:
- Scholarship eligibility (marks > 90%, attendance > 95%)
- Sports team selection (age 16-19, specific sport, fitness level)
- Leadership program qualification (grades, conduct, recommendations)
- Use complex nested conditions with logical operators

### Exercise 4: Restaurant Ordering System (30 minutes)
Develop `restaurant-menu.php` with:
- Menu categories using switch statement
- Price calculation with discounts based on order amount
- Special offers for different days of the week
- Bill generation with tax calculations

---

## Common Conditional Logic Mistakes

### 1. Using Assignment Instead of Comparison
```php
// Wrong - This assigns 18 to $age
if ($age = 18) {
    echo "Just turned 18!";
}

// Correct
if ($age == 18) {
    echo "Just turned 18!";
}
```

### 2. Missing break in switch
```php
// Problematic - Fall-through behavior
switch ($grade) {
    case 'A':
        echo "Excellent!";
    case 'B':
        echo "Good!"; // This will also execute for grade 'A'
        break;
}

// Correct
switch ($grade) {
    case 'A':
        echo "Excellent!";
        break;
    case 'B':
        echo "Good!";
        break;
}
```

### 3. Overcomplicated Nested Conditions
```php
// Hard to read
if ($percentage >= 90) {
    if ($attendance >= 95) {
        if ($conduct == "excellent") {
            echo "Honor student";
        }
    }
}

// Better approach
if ($percentage >= 90 && $attendance >= 95 && $conduct == "excellent") {
    echo "Honor student";
}
```

---

## Assessment

### Knowledge Check:
1. What's the difference between `if-else` and `switch-case`?
2. When would you use the ternary operator?
3. How do you handle multiple conditions in a single if statement?
4. What happens if you forget `break` in a switch case?
5. How can you make nested conditions more readable?

### Practical Assessment:
- [ ] Implemented if-else statements correctly
- [ ] Used elseif for multiple conditions
- [ ] Applied switch-case appropriately
- [ ] Utilized ternary operator effectively
- [ ] Created readable nested conditions
- [ ] Handled edge cases and invalid inputs

---

## Homework Assignment

### **School Grade Management System**
Create a comprehensive grade management system (`grade-manager.php`) with:

#### **Core Features:**
1. **Grade Calculator Module:**
   - Input marks for multiple subjects
   - Calculate percentage and assign grades
   - Determine pass/fail status with subject-wise analysis

2. **Scholarship Eligibility:**
   - Check academic performance criteria
   - Evaluate attendance requirements
   - Assess extracurricular involvement
   - Generate eligibility report

3. **Parent Notification System:**
   - Create different message types based on performance
   - Include specific recommendations
   - Handle multiple notification scenarios

4. **Teacher Dashboard:**
   - Class performance analytics
   - Individual student alerts
   - Automated report generation

#### **Advanced Features:**
- Progress tracking over multiple terms
- Comparative analysis with class averages
- Intervention recommendations for struggling students
- Recognition system for high achievers

**Due:** Next class session  
**Submission:** Complete PHP file with documentation  
**Assessment:** Functionality, code quality, user experience

---

## Next Lesson Preview
**Lesson 5: Control Structures - Loops**
- for, while, and do-while loops
- Nested loop structures
- Loop control with break and continue
- Practical applications in data processing

---

*This lesson builds the foundation for decision-making in PHP programs, essential for creating intelligent and responsive web applications.*
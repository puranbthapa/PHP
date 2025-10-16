# Lesson 1: Introduction to PHP & Setup
**Course:** PHP Web Development - Class XII  
**Duration:** 2.5 hours  
**Prerequisites:** Basic HTML knowledge

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Explain what PHP is and its role in web development
2. Differentiate between client-side and server-side scripting
3. Set up XAMPP development environment
4. Write and execute their first PHP script
5. Embed PHP code within HTML documents

---

## Key Concepts

### 1. What is PHP?
- **PHP:** PHP: Hypertext Preprocessor (recursive acronym)
- **Server-side scripting language:** Code executes on the web server
- **Open source:** Free to download and use
- **Cross-platform:** Works on Windows, Linux, macOS
- **Database friendly:** Supports MySQL, PostgreSQL, Oracle, etc.

### 2. Client-side vs Server-side
- **Client-side (JavaScript):** Runs in user's browser
- **Server-side (PHP):** Runs on web server before sending to browser
- **Benefits of server-side:** Security, database access, file operations

### 3. What PHP Can Do
- Generate dynamic page content
- Create, read, write, delete files on server
- Collect and process form data
- Send and receive cookies
- Add, delete, modify database data
- Control user access and authentication
- Encrypt data for security

---

## Lesson Activities

### Activity 1: XAMPP Installation (30 minutes)

#### Step-by-step Installation:
1. **Download XAMPP:**
   - Visit https://www.apachefriends.org/
   - Download version for your operating system
   - Choose PHP 8.x version

2. **Installation Process:**
   - Run the installer as administrator
   - Select components: Apache, MySQL, PHP, phpMyAdmin
   - Choose installation directory (default: C:\xampp)
   - Complete installation

3. **Starting Services:**
   - Open XAMPP Control Panel
   - Start Apache and MySQL services
   - Verify green status indicators

4. **Testing Installation:**
   - Open browser and go to http://localhost
   - Should see XAMPP welcome page
   - Test phpMyAdmin at http://localhost/phpmyadmin

### Activity 2: First PHP Script (20 minutes)

#### Creating Your First PHP File:

**File: hello.php** (Save in C:\xampp\htdocs\)
```php
<!DOCTYPE html>
<html>
<head>
    <title>My First PHP Page</title>
</head>
<body>
    <h1>Welcome to PHP Programming!</h1>
    
    <?php
        echo "Hello, World from PHP!";
        echo "<br>";
        echo "Today is " . date("Y-m-d");
    ?>
    
    <p>This is regular HTML content.</p>
</body>
</html>
```

**Testing the Script:**
1. Save file as `hello.php` in `C:\xampp\htdocs\`
2. Open browser and navigate to `http://localhost/hello.php`
3. Observe the output combining HTML and PHP

### Activity 3: PHP Syntax Exploration (30 minutes)

#### Basic PHP Syntax Rules:
```php
<?php
    // This is a single-line comment
    
    /* This is a 
       multi-line comment */
    
    // PHP statements end with semicolon
    echo "PHP statements end with semicolon;";
    
    // Case sensitivity
    echo "PHP is case-sensitive for variables";
    $name = "John";    // Variable names are case-sensitive
    $Name = "Jane";    // Different from $name
    
    // PHP keywords are NOT case-sensitive
    ECHO "This works";
    Echo "This also works";
    echo "All variations work";
?>
```

#### PHP Tags:
```php
<?php
    // Standard PHP opening tag
    echo "This is the recommended way";
?>

<!-- Short tags (not recommended) -->
<? echo "Short tags"; ?>

<!-- PHP in HTML -->
<p>Current time: <?php echo date('H:i:s'); ?></p>
```

---

## Practical Examples

### Example 1: Dynamic Content Generation
```php
<!DOCTYPE html>
<html>
<head>
    <title>Dynamic PHP Page</title>
</head>
<body>
    <h1><?php echo "Welcome to Class XII PHP Course"; ?></h1>
    
    <p>Today's Information:</p>
    <ul>
        <li>Date: <?php echo date("F j, Y"); ?></li>
        <li>Time: <?php echo date("g:i A"); ?></li>
        <li>Server: <?php echo $_SERVER['SERVER_NAME']; ?></li>
    </ul>
    
    <?php
        // Calculate and display something
        $students = 30;
        $present = 28;
        $attendance = ($present / $students) * 100;
        
        echo "<p>Class Attendance: " . round($attendance, 1) . "%</p>";
    ?>
</body>
</html>
```

### Example 2: PHP Information Page
```php
<?php
// Display PHP configuration (useful for debugging)
// Note: Remove this in production environments

echo "<h2>PHP Information</h2>";
echo "PHP Version: " . phpversion();
echo "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'];

// Uncomment the line below to see full PHP info
// phpinfo();
?>
```

---

## Exercises

### Exercise 1: Basic Setup Verification (15 minutes)
Create a PHP file named `test-setup.php` that displays:
1. Current PHP version
2. Current date and time
3. Your name and class
4. A welcome message

**Expected Output:**
```
PHP Version: 8.2.x
Current Date: October 16, 2025
Current Time: 2:30 PM
Student: [Your Name]
Class: XII-A
Welcome to PHP Programming!
```

### Exercise 2: HTML-PHP Integration (20 minutes)
Create a file `student-info.php` that:
1. Contains proper HTML structure
2. Uses PHP to display student information in an HTML table
3. Includes at least 5 student records
4. Uses different PHP echo statements

### Exercise 3: Dynamic Page Title (10 minutes)
Create a PHP page where:
1. The page title changes based on the time of day
2. Morning (6-12): "Good Morning - PHP Class"
3. Afternoon (12-18): "Good Afternoon - PHP Class"  
4. Evening (18-24): "Good Evening - PHP Class"
5. Night (0-6): "Good Night - PHP Class"

---

## Assessment Criteria

### Knowledge Check (Verbal Questions):
1. What does PHP stand for?
2. Where does PHP code execute - client or server?
3. What file extension do PHP files use?
4. How do you start and end PHP code blocks?
5. Name three things PHP can do that HTML cannot.

### Practical Assessment:
- [ ] Successfully installed XAMPP
- [ ] Created and ran first PHP script
- [ ] Demonstrated understanding of PHP tags
- [ ] Combined HTML and PHP correctly
- [ ] Completed all exercises

---

## Homework Assignment

### Create "About Me" Page
Create a PHP file named `about-me.php` that includes:

1. **Personal Information Section:**
   - Your name, age, class, school
   - Display using PHP echo statements

2. **Dynamic Content:**
   - Current date and time
   - Day of the week
   - A different greeting based on time of day

3. **Favorites Section:**
   - List of your favorite subjects (use PHP array - we'll learn arrays in detail later)
   - Display each subject using PHP

4. **HTML Styling:**
   - Proper HTML5 structure
   - CSS styling (internal or external)
   - Responsive design elements

**Due:** Next class session  
**Submission:** Upload to class folder or email

---

## Additional Resources

### Online Resources:
- W3Schools PHP Tutorial: https://www.w3schools.com/php/
- PHP Official Documentation: https://www.php.net/docs.php
- XAMPP Documentation: https://www.apachefriends.org/docs/

### Video Tutorials:
- "PHP for Beginners" playlist on YouTube
- XAMPP installation guides

### Practice Platforms:
- PHPFiddle.org (online PHP editor)
- CodePen.io (for HTML/PHP combination)

---

## Next Lesson Preview
**Lesson 2: PHP Syntax & Variables**
- Variable declarations and naming rules
- PHP data types in detail
- Constants and global variables
- Variable scope introduction

---

## Notes for Teachers

### Common Issues:
1. **XAMPP not starting:** Check for port conflicts (Skype, other web servers)
2. **PHP files downloading instead of executing:** Apache not running or wrong file location
3. **Permission errors:** Run XAMPP as administrator

### Extension Activities:
- Have advanced students explore phpinfo() function
- Create a class webpage with everyone's PHP introduction
- Research PHP history and evolution

### Assessment Notes:
- Focus on understanding concepts over perfect syntax
- Encourage experimentation and questions
- Check individual progress during hands-on activities

---

*Lesson 1 establishes the foundation for PHP programming. Ensure all students can successfully run PHP scripts before proceeding to Lesson 2.*
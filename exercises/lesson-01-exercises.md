# Lesson 1 Exercises: Introduction to PHP & Setup

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Exercise 1: Basic Setup Verification (15 minutes)

### Instructions
Create a PHP file named `test-setup.php` that displays your system information and verifies your XAMPP installation is working correctly.

### Requirements
Your page should display:
1. Current PHP version
2. Current date and time  
3. Your name and class
4. A welcome message
5. Server information

### Expected Output Format
```
=== PHP Setup Verification ===
PHP Version: 8.2.x
Current Date: October 16, 2025
Current Time: 2:30 PM
Student Name: [Your Name]
Class: XII-A
Server: Apache/2.4.x (Win64)
Welcome to PHP Programming!
```

### Starter Code Template
```php
<!DOCTYPE html>
<html>
<head>
    <title>PHP Setup Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .info-box { background-color: #e8f4f8; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>PHP Setup Verification</h1>
    <div class="info-box">
        <?php
            // Your PHP code goes here
            // Hint: Use phpversion(), date(), $_SERVER variables
        ?>
    </div>
</body>
</html>
```

### Assessment Criteria
- [ ] PHP version displayed correctly
- [ ] Date and time shown in readable format
- [ ] Personal information included
- [ ] Proper HTML structure
- [ ] Page loads without errors

---

## Exercise 2: HTML-PHP Integration (20 minutes)

### Instructions
Create a file `student-info.php` that displays information about multiple students using PHP embedded in HTML.

### Requirements
1. Create an HTML table showing at least 5 students
2. Use PHP variables to store student data
3. Display: Name, Roll Number, Class, Subjects, Grade
4. Use different PHP echo statements for each field
5. Apply CSS styling to make it attractive

### Sample Data to Use
- Student 1: Rahul Sharma, Roll 101, Class XII-A, Subjects: Math/Physics/Chemistry, Grade: A
- Student 2: Priya Patel, Roll 102, Class XII-A, Subjects: Math/CS/English, Grade: A+
- Student 3: Arjun Singh, Roll 103, Class XII-B, Subjects: Physics/Chemistry/Biology, Grade: B+
- Add 2 more students of your choice

### Bonus Challenges
- Calculate and display the average grade
- Highlight students with A+ grades
- Add a search functionality (basic)
- Include student photos (placeholder images)

---

## Exercise 3: Dynamic Page Title (10 minutes)

### Instructions
Create a PHP page where the page title and greeting change based on the current time of day.

### Requirements
Create different messages for:
- **Morning (6 AM - 12 PM):** "Good Morning - Welcome to PHP Class"
- **Afternoon (12 PM - 6 PM):** "Good Afternoon - PHP Learning Session"  
- **Evening (6 PM - 12 AM):** "Good Evening - PHP Practice Time"
- **Night (12 AM - 6 AM):** "Good Night - Late Study Session"

### Advanced Requirements
- Display appropriate icons or colors for each time period
- Show remaining time until next period
- Add a clock showing current time
- Include a motivational quote that changes based on time

### Hint
Use PHP's `date('H')` function to get the current hour in 24-hour format.

---

## Exercise 4: Personal Portfolio Page (25 minutes)

### Instructions
Create a comprehensive "About Me" PHP page that showcases your personal information using dynamic content.

### Requirements

#### 1. Personal Information Section
- Full name, age (calculated from birth year), class, school name
- Display using PHP variables and calculations
- Include a profile picture placeholder

#### 2. Dynamic Content
- Current date and time with proper formatting
- Day of the week
- Greeting message based on time of day
- Count of days until next major holiday

#### 3. Academic Information  
- List of favorite subjects (use PHP array)
- Current GPA or percentage
- Academic goals for the year
- Extracurricular activities

#### 4. Technical Skills
- Programming languages you know or want to learn
- Display as a progress bar or skill level indicator
- Include PHP as one of the skills you're currently learning

#### 5. Interactive Elements
- Contact form (basic HTML, will be functional in later lessons)
- Social media links (can be placeholder)
- Download resume button (placeholder)

### Bonus Features
- Responsive design that works on mobile
- Dark/light theme toggle using CSS and JavaScript
- Visitor counter (using PHP sessions)
- Random quote generator
- Recent blog posts or projects section

### Assessment Criteria
- [ ] All personal information displayed correctly using PHP
- [ ] Dynamic content updates based on current date/time
- [ ] Clean, professional design and layout
- [ ] Proper use of PHP variables and functions
- [ ] Creative additions beyond basic requirements
- [ ] Code is well-commented and organized

---

## Homework Assignment: School Website Landing Page

### Project Description
Create a dynamic landing page for your school that showcases PHP integration with HTML.

### Requirements

#### Core Features
1. **Header Section**
   - School name and logo
   - Navigation menu (Home, About, Courses, Contact)
   - Current date and time display

2. **Hero Section**  
   - Welcome message with student's name
   - Dynamic greeting based on time of day
   - School motto or mission statement

3. **Information Cards**
   - Total students (use PHP variable)
   - Number of courses offered  
   - Years of establishment (calculate from founding year)
   - Upcoming events (show next 3 events)

4. **News Section**
   - Display recent school announcements
   - Use PHP arrays to store news items
   - Show publication dates

5. **Contact Information**
   - School address, phone, email
   - Office hours (show if currently open/closed based on time)
   - Map embed (Google Maps iframe)

#### Technical Requirements
- Use PHP for all dynamic content
- Implement proper HTML5 structure
- Apply CSS for professional styling
- Ensure responsive design
- Include proper meta tags and SEO elements

#### Advanced Features (Optional)
- Weather widget showing current weather
- Event countdown timer
- Student portal login section
- Photo gallery slideshow
- Quick links to important resources

### Submission Guidelines
- **File Name:** `school-landing.php`
- **Due Date:** Next class session
- **Format:** Single PHP file with embedded CSS
- **Testing:** Must work on XAMPP server
- **Documentation:** Include comments explaining PHP code sections

### Assessment Rubric (100 points)
- **Functionality (40 points):** All features work correctly
- **Design (25 points):** Professional, attractive layout
- **PHP Usage (20 points):** Proper use of variables, functions, and dynamic content
- **Code Quality (10 points):** Clean, commented, organized code
- **Creativity (5 points):** Additional features and innovative elements

---

## Additional Practice Exercises

### Quick Challenges (5-10 minutes each)
1. **Random Quote Generator:** Display a random inspirational quote each time the page loads
2. **Simple Calculator Display:** Show the result of basic math operations using PHP
3. **Birthday Countdown:** Calculate and display days until your next birthday
4. **Grade Converter:** Convert percentage to letter grades using PHP conditional logic
5. **Color Generator:** Display random background colors using PHP and CSS

### Code Debugging Exercises
Fix the errors in these PHP code snippets:

```php
// Exercise A: Find and fix 3 errors
<?php
    $student_name = "John Doe"
    echo "Welcome " + $student_name;
    Echo "Today is " . Date("Y-m-d");
?>

// Exercise B: Fix the syntax errors
<!DOCTYPE html>
<html>
<body>
    <?php
        $age = "17";
        $birth_year = 2025 - age;
        echo "<p>Birth year: " . $birth_year . "<p>";
    ?>
</body>
</html>
```

### Research Assignment
Write a 300-word report on:
- History and evolution of PHP
- Major websites that use PHP
- Comparison with other server-side languages
- Future of PHP in web development

---

## Answer Key Available
Complete solutions for all exercises are available in the `answer-keys/lesson-01/` directory for instructor reference.

## Need Help?
- Review Lesson 1 materials
- Check XAMPP troubleshooting guide
- Ask questions during next class session
- Use W3Schools PHP tutorial for additional examples
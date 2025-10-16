# Lesson 8: String Manipulation & Regular Expressions
**Course:** PHP Web Development - Class XII  
**Duration:** 3 hours  
**Prerequisites:** Lessons 1-6 completed, understanding of variables, functions, and control structures

---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Master PHP string manipulation functions and techniques
2. Understand and implement regular expressions (RegEx) in PHP
3. Perform advanced text processing and validation
4. Create pattern matching and text extraction solutions
5. Implement string sanitization and security measures
6. Build practical text processing applications

---

## Key Concepts

### 1. PHP String Fundamentals

#### String Creation and Syntax
```php
<?php
// Different ways to create strings
$singleQuoted = 'This is a single quoted string';
$doubleQuoted = "This is a double quoted string";
$heredoc = <<<EOD
This is a heredoc string.
It can span multiple lines
and supports variable interpolation.
EOD;

$nowdoc = <<<'EOD'
This is a nowdoc string.
It treats everything literally,
no variable interpolation.
EOD;

// String concatenation
$firstName = "John";
$lastName = "Doe";
$fullName = $firstName . " " . $lastName;
$greeting = "Hello, $firstName!"; // Variable interpolation in double quotes

echo "Single quoted: $singleQuoted<br>";
echo "Double quoted: $doubleQuoted<br>";
echo "Heredoc: $heredoc<br>";
echo "Nowdoc: $nowdoc<br>";
echo "Concatenated: $fullName<br>";
echo "Interpolated: $greeting<br>";
?>
```

#### Escape Sequences and Special Characters
```php
<?php
echo "<h3>Escape Sequences Demo:</h3>";

// Common escape sequences
$tab = "Column1\tColumn2\tColumn3";
$newline = "Line 1\nLine 2\nLine 3";
$quote = "He said, \"Hello World!\"";
$backslash = "Path: C:\\xampp\\htdocs";
$dollar = "Price: \$29.99";

echo "Tab separated: " . nl2br($tab) . "<br>";
echo "New lines: " . nl2br($newline) . "<br>";
echo "Quotes: $quote<br>";
echo "Backslash: $backslash<br>";
echo "Dollar sign: $dollar<br>";

// HTML entities and special characters
$htmlCode = "<script>alert('Hello');</script>";
$escapedHtml = htmlspecialchars($htmlCode);
echo "Original: $htmlCode<br>";
echo "Escaped: $escapedHtml<br>";
?>
```

### 2. Essential String Functions

#### Length and Character Functions
```php
<?php
$text = "  Welcome to PHP String Programming!  ";
echo "<h3>String Analysis Functions:</h3>";
echo "Original text: '$text'<br>";
echo "Length: " . strlen($text) . " characters<br>";
echo "Word count: " . str_word_count($text) . " words<br>";
echo "Character at position 10: " . $text[10] . "<br>";

// Trimming functions
echo "Trimmed: '" . trim($text) . "'<br>";
echo "Left trimmed: '" . ltrim($text) . "'<br>";
echo "Right trimmed: '" . rtrim($text) . "'<br>";
echo "Custom trim (remove dots): '" . trim("...Hello World...", ".") . "'<br>";

// Case conversion
echo "Uppercase: " . strtoupper($text) . "<br>";
echo "Lowercase: " . strtolower($text) . "<br>";
echo "Title case: " . ucwords(trim($text)) . "<br>";
echo "First letter uppercase: " . ucfirst(strtolower(trim($text))) . "<br>";
?>
```

#### Search and Replace Functions
```php
<?php
$sentence = "PHP is powerful. PHP is flexible. PHP is popular.";
echo "<h3>Search and Replace Functions:</h3>";
echo "Original: $sentence<br>";

// Position functions
$position = strpos($sentence, "PHP");
echo "First 'PHP' at position: $position<br>";

$lastPosition = strrpos($sentence, "PHP");
echo "Last 'PHP' at position: $lastPosition<br>";

$caseInsensitivePos = stripos($sentence, "php");
echo "Case-insensitive 'php' at position: $caseInsensitivePos<br>";

// Replace functions
$replaced = str_replace("PHP", "JavaScript", $sentence);
echo "Replace PHP with JavaScript: $replaced<br>";

$caseInsensitiveReplace = str_ireplace("php", "Python", $sentence);
echo "Case-insensitive replace: $caseInsensitiveReplace<br>";

// Multiple replacements
$search = ["PHP", "powerful", "flexible"];
$replace = ["Python", "amazing", "versatile"];
$multiReplace = str_replace($search, $replace, $sentence);
echo "Multiple replacements: $multiReplace<br>";

// Limit replacements
$limitedReplace = str_replace("PHP", "Java", $sentence, $count, 2);
echo "Limited replace (2 times): $limitedReplace<br>";
echo "Replacements made: $count<br>";
?>
```

#### Substring Functions
```php
<?php
$longText = "The quick brown fox jumps over the lazy dog";
echo "<h3>Substring Functions:</h3>";
echo "Original: $longText<br>";

// Basic substring
echo "Characters 4-9: '" . substr($longText, 4, 5) . "'<br>";
echo "From position 10: '" . substr($longText, 10) . "'<br>";
echo "Last 8 characters: '" . substr($longText, -8) . "'<br>";
echo "All but last 4: '" . substr($longText, 0, -4) . "'<br>";

// Advanced substring functions
$beforeWord = strstr($longText, "fox", true); // Get string before 'fox'
echo "Before 'fox': '$beforeWord'<br>";

$fromWord = strstr($longText, "fox"); // Get string from 'fox'
echo "From 'fox': '$fromWord'<br>";

$afterWord = strstr($longText, "fox");
$afterWord = substr($afterWord, 3); // Remove 'fox' itself
echo "After 'fox': '$afterWord'<br>";

// Chunk split
$chunked = chunk_split($longText, 10, "-");
echo "Chunked (every 10 chars): $chunked<br>";
?>
```

#### String Formatting and Padding
```php
<?php
echo "<h3>String Formatting Functions:</h3>";

// Number formatting
$number = 1234567.89;
echo "Number: " . number_format($number) . "<br>";
echo "Number with decimals: " . number_format($number, 2) . "<br>";
echo "Number with custom separators: " . number_format($number, 2, ',', '.') . "<br>";

// String padding
$text = "PHP";
echo "Original: '$text'<br>";
echo "Left pad (10 chars with 0): '" . str_pad($text, 10, "0", STR_PAD_LEFT) . "'<br>";
echo "Right pad (10 chars with -): '" . str_pad($text, 10, "-", STR_PAD_RIGHT) . "'<br>";
echo "Both sides (10 chars with *): '" . str_pad($text, 10, "*", STR_PAD_BOTH) . "'<br>";

// Repeat strings
echo "Repeat 'Hello' 3 times: " . str_repeat("Hello ", 3) . "<br>";

// Reverse string
echo "Reverse '$text': " . strrev($text) . "<br>";

// Shuffle string characters
$shuffled = str_shuffle("Hello World");
echo "Shuffled 'Hello World': $shuffled<br>";
?>
```

### 3. Array and String Conversion

#### Explode and Implode Functions
```php
<?php
echo "<h3>Array-String Conversion:</h3>";

// String to array
$csvData = "apple,banana,orange,grape,mango";
$fruits = explode(",", $csvData);
echo "CSV string: $csvData<br>";
echo "Exploded array: ";
print_r($fruits);

// Array to string
$joinedFruits = implode(" | ", $fruits);
echo "Imploded with ' | ': $joinedFruits<br>";

// Advanced splitting
$sentence = "The quick brown fox jumps";
$words = explode(" ", $sentence);
echo "Words array: ";
print_r($words);

// Multi-character delimiter
$data = "name::John||age::30||city::New York";
$pairs = explode("||", $data);
echo "Split by '||': ";
print_r($pairs);

// Parse each pair
foreach ($pairs as $pair) {
    $keyValue = explode("::", $pair);
    if (count($keyValue) == 2) {
        echo "Key: {$keyValue[0]}, Value: {$keyValue[1]}<br>";
    }
}
?>
```

#### Advanced String Parsing
```php
<?php
echo "<h3>Advanced String Parsing:</h3>";

// Parse URL components
$url = "https://www.example.com:8080/path/to/page?name=John&age=30#section1";
$parsed = parse_url($url);
echo "URL: $url<br>";
echo "Parsed components:<br>";
foreach ($parsed as $key => $value) {
    echo "&nbsp;&nbsp;$key: $value<br>";
}

// Parse query string
$queryString = "name=John&age=30&city=New%20York&skills[]=PHP&skills[]=JavaScript";
parse_str($queryString, $queryArray);
echo "<br>Query string: $queryString<br>";
echo "Parsed query array:<br>";
print_r($queryArray);

// CSV parsing
$csvLine = '"John Doe","Software Engineer","New York, NY","john@example.com"';
$csvFields = str_getcsv($csvLine);
echo "<br>CSV line: $csvLine<br>";
echo "CSV fields:<br>";
print_r($csvFields);

// Convert array back to CSV
$newCsvLine = implode('","', $csvFields);
$newCsvLine = '"' . $newCsvLine . '"';
echo "Reconstructed CSV: $newCsvLine<br>";
?>
```

### 4. Regular Expressions (RegEx)

#### Introduction to Regular Expressions
```php
<?php
echo "<h3>Regular Expression Basics:</h3>";

$text = "Contact us at support@example.com or sales@company.org";
$emailPattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';

// Pattern matching
if (preg_match($emailPattern, $text, $matches)) {
    echo "Found email: " . $matches[0] . "<br>";
}

// Find all matches
$allMatches = preg_match_all($emailPattern, $text, $matches);
echo "Found $allMatches email(s):<br>";
foreach ($matches[0] as $email) {
    echo "- $email<br>";
}

// Common regex patterns
$patterns = [
    'email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
    'phone' => '/^(\+\d{1,3}[- ]?)?\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$/',
    'url' => '/^https?:\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/',
    'zipcode' => '/^\d{5}(-\d{4})?$/',
    'credit_card' => '/^\d{4}[- ]?\d{4}[- ]?\d{4}[- ]?\d{4}$/',
    'ip_address' => '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
    'strong_password' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
    'html_tag' => '/<\/?[a-z][\s\S]*>/i'
];

echo "<br><h4>Pattern Testing:</h4>";
$testStrings = [
    'john.doe@example.com',
    '(555) 123-4567',
    'https://www.example.com',
    '12345-6789',
    '1234 5678 9012 3456',
    '192.168.1.1',
    'MyPassword123!',
    '<div class="content">Hello</div>'
];

foreach ($testStrings as $index => $testString) {
    echo "<strong>Testing: '$testString'</strong><br>";
    foreach ($patterns as $type => $pattern) {
        $match = preg_match($pattern, $testString);
        echo "&nbsp;&nbsp;$type: " . ($match ? "✓" : "✗") . "<br>";
    }
    echo "<br>";
}
?>
```

#### Advanced Regular Expression Functions
```php
<?php
echo "<h3>Advanced RegEx Functions:</h3>";

$text = "Today is 2024-03-15. The event is scheduled for 15/03/2024 and ends on March 15, 2024.";

// Replace with regex
$datePattern = '/\d{4}-\d{2}-\d{2}/';
$newText = preg_replace($datePattern, '[DATE]', $text);
echo "Original: $text<br>";
echo "Date replaced: $newText<br>";

// Replace with callback function
function formatDate($matches) {
    $date = DateTime::createFromFormat('Y-m-d', $matches[0]);
    return $date->format('F j, Y');
}

$formattedText = preg_replace_callback($datePattern, 'formatDate', $text);
echo "Date formatted: $formattedText<br>";

// Split with regex
$mixedText = "apple,banana;orange:grape|mango";
$fruits = preg_split('/[,;:|]/', $mixedText);
echo "<br>Mixed delimiters: $mixedText<br>";
echo "Split result: " . implode(' | ', $fruits) . "<br>";

// Complex pattern matching with groups
$logLine = '[2024-03-15 14:30:25] ERROR: Database connection failed (Code: 1045)';
$logPattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+): (.+) \(Code: (\d+)\)/';

if (preg_match($logPattern, $logLine, $matches)) {
    echo "<br>Log parsing:<br>";
    echo "Full match: {$matches[0]}<br>";
    echo "Timestamp: {$matches[1]}<br>";
    echo "Level: {$matches[2]}<br>";
    echo "Message: {$matches[3]}<br>";
    echo "Error Code: {$matches[4]}<br>";
}
?>
```

#### Pattern Modifiers and Flags
```php
<?php
echo "<h3>RegEx Pattern Modifiers:</h3>";

$text = "Hello World\nThis is a Test\nPHP is Great";

// Case insensitive (i modifier)
$pattern1 = '/hello/i';
echo "Case insensitive match 'hello': " . (preg_match($pattern1, $text) ? "Found" : "Not found") . "<br>";

// Multiline mode (m modifier)
$pattern2 = '/^This/m';
echo "Multiline match '^This': " . (preg_match($pattern2, $text) ? "Found" : "Not found") . "<br>";

// Dot matches newline (s modifier)
$pattern3 = '/World.*Test/s';
echo "Dot matches newline: " . (preg_match($pattern3, $text) ? "Found" : "Not found") . "<br>";

// Extended syntax (x modifier) - allows comments and whitespace
$complexPattern = '/
    ^[a-zA-Z0-9._%+-]+  # Username part
    @                   # @ symbol
    [a-zA-Z0-9.-]+      # Domain name
    \.                  # Dot
    [a-zA-Z]{2,}$       # Top-level domain
/x';

$email = "user@example.com";
echo "<br>Extended pattern email validation: " . (preg_match($complexPattern, $email) ? "Valid" : "Invalid") . "<br>";

// Global modifier with preg_replace
$textWithNumbers = "There are 123 apples and 456 oranges and 789 bananas.";
$numberPattern = '/\d+/';

// Replace first occurrence
$replaceFirst = preg_replace($numberPattern, 'X', $textWithNumbers, 1);
echo "<br>Replace first number: $replaceFirst<br>";

// Replace all occurrences
$replaceAll = preg_replace($numberPattern, 'X', $textWithNumbers);
echo "Replace all numbers: $replaceAll<br>";
?>
```

### 5. Text Validation and Sanitization

#### Input Validation Functions
```php
<?php
echo "<h3>Input Validation and Sanitization:</h3>";

function validateEmail($email) {
    // Basic format check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    // Additional regex check for stricter validation
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email);
}

function validatePhone($phone) {
    // Remove all non-digit characters
    $cleaned = preg_replace('/\D/', '', $phone);
    
    // Check if it's a valid length (10 or 11 digits)
    return strlen($cleaned) >= 10 && strlen($cleaned) <= 11;
}

function validatePassword($password) {
    // At least 8 characters, one uppercase, one lowercase, one digit, one special char
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    return preg_match($pattern, $password);
}

function sanitizeInput($input) {
    // Remove whitespace
    $input = trim($input);
    
    // Remove HTML tags
    $input = strip_tags($input);
    
    // Convert special characters to HTML entities
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    
    return $input;
}

function validateCreditCard($number) {
    // Remove spaces and hyphens
    $number = preg_replace('/[\s-]/', '', $number);
    
    // Check if all characters are digits
    if (!ctype_digit($number)) {
        return false;
    }
    
    // Luhn algorithm for credit card validation
    $sum = 0;
    $length = strlen($number);
    
    for ($i = $length - 1; $i >= 0; $i--) {
        $digit = intval($number[$i]);
        
        if (($length - $i) % 2 == 0) {
            $digit *= 2;
            if ($digit > 9) {
                $digit = $digit % 10 + intval($digit / 10);
            }
        }
        
        $sum += $digit;
    }
    
    return $sum % 10 == 0;
}

// Test validation functions
$testData = [
    'emails' => ['john@example.com', 'invalid.email', 'test@domain.co.uk'],
    'phones' => ['(555) 123-4567', '555-123-4567', '1234567890', '123'],
    'passwords' => ['MyPassword123!', 'weak', 'Strong123!', 'NODIGITS!'],
    'credit_cards' => ['4532015112830366', '1234567890123456', '4532-0151-1283-0366']
];

foreach ($testData['emails'] as $email) {
    echo "Email '$email': " . (validateEmail($email) ? "Valid" : "Invalid") . "<br>";
}

echo "<br>";
foreach ($testData['phones'] as $phone) {
    echo "Phone '$phone': " . (validatePhone($phone) ? "Valid" : "Invalid") . "<br>";
}

echo "<br>";
foreach ($testData['passwords'] as $password) {
    echo "Password '$password': " . (validatePassword($password) ? "Strong" : "Weak") . "<br>";
}

echo "<br>";
foreach ($testData['credit_cards'] as $cc) {
    echo "Credit Card '$cc': " . (validateCreditCard($cc) ? "Valid" : "Invalid") . "<br>";
}

// Sanitization examples
echo "<br><h4>Sanitization Examples:</h4>";
$userInputs = [
    '  <script>alert("XSS")</script>  ',
    'John "The Great" Doe',
    'Price: $29.99 & shipping',
    '   Multiple   Spaces   Between   Words   '
];

foreach ($userInputs as $input) {
    $sanitized = sanitizeInput($input);
    echo "Original: '$input'<br>";
    echo "Sanitized: '$sanitized'<br><br>";
}
?>
```

### 6. Advanced String Processing

#### String Comparison Functions
```php
<?php
echo "<h3>String Comparison Functions:</h3>";

$string1 = "Apple";
$string2 = "apple";
$string3 = "Banana";

// Basic comparison
echo "strcmp('$string1', '$string2'): " . strcmp($string1, $string2) . "<br>";
echo "strcasecmp('$string1', '$string2'): " . strcasecmp($string1, $string2) . "<br>";

// Natural order comparison
$items = ["item2", "item10", "item1", "item20"];
echo "<br>Original array: " . implode(", ", $items) . "<br>";

sort($items);
echo "Standard sort: " . implode(", ", $items) . "<br>";

natsort($items);
echo "Natural sort: " . implode(", ", $items) . "<br>";

// Similar text comparison
$text1 = "PHP Programming";
$text2 = "PHP Development";
$similarity = 0;
$similarText = similar_text($text1, $text2, $similarity);
echo "<br>Similar text between '$text1' and '$text2':<br>";
echo "Characters in common: $similarText<br>";
echo "Similarity percentage: " . round($similarity, 2) . "%<br>";

// Levenshtein distance (edit distance)
$word1 = "kitten";
$word2 = "sitting";
$distance = levenshtein($word1, $word2);
echo "<br>Levenshtein distance between '$word1' and '$word2': $distance<br>";

// Soundex comparison
$name1 = "Smith";
$name2 = "Smyth";
echo "<br>Soundex comparison:<br>";
echo "'$name1' soundex: " . soundex($name1) . "<br>";
echo "'$name2' soundex: " . soundex($name2) . "<br>";
echo "Sound similar: " . (soundex($name1) == soundex($name2) ? "Yes" : "No") . "<br>";
?>
```

#### Text Encoding and Conversion
```php
<?php
echo "<h3>Text Encoding and Conversion:</h3>";

// Base64 encoding/decoding
$originalText = "Hello, World! This is a test message.";
$encoded = base64_encode($originalText);
$decoded = base64_decode($encoded);

echo "Original: $originalText<br>";
echo "Base64 encoded: $encoded<br>";
echo "Decoded: $decoded<br>";

// URL encoding/decoding
$url = "https://example.com/search?q=hello world&lang=en";
$urlEncoded = urlencode($url);
$urlDecoded = urldecode($urlEncoded);

echo "<br>URL: $url<br>";
echo "URL encoded: $urlEncoded<br>";
echo "URL decoded: $urlDecoded<br>";

// Raw URL encoding (for spaces and special chars)
$query = "hello world & special chars!";
$rawEncoded = rawurlencode($query);
echo "<br>Query: $query<br>";
echo "Raw URL encoded: $rawEncoded<br>";

// HTML encoding/decoding
$htmlContent = '<p>This is <strong>bold</strong> text & "quoted" content.</p>';
$htmlEncoded = htmlentities($htmlContent);
$htmlDecoded = html_entity_decode($htmlEncoded);

echo "<br>HTML: $htmlContent<br>";
echo "HTML encoded: $htmlEncoded<br>";
echo "HTML decoded: $htmlDecoded<br>";

// Binary to hex conversion
$binaryData = "Hello";
$hexData = bin2hex($binaryData);
$backToBinary = hex2bin($hexData);

echo "<br>Binary: $binaryData<br>";
echo "Hex: $hexData<br>";
echo "Back to binary: $backToBinary<br>";
?>
```

---

## Practical Activities

### Activity 1: Advanced Text Processing System (90 minutes)

#### Create file: `text-processor.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Text Processing System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; }
        .section { background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .tool-section { border: 1px solid #ddd; margin: 15px 0; padding: 15px; border-radius: 5px; }
        .result { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        textarea { width: 100%; min-height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        input[type="text"] { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; width: 200px; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background-color: #0056b3; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin: 15px 0; }
        .stat-item { background-color: white; padding: 10px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>
    <h1>📝 Advanced Text Processing System</h1>
    
    <?php
    // Text analysis functions
    function analyzeText($text) {
        $stats = [
            'character_count' => strlen($text),
            'character_count_no_spaces' => strlen(str_replace(' ', '', $text)),
            'word_count' => str_word_count($text),
            'sentence_count' => preg_match_all('/[.!?]+/', $text),
            'paragraph_count' => count(array_filter(explode("\n\n", $text))),
            'line_count' => count(explode("\n", $text)),
            'average_words_per_sentence' => 0,
            'reading_time_minutes' => 0
        ];
        
        if ($stats['sentence_count'] > 0) {
            $stats['average_words_per_sentence'] = round($stats['word_count'] / $stats['sentence_count'], 1);
        }
        
        // Estimate reading time (average 200 words per minute)
        $stats['reading_time_minutes'] = round($stats['word_count'] / 200, 1);
        
        return $stats;
    }
    
    function extractEntities($text) {
        $entities = [
            'emails' => [],
            'urls' => [],
            'phone_numbers' => [],
            'dates' => [],
            'numbers' => [],
            'hashtags' => [],
            'mentions' => []
        ];
        
        // Email extraction
        preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches);
        $entities['emails'] = array_unique($matches[0]);
        
        // URL extraction
        preg_match_all('/https?:\/\/[^\s]+/', $text, $matches);
        $entities['urls'] = array_unique($matches[0]);
        
        // Phone number extraction
        preg_match_all('/\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/', $text, $matches);
        $entities['phone_numbers'] = array_unique($matches[0]);
        
        // Date extraction (various formats)
        preg_match_all('/\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}|\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}/', $text, $matches);
        $entities['dates'] = array_unique($matches[0]);
        
        // Number extraction
        preg_match_all('/\$?\d+(?:,\d{3})*(?:\.\d{2})?/', $text, $matches);
        $entities['numbers'] = array_unique($matches[0]);
        
        // Hashtag extraction
        preg_match_all('/#\w+/', $text, $matches);
        $entities['hashtags'] = array_unique($matches[0]);
        
        // Mention extraction (@username)
        preg_match_all('/@\w+/', $text, $matches);
        $entities['mentions'] = array_unique($matches[0]);
        
        return $entities;
    }
    
    function transformText($text, $transformation) {
        switch ($transformation) {
            case 'uppercase':
                return strtoupper($text);
            case 'lowercase':
                return strtolower($text);
            case 'title_case':
                return ucwords(strtolower($text));
            case 'sentence_case':
                return ucfirst(strtolower($text));
            case 'reverse':
                return strrev($text);
            case 'remove_spaces':
                return preg_replace('/\s+/', '', $text);
            case 'normalize_spaces':
                return preg_replace('/\s+/', ' ', trim($text));
            case 'remove_numbers':
                return preg_replace('/\d/', '', $text);
            case 'remove_punctuation':
                return preg_replace('/[^\w\s]/', '', $text);
            case 'slug':
                return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($text)));
            default:
                return $text;
        }
    }
    
    function validateInput($input, $type) {
        switch ($type) {
            case 'email':
                return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
            case 'url':
                return filter_var($input, FILTER_VALIDATE_URL) !== false;
            case 'phone':
                return preg_match('/^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/', $input);
            case 'zipcode':
                return preg_match('/^\d{5}(-\d{4})?$/', $input);
            case 'credit_card':
                return preg_match('/^\d{4}[-\s]?\d{4}[-\s]?\d{4}[-\s]?\d{4}$/', $input);
            case 'strong_password':
                return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $input);
            default:
                return false;
        }
    }
    
    function findSimilarWords($word, $wordList) {
        $similar = [];
        $threshold = 3; // Maximum edit distance
        
        foreach ($wordList as $listWord) {
            $distance = levenshtein(strtolower($word), strtolower($listWord));
            if ($distance <= $threshold && $distance > 0) {
                $similar[] = $listWord;
            }
        }
        
        return $similar;
    }
    
    // Process form submissions
    $result = '';
    $textToProcess = '';
    
    if ($_POST) {
        $textToProcess = $_POST['text'] ?? '';
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'analyze':
                $stats = analyzeText($textToProcess);
                $entities = extractEntities($textToProcess);
                break;
                
            case 'transform':
                $transformation = $_POST['transformation'] ?? '';
                $transformedText = transformText($textToProcess, $transformation);
                $result = "Transformation applied: " . ucwords(str_replace('_', ' ', $transformation));
                break;
                
            case 'validate':
                $validationType = $_POST['validation_type'] ?? '';
                $isValid = validateInput($textToProcess, $validationType);
                $result = "Validation result: " . ($isValid ? "Valid" : "Invalid") . " " . ucwords(str_replace('_', ' ', $validationType));
                break;
                
            case 'search_replace':
                $searchTerm = $_POST['search_term'] ?? '';
                $replaceTerm = $_POST['replace_term'] ?? '';
                $useRegex = isset($_POST['use_regex']);
                
                if ($useRegex) {
                    $transformedText = preg_replace($searchTerm, $replaceTerm, $textToProcess);
                } else {
                    $transformedText = str_replace($searchTerm, $replaceTerm, $textToProcess);
                }
                $result = "Search and replace completed";
                break;
        }
    }
    ?>
    
    <!-- Text Input Form -->
    <div class="section">
        <h2>📄 Text Input</h2>
        <form method="POST" id="mainForm">
            <textarea name="text" placeholder="Enter your text here for processing..."><?php echo htmlspecialchars($textToProcess); ?></textarea>
        </form>
    </div>
    
    <!-- Text Analysis Tools -->
    <div class="section">
        <h2>📊 Text Analysis</h2>
        <div class="tool-section">
            <h3>Statistical Analysis</h3>
            <button onclick="submitForm('analyze')">Analyze Text</button>
            
            <?php if (isset($stats)): ?>
                <div class="stats-grid">
                    <div class="stat-item">
                        <strong><?php echo number_format($stats['character_count']); ?></strong><br>
                        Characters (with spaces)
                    </div>
                    <div class="stat-item">
                        <strong><?php echo number_format($stats['character_count_no_spaces']); ?></strong><br>
                        Characters (no spaces)
                    </div>
                    <div class="stat-item">
                        <strong><?php echo number_format($stats['word_count']); ?></strong><br>
                        Words
                    </div>
                    <div class="stat-item">
                        <strong><?php echo number_format($stats['sentence_count']); ?></strong><br>
                        Sentences
                    </div>
                    <div class="stat-item">
                        <strong><?php echo $stats['average_words_per_sentence']; ?></strong><br>
                        Avg. Words/Sentence
                    </div>
                    <div class="stat-item">
                        <strong><?php echo $stats['reading_time_minutes']; ?> min</strong><br>
                        Est. Reading Time
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Entity Extraction -->
        <?php if (isset($entities)): ?>
            <div class="tool-section">
                <h3>🔍 Extracted Entities</h3>
                <?php foreach ($entities as $type => $items): ?>
                    <?php if (!empty($items)): ?>
                        <div style="margin: 10px 0;">
                            <strong><?php echo ucwords(str_replace('_', ' ', $type)); ?>:</strong><br>
                            <?php foreach ($items as $item): ?>
                                <span style="background-color: #e9ecef; padding: 2px 6px; margin: 2px; border-radius: 3px; display: inline-block;"><?php echo htmlspecialchars($item); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Text Transformation Tools -->
    <div class="section">
        <h2>🔄 Text Transformations</h2>
        <div class="tool-section">
            <select name="transformation" form="mainForm">
                <option value="uppercase">UPPERCASE</option>
                <option value="lowercase">lowercase</option>
                <option value="title_case">Title Case</option>
                <option value="sentence_case">Sentence case</option>
                <option value="reverse">esreveR</option>
                <option value="remove_spaces">RemoveSpaces</option>
                <option value="normalize_spaces">Normalize Spaces</option>
                <option value="remove_numbers">Remove Numbers</option>
                <option value="remove_punctuation">Remove Punctuation</option>
                <option value="slug">create-slug</option>
            </select>
            <button onclick="submitForm('transform')">Transform Text</button>
            
            <?php if (isset($transformedText)): ?>
                <div class="result">
                    <strong>Transformed Text:</strong><br>
                    <textarea readonly style="background-color: #f8f9fa;"><?php echo htmlspecialchars($transformedText); ?></textarea>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Search and Replace -->
    <div class="section">
        <h2>🔍 Search & Replace</h2>
        <div class="tool-section">
            <input type="text" name="search_term" form="mainForm" placeholder="Search for...">
            <input type="text" name="replace_term" form="mainForm" placeholder="Replace with...">
            <label><input type="checkbox" name="use_regex" form="mainForm"> Use RegEx</label>
            <button onclick="submitForm('search_replace')">Search & Replace</button>
        </div>
    </div>
    
    <!-- Input Validation -->
    <div class="section">
        <h2>✅ Input Validation</h2>
        <div class="tool-section">
            <select name="validation_type" form="mainForm">
                <option value="email">Email Address</option>
                <option value="url">URL</option>
                <option value="phone">Phone Number</option>
                <option value="zipcode">ZIP Code</option>
                <option value="credit_card">Credit Card</option>
                <option value="strong_password">Strong Password</option>
            </select>
            <button onclick="submitForm('validate')">Validate Input</button>
        </div>
    </div>
    
    <?php if ($result): ?>
        <div class="result">
            <?php echo $result; ?>
        </div>
    <?php endif; ?>
    
    <script>
        function submitForm(action) {
            const form = document.getElementById('mainForm');
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);
            form.submit();
        }
    </script>
</body>
</html>
```

### Activity 2: Regular Expression Tester and Generator (45 minutes)

#### Create file: `regex-tester.php`
```php
<!DOCTYPE html>
<html>
<head>
    <title>RegEx Tester & Pattern Generator</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; }
        .section { background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .pattern-input { width: 100%; padding: 10px; font-family: monospace; border: 1px solid #ddd; border-radius: 4px; }
        .test-strings { min-height: 100px; }
        .match { background-color: #d4edda; color: #155724; }
        .no-match { background-color: #f8d7da; color: #721c24; }
        .pattern-library { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; }
        .pattern-item { background-color: white; padding: 15px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background-color: #0056b3; }
        .copy-btn { background-color: #28a745; font-size: 12px; padding: 4px 8px; }
        code { background-color: #f1f1f1; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>🔍 RegEx Tester & Pattern Generator</h1>
    
    <?php
    // Common regex patterns library
    $patternLibrary = [
        'Email Address' => [
            'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'description' => 'Validates standard email addresses',
            'examples' => ['user@example.com', 'test.email+tag@domain.co.uk']
        ],
        'Phone Number (US)' => [
            'pattern' => '/^(\+1[- ]?)?\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$/',
            'description' => 'US phone numbers with various formats',
            'examples' => ['(555) 123-4567', '555-123-4567', '+1 555 123 4567']
        ],
        'URL' => [
            'pattern' => '/^https?:\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/',
            'description' => 'HTTP and HTTPS URLs',
            'examples' => ['https://www.example.com', 'http://subdomain.site.org/path']
        ],
        'IP Address' => [
            'pattern' => '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
            'description' => 'IPv4 addresses',
            'examples' => ['192.168.1.1', '10.0.0.1', '255.255.255.255']
        ],
        'Strong Password' => [
            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'description' => 'At least 8 chars, 1 upper, 1 lower, 1 digit, 1 special',
            'examples' => ['MyPassword123!', 'SecurePass@2024']
        ],
        'Credit Card' => [
            'pattern' => '/^\d{4}[- ]?\d{4}[- ]?\d{4}[- ]?\d{4}$/',
            'description' => '16-digit credit card numbers',
            'examples' => ['1234 5678 9012 3456', '1234-5678-9012-3456']
        ],
        'Date (MM/DD/YYYY)' => [
            'pattern' => '/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/',
            'description' => 'US date format MM/DD/YYYY',
            'examples' => ['03/15/2024', '12/31/2023']
        ],
        'Social Security Number' => [
            'pattern' => '/^\d{3}-\d{2}-\d{4}$/',
            'description' => 'US SSN format XXX-XX-XXXX',
            'examples' => ['123-45-6789', '987-65-4321']
        ],
        'HTML Tag' => [
            'pattern' => '/<\/?[a-z][\s\S]*>/i',
            'description' => 'HTML opening and closing tags',
            'examples' => ['<div>', '</p>', '<img src="image.jpg">']
        ],
        'Hexadecimal Color' => [
            'pattern' => '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'description' => 'CSS hex color codes',
            'examples' => ['#FF5733', '#333', '#A1B2C3']
        ]
    ];
    
    $testResults = [];
    $pattern = '';
    $testStrings = '';
    $flags = '';
    
    if ($_POST) {
        $pattern = $_POST['pattern'] ?? '';
        $testStrings = $_POST['test_strings'] ?? '';
        $flags = $_POST['flags'] ?? '';
        $action = $_POST['action'] ?? '';
        
        if ($action === 'test' && !empty($pattern) && !empty($testStrings)) {
            $strings = array_filter(explode("\n", $testStrings));
            
            foreach ($strings as $string) {
                $string = trim($string);
                if (!empty($string)) {
                    try {
                        $fullPattern = $pattern . $flags;
                        $matches = [];
                        $result = preg_match($fullPattern, $string, $matches);
                        
                        $testResults[] = [
                            'string' => $string,
                            'matches' => $result,
                            'captured_groups' => $matches,
                            'error' => null
                        ];
                    } catch (Exception $e) {
                        $testResults[] = [
                            'string' => $string,
                            'matches' => false,
                            'captured_groups' => [],
                            'error' => $e->getMessage()
                        ];
                    }
                }
            }
        } elseif ($action === 'use_pattern') {
            $selectedPattern = $_POST['selected_pattern'] ?? '';
            if (isset($patternLibrary[$selectedPattern])) {
                $pattern = $patternLibrary[$selectedPattern]['pattern'];
                $testStrings = implode("\n", $patternLibrary[$selectedPattern]['examples']);
            }
        }
    }
    ?>
    
    <div class="section">
        <h2>🧪 Pattern Tester</h2>
        <form method="POST">
            <div style="margin-bottom: 15px;">
                <label for="pattern"><strong>Regular Expression Pattern:</strong></label><br>
                <input type="text" name="pattern" id="pattern" class="pattern-input" 
                       value="<?php echo htmlspecialchars($pattern); ?>" 
                       placeholder="Enter your regex pattern here (e.g., /^[a-zA-Z0-9]+$/)">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="flags"><strong>Flags (optional):</strong></label><br>
                <select name="flags" id="flags">
                    <option value="">No flags</option>
                    <option value="i" <?php echo $flags === 'i' ? 'selected' : ''; ?>>i (case insensitive)</option>
                    <option value="m" <?php echo $flags === 'm' ? 'selected' : ''; ?>>m (multiline)</option>
                    <option value="s" <?php echo $flags === 's' ? 'selected' : ''; ?>>s (dot matches newline)</option>
                    <option value="x" <?php echo $flags === 'x' ? 'selected' : ''; ?>>x (ignore whitespace)</option>
                    <option value="im" <?php echo $flags === 'im' ? 'selected' : ''; ?>>im (case insensitive + multiline)</option>
                </select>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="test_strings"><strong>Test Strings (one per line):</strong></label><br>
                <textarea name="test_strings" id="test_strings" class="pattern-input test-strings" 
                          placeholder="Enter test strings here, one per line"><?php echo htmlspecialchars($testStrings); ?></textarea>
            </div>
            
            <button type="submit" name="action" value="test">Test Pattern</button>
        </form>
        
        <?php if (!empty($testResults)): ?>
            <div style="margin-top: 20px;">
                <h3>🎯 Test Results</h3>
                <?php foreach ($testResults as $result): ?>
                    <div class="<?php echo $result['matches'] ? 'match' : 'no-match'; ?>" style="padding: 10px; margin: 5px 0; border-radius: 4px;">
                        <strong>String:</strong> <code><?php echo htmlspecialchars($result['string']); ?></code><br>
                        <strong>Result:</strong> <?php echo $result['matches'] ? '✅ Match' : '❌ No Match'; ?>
                        
                        <?php if ($result['error']): ?>
                            <br><strong>Error:</strong> <?php echo htmlspecialchars($result['error']); ?>
                        <?php elseif (!empty($result['captured_groups']) && count($result['captured_groups']) > 1): ?>
                            <br><strong>Captured Groups:</strong>
                            <?php for ($i = 1; $i < count($result['captured_groups']); $i++): ?>
                                <code>Group <?php echo $i; ?>: <?php echo htmlspecialchars($result['captured_groups'][$i]); ?></code>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h2>📚 Pattern Library</h2>
        <p>Click "Use Pattern" to load a pre-built pattern with examples:</p>
        
        <div class="pattern-library">
            <?php foreach ($patternLibrary as $name => $info): ?>
                <div class="pattern-item">
                    <h4><?php echo htmlspecialchars($name); ?></h4>
                    <p><small><?php echo htmlspecialchars($info['description']); ?></small></p>
                    <code style="font-size: 12px; word-break: break-all;"><?php echo htmlspecialchars($info['pattern']); ?></code>
                    <br><br>
                    <strong>Examples:</strong><br>
                    <?php foreach ($info['examples'] as $example): ?>
                        <code style="font-size: 11px; display: block; margin: 2px 0;"><?php echo htmlspecialchars($example); ?></code>
                    <?php endforeach; ?>
                    <br>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="selected_pattern" value="<?php echo htmlspecialchars($name); ?>">
                        <button type="submit" name="action" value="use_pattern" class="copy-btn">Use Pattern</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="section">
        <h2>📖 RegEx Quick Reference</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div>
                <h4>Character Classes</h4>
                <code>.</code> - Any character<br>
                <code>\d</code> - Digit (0-9)<br>
                <code>\w</code> - Word character (a-z, A-Z, 0-9, _)<br>
                <code>\s</code> - Whitespace<br>
                <code>[abc]</code> - Any of a, b, c<br>
                <code>[a-z]</code> - Any lowercase letter<br>
                <code>[^abc]</code> - Not a, b, or c
            </div>
            
            <div>
                <h4>Quantifiers</h4>
                <code>*</code> - 0 or more<br>
                <code>+</code> - 1 or more<br>
                <code>?</code> - 0 or 1<br>
                <code>{3}</code> - Exactly 3<br>
                <code>{2,5}</code> - Between 2 and 5<br>
                <code>{3,}</code> - 3 or more
            </div>
            
            <div>
                <h4>Anchors</h4>
                <code>^</code> - Start of string<br>
                <code>$</code> - End of string<br>
                <code>\b</code> - Word boundary<br>
                <code>\B</code> - Not word boundary
            </div>
            
            <div>
                <h4>Groups & Alternation</h4>
                <code>(abc)</code> - Capturing group<br>
                <code>(?:abc)</code> - Non-capturing group<br>
                <code>a|b</code> - a or b<br>
                <code>(?=abc)</code> - Positive lookahead<br>
                <code>(?!abc)</code> - Negative lookahead
            </div>
        </div>
    </div>
</body>
</html>
```

---

## Exercises

### Exercise 1: Password Strength Analyzer (20 minutes)
Create `password-analyzer.php` that:
- Analyzes password strength using multiple criteria
- Provides specific feedback on improvements
- Generates strong password suggestions
- Shows visual strength indicators

### Exercise 2: Text Similarity Checker (25 minutes) 
Build `similarity-checker.php` featuring:
- Compare two texts for similarity percentage
- Highlight common words and phrases
- Calculate various distance metrics
- Provide plagiarism detection features

### Exercise 3: Log File Parser (30 minutes)
Develop `log-parser.php` that:
- Parses various log file formats (Apache, nginx, custom)
- Extracts relevant information (IPs, timestamps, errors)
- Generates statistics and reports
- Filters and searches log entries

### Exercise 4: Markdown to HTML Converter (35 minutes)
Create `markdown-converter.php` with:
- Convert basic Markdown syntax to HTML
- Support headers, links, lists, code blocks
- Implement custom extensions
- Preview and export functionality

---

## Assessment

### Knowledge Check:
1. What is the difference between `preg_match()` and `preg_match_all()`?
2. Explain the purpose of regex modifiers (i, m, s, x).
3. How do you escape special characters in regular expressions?
4. What are capturing groups and how are they used?
5. When should you use `preg_replace()` vs `str_replace()`?

### Practical Assessment:
- [ ] Created effective regular expression patterns
- [ ] Used string functions appropriately
- [ ] Implemented input validation and sanitization
- [ ] Applied text processing techniques correctly
- [ ] Handled edge cases and errors properly

---

## Homework Assignment

### **Complete Content Management System (CMS) Text Processing Module**
Create a comprehensive text processing system (`cms-text-processor.php`) with advanced features:

#### **Required Components:**
1. **Article Processing:**
   - Auto-generate article summaries (first 200 words)
   - Extract and count keywords and phrases
   - Estimate reading time and difficulty level
   - Auto-generate SEO-friendly URLs (slugs)

2. **Content Validation:**
   - Check for proper grammar and spelling
   - Validate embedded links and images
   - Detect duplicate content
   - Ensure accessibility compliance

3. **Advanced Text Manipulation:**
   - Smart excerpt generation with proper sentence endings
   - Auto-format quotes and code blocks
   - Convert plain text to structured HTML
   - Implement text templates and placeholders

4. **Search and Filter System:**
   - Full-text search with highlighting
   - Tag extraction and management
   - Content categorization based on text analysis
   - Related content suggestions

5. **Security Features:**
   - XSS prevention and HTML sanitization
   - Profanity filter and content moderation
   - Spam detection using pattern matching
   - Input validation for all user content

**Due:** Next class session  
**Assessment:** Regex implementation, string processing efficiency, security measures, user interface design

---

## Next Lesson Preview
**Lesson 9: Form Handling & Validation**
- Processing form data securely
- Server-side validation techniques
- File uploads and handling
- CSRF protection and security

---

*String manipulation and regular expressions are powerful tools for text processing. Master these skills to build robust, secure, and efficient web applications.*
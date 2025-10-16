# Lesson 10: File Handling, Sessions & Cookies
**Course:** PHP Web Development - Class XII  
**Duration:** 4 hours  
**Prerequisites:** Lessons 1-9 completed; basic PHP syntax and web fundamentals

---

## Learning Objectives
By the end of this lesson students will be able to:
1. Read from and write to files using PHP's file I/O functions
2. Work safely with files: locking, permissions, and MIME checks
3. Store structured data in files (JSON, CSV) and parse them
4. Manage sessions securely and implement login/logout flows
5. Use cookies for stateful client-side storage and understand security flags
6. Build small file-based data storage and session-backed features

---

## Section 1 — File Operations Basics

### Opening, Reading and Writing Files
Key functions: `fopen()`, `fread()`, `fwrite()`, `fclose()`, `file_get_contents()`, `file_put_contents()`

Example:
```php
<?php
$path = __DIR__ . '/../data/example.txt';
// Write text to a file
file_put_contents($path, "Hello PHP file handling!\n", FILE_APPEND | LOCK_EX);

// Read file contents
$content = file_get_contents($path);
echo nl2br(htmlspecialchars($content));
?>
```

Notes:
- Use `LOCK_EX` to avoid race conditions when writing.
- Check return values for errors and handle them gracefully.

### File Pointers and Streams
```php
<?php
$fp = fopen($path, 'r');
if ($fp) {
    while (!feof($fp)) {
        $line = fgets($fp);
        echo htmlspecialchars($line) . "<br>";
    }
    fclose($fp);
}
?>
```

### File Locking
Always lock files when performing read-modify-write sequences to prevent corruption:
```php
$fp = fopen($path, 'c+');
if (flock($fp, LOCK_EX)) { // exclusive lock
    // safe read / write
    flock($fp, LOCK_UN); // release
}
fclose($fp);
```

---

## Section 2 — Working with JSON & CSV

### JSON (Recommended for structured data)
```php
<?php
$data = ['id' => 1, 'name' => 'Alice', 'score' => 95];
file_put_contents('data/students.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

$raw = file_get_contents('data/students.json');
$decoded = json_decode($raw, true);
print_r($decoded);
?>
```

### CSV (Interoperability)
```php
<?php
$row = ['2025001', 'Alice Johnson', 'XII-A'];
$fp = fopen('data/students.csv', 'a');
fputcsv($fp, $row);
fclose($fp);

// Reading CSV
$fp = fopen('data/students.csv', 'r');
while (($fields = fgetcsv($fp)) !== false) {
    print_r($fields);
}
fclose($fp);
?>
```

---

## Section 3 — File Upload Security and Handling

Key checks for uploaded files:
- Check `$_FILES['file']['error']` for errors
- Restrict extension and validate MIME type via `finfo`
- Set size limits and storage outside webroot when possible
- Generate safe filenames via `uniqid()` or hashing

Example snippet:
```php
<?php
$allowed_ext = ['jpg','png','pdf'];
$max_size = 5 * 1024 * 1024; // 5MB
if (isset($_FILES['upload']) && $_FILES['upload']['error'] == UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) die('Invalid file type');

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['upload']['tmp_name']);
    finfo_close($finfo);
    // Check mime accordingly
    $safe_name = uniqid('doc_', true) . '.' . $ext;
    move_uploaded_file($_FILES['upload']['tmp_name'], __DIR__ . '/../uploads/' . $safe_name);
}
?>
```

---

## Section 4 — Sessions: State on the Server

### Starting Sessions Securely
Use a central helper (see `form-validators.php` / `SessionManager`) or:
```php
<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
if (isset($_SERVER['HTTPS'])) ini_set('session.cookie_secure', 1);
session_start();
// regenerate id after login
session_regenerate_id(true);
?>
```

### Simple Login Flow (Session-backed)
- Validate credentials (in a real app check a database)
- On success: store user id and role in `$_SESSION`
- On logout: `session_unset(); session_destroy();`

Example:
```php
<?php
// login.php (process)
session_start();
if ($_POST['username']==='student' && $_POST['password']==='pass123') {
    $_SESSION['user'] = ['id'=>1,'username'=>'student'];
    header('Location: session-demo.php');
    exit;
}
?>
```

### Flash messages pattern
Store messages in session and remove after reading so they appear once.

---

## Section 5 — Cookies: Client-side Persistent Data

### Setting & Reading Cookies
```php
<?php
// set cookie (name, value, expire, path, domain, secure, httponly, samesite)
setcookie('theme', 'dark', time()+60*60*24*30, '/', '', isset($_SERVER['HTTPS']), true);

// read
$theme = $_COOKIE['theme'] ?? 'light';

// delete
setcookie('theme', '', time()-3600, '/');
?>
```

### Security Flags
- `HttpOnly` prevents JavaScript access
- `Secure` sent only over HTTPS
- `SameSite` helps prevent CSRF (Lax or Strict)

---

## Practical Activities

### Activity 1: File Handling Demo (30 minutes)
Open `file-handling-demo.php`. It contains examples for reading/writing files, JSON and CSV manipulation, and safe file operations.

### Activity 2: Session Demo (30 minutes)
Open `session-demo.php`. Demonstrates secure session start, login simulation, flash messages, and logout.

### Activity 3: Cookie Demo (20 minutes)
Open `cookie-demo.php` to test setting, reading, and deleting cookies with secure flags.

---

## Exercises

1. Build a file-based guestbook using `file_put_contents()` and `fgetcsv()` for entries. Include validation and XSS protection.
2. Implement multi-file upload with progress bar and store metadata in `data/uploads.json`.
3. Create a login system using sessions and a `data/users.json` file (store hashed passwords using `password_hash()`).

---

## Homework

Create a mini Student Document Center combining file uploads, session-based authentication, and a file browser restricted to logged-in users. Requirements:
- Login and logout functionality with sessions
- Upload documents and list files uploaded by the logged in user
- Secure display and download links (no arbitrary path access)
- Store metadata in `data/uploads.json`

---

## Next Lesson Preview
**Lesson 11: Error Handling & Debugging** — exceptions, try/catch, logging, using Xdebug and debugging techniques.

---

*Files used in examples are in the repository root under `data/` (for JSON/CSV) and `uploads/` (for file uploads). Ensure these directories are writable by your webserver.*

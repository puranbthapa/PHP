<?php
/**
 * File Handling Demo
 * Demonstrates basic file read/write, JSON and CSV usage, and safe locking
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

$base = __DIR__ . DIRECTORY_SEPARATOR . 'data';
if (!is_dir($base)) mkdir($base, 0777, true);

// Example text file
$txtFile = $base . DIRECTORY_SEPARATOR . 'example.txt';
file_put_contents($txtFile, "Demo created at: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND | LOCK_EX);

// Read file
echo "<h2>Text File Contents</h2>";
if (file_exists($txtFile)) {
    $content = file_get_contents($txtFile);
    echo '<pre>' . htmlspecialchars($content) . '</pre>';
} else {
    echo '<p>No text file found.</p>';
}

// JSON example
$jsonFile = $base . DIRECTORY_SEPARATOR . 'students.json';
$students = [];
if (file_exists($jsonFile)) {
    $students = json_decode(file_get_contents($jsonFile), true) ?: [];
}

$students[] = ['id' => uniqid('s_'), 'name' => 'Student ' . (count($students) + 1), 'created' => date('c')];
file_put_contents($jsonFile, json_encode($students, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo "<h2>Students (JSON)</h2>";
echo '<pre>' . htmlspecialchars(file_get_contents($jsonFile)) . '</pre>';

// CSV example
$csvFile = $base . DIRECTORY_SEPARATOR . 'students.csv';
$fp = fopen($csvFile, 'a');
fputcsv($fp, [date('Y-m-d H:i:s'), 'Sample Student', rand(1,100)]);
fclose($fp);

echo "<h2>CSV Contents</h2>";
if (($fp = fopen($csvFile, 'r')) !== false) {
    echo '<table border="1" cellpadding="6" cellspacing="0">';
    while (($row = fgetcsv($fp)) !== false) {
        echo '<tr>';
        foreach ($row as $cell) echo '<td>' . htmlspecialchars($cell) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    fclose($fp);
}

// Safe rename and delete example
echo "<h2>Safe File Operations</h2>";
$tmp = $base . DIRECTORY_SEPARATOR . 'temp.txt';
file_put_contents($tmp, "temporary\n");

if (file_exists($tmp)) {
    $safeName = $base . DIRECTORY_SEPARATOR . 'renamed_' . basename($tmp);
    rename($tmp, $safeName);
    echo '<p>Renamed temp file to: ' . htmlspecialchars(basename($safeName)) . '</p>';
    unlink($safeName);
}

// Demonstrate file locking for critical section
$lockFile = $base . DIRECTORY_SEPARATOR . 'counter.txt';
$handle = fopen($lockFile, 'c+');
if ($handle) {
    if (flock($handle, LOCK_EX)) {
        $counter = intval(stream_get_contents($handle));
        $counter++;
        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, (string)$counter);
        fflush($handle);
        flock($handle, LOCK_UN);
        echo '<p>Counter incremented to: ' . $counter . '</p>';
    }
    fclose($handle);
}

?>
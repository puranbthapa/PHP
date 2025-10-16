<?php
/**
 * Student Manager Demo
 * Demonstrates comprehensive CRUD operations with proper error handling
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

// Database configuration
$config = [
    'host' => 'localhost',
    'dbname' => 'school_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        global $config;
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

class StudentManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO students (name, email, age, grade, total_points) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                $data['name'],
                $data['email'],
                $data['age'],
                $data['grade'],
                $data['total_points'] ?? 0
            ]);
            
            return $result ? $this->db->lastInsertId() : false;
        } catch (PDOException $e) {
            throw new Exception("Failed to create student: " . $e->getMessage());
        }
    }
    
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM students WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit, $offset]);
        
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM students WHERE id = ? AND deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch();
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE students SET name = ?, email = ?, age = ?, grade = ?, total_points = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                $data['name'],
                $data['email'],
                $data['age'],
                $data['grade'],
                $data['total_points'],
                $id
            ]);
        } catch (PDOException $e) {
            throw new Exception("Failed to update student: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        try {
            // Soft delete
            $sql = "UPDATE students SET deleted_at = NOW() WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Failed to delete student: " . $e->getMessage());
        }
    }
    
    public function search($term) {
        $sql = "SELECT * FROM students WHERE deleted_at IS NULL AND (name LIKE ? OR email LIKE ?) ORDER BY name";
        $searchTerm = "%$term%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        
        return $stmt->fetchAll();
    }
}

// Initialize manager
$studentManager = new StudentManager();
$message = '';
$messageType = 'info';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'create':
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'age' => (int)$_POST['age'],
                    'grade' => trim($_POST['grade']),
                    'total_points' => (int)($_POST['total_points'] ?? 0)
                ];
                
                // Basic validation
                if (empty($data['name']) || empty($data['email'])) {
                    throw new Exception("Name and email are required");
                }
                
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Invalid email format");
                }
                
                $id = $studentManager->create($data);
                $message = "Student created successfully with ID: $id";
                $messageType = 'success';
                break;
                
            case 'update':
                $id = (int)$_POST['id'];
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'age' => (int)$_POST['age'],
                    'grade' => trim($_POST['grade']),
                    'total_points' => (int)$_POST['total_points']
                ];
                
                if ($studentManager->update($id, $data)) {
                    $message = "Student updated successfully";
                    $messageType = 'success';
                } else {
                    $message = "Failed to update student";
                    $messageType = 'error';
                }
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                if ($studentManager->delete($id)) {
                    $message = "Student deleted successfully";
                    $messageType = 'success';
                } else {
                    $message = "Failed to delete student";
                    $messageType = 'error';
                }
                break;
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}

// Get students for display
$students = $studentManager->getAll();
$editStudent = null;

// Handle edit mode
if (isset($_GET['edit'])) {
    $editStudent = $studentManager->getById((int)$_GET['edit']);
}

// Handle search
$searchResults = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchResults = $studentManager->search($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Manager Demo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .message { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .form-group { margin: 10px 0; }
        label { display: inline-block; width: 100px; font-weight: bold; }
        input, select { padding: 5px; width: 200px; }
        button { padding: 8px 15px; margin: 5px; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; border: none; }
        .btn-danger { background: #dc3545; color: white; border: none; }
        .btn-secondary { background: #6c757d; color: white; border: none; }
        .actions { white-space: nowrap; }
    </style>
</head>
<body>
    <h1>Student Manager Demo</h1>
    
    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <!-- Search Form -->
    <form method="GET" style="margin: 20px 0;">
        <label>Search:</label>
        <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Name or email">
        <button type="submit" class="btn-primary">Search</button>
        <a href="?" class="btn-secondary" style="text-decoration: none; padding: 8px 15px;">Show All</a>
    </form>
    
    <!-- Add/Edit Form -->
    <h2><?php echo $editStudent ? 'Edit Student' : 'Add New Student'; ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?php echo $editStudent ? 'update' : 'create'; ?>">
        <?php if ($editStudent): ?>
            <input type="hidden" name="id" value="<?php echo $editStudent['id']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($editStudent['name'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($editStudent['email'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Age:</label>
            <input type="number" name="age" value="<?php echo htmlspecialchars($editStudent['age'] ?? ''); ?>" min="15" max="25" required>
        </div>
        
        <div class="form-group">
            <label>Grade:</label>
            <select name="grade" required>
                <option value="">Select Grade</option>
                <?php
                $grades = ['XII-A', 'XII-B', 'XII-C'];
                foreach ($grades as $grade) {
                    $selected = (isset($editStudent['grade']) && $editStudent['grade'] == $grade) ? 'selected' : '';
                    echo "<option value='$grade' $selected>$grade</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Points:</label>
            <input type="number" name="total_points" value="<?php echo htmlspecialchars($editStudent['total_points'] ?? 0); ?>" min="0">
        </div>
        
        <button type="submit" class="btn-primary">
            <?php echo $editStudent ? 'Update Student' : 'Add Student'; ?>
        </button>
        
        <?php if ($editStudent): ?>
            <a href="?" class="btn-secondary" style="text-decoration: none; padding: 8px 15px;">Cancel</a>
        <?php endif; ?>
    </form>
    
    <!-- Students Table -->
    <h2><?php echo !empty($searchResults) ? 'Search Results' : 'All Students'; ?></h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Grade</th>
                <th>Points</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $displayStudents = !empty($searchResults) ? $searchResults : $students;
            if (empty($displayStudents)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; font-style: italic;">No students found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($displayStudents as $student): ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo $student['age']; ?></td>
                        <td><?php echo htmlspecialchars($student['grade']); ?></td>
                        <td><?php echo $student['total_points']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($student['created_at'])); ?></td>
                        <td class="actions">
                            <a href="?edit=<?php echo $student['id']; ?>" class="btn-primary" style="text-decoration: none; padding: 5px 10px; font-size: 12px;">Edit</a>
                            
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                <button type="submit" class="btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <p><a href="database-setup.php">← Back to Database Setup</a> | <a href="prepared-statements-demo.php">Prepared Statements Demo →</a></p>
</body>
</html>
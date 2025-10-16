# Lesson 14: Web APIs and Modern PHP Development
**Course:** PHP Web Development - Class XII  
**Duration:** 3 hours  
**Prerequisites:** Lessons 1-13 completed, understanding of databases, JSON basics, JavaScript fundamentals

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Learning Objectives
By the end of this lesson, students will be able to:
1. Understand REST API principles and HTTP methods
2. Create and consume RESTful web services in PHP
3. Handle JSON data efficiently for API communication
4. Implement AJAX requests for dynamic web interactions
5. Apply API authentication and security measures
6. Use modern PHP development practices and tools

---

## Key Concepts

### 1. RESTful API Fundamentals

#### What is a REST API?
**REST** (Representational State Transfer) is an architectural style for web services that uses HTTP methods to perform operations on resources.

#### HTTP Methods and Their Uses:
```php
<?php
// RESTful endpoint examples
/*
GET    /api/students        - Get all students
GET    /api/students/123    - Get student with ID 123
POST   /api/students        - Create new student
PUT    /api/students/123    - Update student with ID 123
DELETE /api/students/123    - Delete student with ID 123
*/

// Basic API structure
class StudentAPI {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type');
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path_parts = explode('/', trim($path, '/'));
        
        // Remove 'api' from path if present
        if ($path_parts[0] === 'api') {
            array_shift($path_parts);
        }
        
        $resource = $path_parts[0] ?? '';
        $id = $path_parts[1] ?? null;
        
        try {
            switch ($method) {
                case 'GET':
                    if ($id) {
                        $this->getStudent($id);
                    } else {
                        $this->getAllStudents();
                    }
                    break;
                    
                case 'POST':
                    $this->createStudent();
                    break;
                    
                case 'PUT':
                    $this->updateStudent($id);
                    break;
                    
                case 'DELETE':
                    $this->deleteStudent($id);
                    break;
                    
                default:
                    $this->sendResponse(405, ['error' => 'Method not allowed']);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => 'Internal server error']);
        }
    }
    
    private function sendResponse($status_code, $data) {
        http_response_code($status_code);
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
}
?>
```

### 2. Complete RESTful API Implementation

#### Create file: `api/students.php`
```php
<?php
require_once '../config/database.php';

class StudentsAPI {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=school_management", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->sendError(500, 'Database connection failed');
        }
        
        // Set API headers
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path_info = $_SERVER['PATH_INFO'] ?? '';
        $path_parts = explode('/', trim($path_info, '/'));
        $student_id = $path_parts[0] ?? null;
        
        switch ($method) {
            case 'GET':
                if ($student_id) {
                    $this->getStudent($student_id);
                } else {
                    $this->getAllStudents();
                }
                break;
                
            case 'POST':
                $this->createStudent();
                break;
                
            case 'PUT':
                if ($student_id) {
                    $this->updateStudent($student_id);
                } else {
                    $this->sendError(400, 'Student ID required for update');
                }
                break;
                
            case 'DELETE':
                if ($student_id) {
                    $this->deleteStudent($student_id);
                } else {
                    $this->sendError(400, 'Student ID required for deletion');
                }
                break;
                
            default:
                $this->sendError(405, 'Method not allowed');
        }
    }
    
    // GET /api/students
    private function getAllStudents() {
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 10);
        $search = $_GET['search'] ?? '';
        $class = $_GET['class'] ?? '';
        $sort = $_GET['sort'] ?? 'first_name';
        $order = $_GET['order'] ?? 'ASC';
        
        $offset = ($page - 1) * $limit;
        
        // Build query with filters
        $where_conditions = ['1=1'];
        $params = [];
        
        if (!empty($search)) {
            $where_conditions[] = "(first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR roll_number LIKE ?)";
            $search_term = "%$search%";
            $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
        }
        
        if (!empty($class)) {
            $where_conditions[] = "class = ?";
            $params[] = $class;
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        // Get total count for pagination
        $count_sql = "SELECT COUNT(*) FROM students WHERE $where_clause";
        $count_stmt = $this->pdo->prepare($count_sql);
        $count_stmt->execute($params);
        $total_records = $count_stmt->fetchColumn();
        
        // Get students with pagination
        $allowed_sort_columns = ['first_name', 'last_name', 'email', 'roll_number', 'class', 'created_at'];
        if (!in_array($sort, $allowed_sort_columns)) {
            $sort = 'first_name';
        }
        
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        
        $sql = "SELECT student_id, first_name, last_name, email, roll_number, class, 
                       date_of_birth, phone, address, created_at 
                FROM students 
                WHERE $where_clause 
                ORDER BY $sort $order 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $students = $stmt->fetchAll();
        
        // Include statistics
        $stats_sql = "
            SELECT 
                COUNT(*) as total_students,
                COUNT(DISTINCT class) as total_classes,
                AVG(YEAR(CURDATE()) - YEAR(date_of_birth)) as average_age
            FROM students 
            WHERE $where_clause
        ";
        $stats_stmt = $this->pdo->prepare($sql_stats = str_replace('ORDER BY', '', str_replace("LIMIT $limit OFFSET $offset", '', $sql)));
        
        // Build stats query without the complex part for simplicity
        $simple_stats = $this->pdo->query("SELECT COUNT(*) as total FROM students")->fetch();
        
        $response = [
            'success' => true,
            'data' => $students,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total_records' => $total_records,
                'total_pages' => ceil($total_records / $limit)
            ],
            'filters' => [
                'search' => $search,
                'class' => $class,
                'sort' => $sort,
                'order' => $order
            ]
        ];
        
        $this->sendResponse(200, $response);
    }
    
    // GET /api/students/{id}
    private function getStudent($id) {
        if (!is_numeric($id)) {
            $this->sendError(400, 'Invalid student ID');
        }
        
        $sql = "
            SELECT s.*, 
                   COUNT(g.grade_id) as total_grades,
                   AVG(g.marks_obtained) as average_marks,
                   MAX(g.marks_obtained) as highest_marks,
                   MIN(g.marks_obtained) as lowest_marks
            FROM students s
            LEFT JOIN grades g ON s.student_id = g.student_id
            WHERE s.student_id = ?
            GROUP BY s.student_id
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $student = $stmt->fetch();
        
        if (!$student) {
            $this->sendError(404, 'Student not found');
        }
        
        // Get recent grades
        $grades_sql = "
            SELECT g.*, sub.subject_name, sub.subject_code
            FROM grades g
            INNER JOIN subjects sub ON g.subject_id = sub.subject_id
            WHERE g.student_id = ?
            ORDER BY g.exam_date DESC
            LIMIT 5
        ";
        
        $grades_stmt = $this->pdo->prepare($grades_sql);
        $grades_stmt->execute([$id]);
        $student['recent_grades'] = $grades_stmt->fetchAll();
        
        $this->sendResponse(200, [
            'success' => true,
            'data' => $student
        ]);
    }
    
    // POST /api/students
    private function createStudent() {
        $input = $this->getJsonInput();
        
        // Validate required fields
        $required_fields = ['first_name', 'last_name', 'email', 'roll_number', 'class'];
        $errors = [];
        
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                $errors[] = "Field '$field' is required";
            }
        }
        
        // Validate email format
        if (!empty($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        // Validate roll number format (assuming 7 digits)
        if (!empty($input['roll_number']) && !preg_match('/^\d{7}$/', $input['roll_number'])) {
            $errors[] = "Roll number must be 7 digits";
        }
        
        if (!empty($errors)) {
            $this->sendError(400, 'Validation failed', $errors);
        }
        
        // Check for duplicates
        $check_sql = "SELECT COUNT(*) FROM students WHERE email = ? OR roll_number = ?";
        $check_stmt = $this->pdo->prepare($check_sql);
        $check_stmt->execute([$input['email'], $input['roll_number']]);
        
        if ($check_stmt->fetchColumn() > 0) {
            $this->sendError(409, 'Student with this email or roll number already exists');
        }
        
        // Insert new student
        $sql = "
            INSERT INTO students (first_name, last_name, email, roll_number, class, 
                                date_of_birth, phone, address, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            $input['first_name'],
            $input['last_name'],
            $input['email'],
            $input['roll_number'],
            $input['class'],
            $input['date_of_birth'] ?? null,
            $input['phone'] ?? null,
            $input['address'] ?? null
        ]);
        
        if ($result) {
            $student_id = $this->pdo->lastInsertId();
            $this->sendResponse(201, [
                'success' => true,
                'message' => 'Student created successfully',
                'data' => ['student_id' => $student_id]
            ]);
        } else {
            $this->sendError(500, 'Failed to create student');
        }
    }
    
    // PUT /api/students/{id}
    private function updateStudent($id) {
        if (!is_numeric($id)) {
            $this->sendError(400, 'Invalid student ID');
        }
        
        // Check if student exists
        $check_stmt = $this->pdo->prepare("SELECT COUNT(*) FROM students WHERE student_id = ?");
        $check_stmt->execute([$id]);
        if ($check_stmt->fetchColumn() == 0) {
            $this->sendError(404, 'Student not found');
        }
        
        $input = $this->getJsonInput();
        
        // Build dynamic update query
        $update_fields = [];
        $params = [];
        
        $allowed_fields = ['first_name', 'last_name', 'email', 'phone', 'address', 'date_of_birth'];
        
        foreach ($allowed_fields as $field) {
            if (isset($input[$field])) {
                $update_fields[] = "$field = ?";
                $params[] = $input[$field];
            }
        }
        
        if (empty($update_fields)) {
            $this->sendError(400, 'No valid fields to update');
        }
        
        // Validate email if provided
        if (isset($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $this->sendError(400, 'Invalid email format');
        }
        
        $params[] = $id; // Add ID for WHERE clause
        
        $sql = "UPDATE students SET " . implode(', ', $update_fields) . ", updated_at = NOW() WHERE student_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($result) {
            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Student updated successfully'
            ]);
        } else {
            $this->sendError(500, 'Failed to update student');
        }
    }
    
    // DELETE /api/students/{id}
    private function deleteStudent($id) {
        if (!is_numeric($id)) {
            $this->sendError(400, 'Invalid student ID');
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Check if student exists and get info
            $student_stmt = $this->pdo->prepare("SELECT first_name, last_name FROM students WHERE student_id = ?");
            $student_stmt->execute([$id]);
            $student = $student_stmt->fetch();
            
            if (!$student) {
                $this->pdo->rollBack();
                $this->sendError(404, 'Student not found');
            }
            
            // Delete related records first (grades, attendance, etc.)
            $this->pdo->prepare("DELETE FROM grades WHERE student_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM attendance WHERE student_id = ?")->execute([$id]);
            
            // Delete the student
            $delete_stmt = $this->pdo->prepare("DELETE FROM students WHERE student_id = ?");
            $delete_result = $delete_stmt->execute([$id]);
            
            if ($delete_result && $delete_stmt->rowCount() > 0) {
                $this->pdo->commit();
                $this->sendResponse(200, [
                    'success' => true,
                    'message' => "Student {$student['first_name']} {$student['last_name']} deleted successfully"
                ]);
            } else {
                $this->pdo->rollBack();
                $this->sendError(500, 'Failed to delete student');
            }
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->sendError(500, 'Error during deletion: ' . $e->getMessage());
        }
    }
    
    private function getJsonInput() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError(400, 'Invalid JSON input');
        }
        
        return $data;
    }
    
    private function sendResponse($status_code, $data) {
        http_response_code($status_code);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    private function sendError($status_code, $message, $details = null) {
        $response = [
            'success' => false,
            'error' => $message
        ];
        
        if ($details !== null) {
            $response['details'] = $details;
        }
        
        $this->sendResponse($status_code, $response);
    }
}

// Initialize and handle the API request
$api = new StudentsAPI();
$api->handleRequest();
?>
```

### 3. Frontend Integration with AJAX

#### Create file: `student-management-spa.html`
```html
<!DOCTYPE html>
<html>
<head>
    <title>Student Management SPA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f7fa; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; margin-bottom: 30px; text-align: center; }
        .controls { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #333; }
        input, select, textarea { width: 100%; padding: 10px; border: 2px solid #e1e5e9; border-radius: 5px; font-size: 14px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        button { background: #667eea; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600; margin: 5px; }
        button:hover { background: #5a6fd8; transform: translateY(-1px); }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .students-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-top: 20px; }
        .student-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .student-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .student-name { font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px; }
        .student-info { color: #666; margin-bottom: 8px; }
        .student-actions { margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; }
        .loading { text-align: center; padding: 40px; color: #666; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #f5c6cb; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #c3e6cb; }
        .pagination { display: flex; justify-content: center; align-items: center; gap: 10px; margin: 20px 0; }
        .pagination button { padding: 8px 12px; font-size: 12px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { background: white; margin: 5% auto; padding: 30px; border-radius: 10px; width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto; }
        .close { float: right; font-size: 24px; font-weight: bold; color: #aaa; cursor: pointer; }
        .close:hover { color: #000; }
        .search-filters { display: grid; grid-template-columns: 2fr 1fr auto auto; gap: 15px; align-items: end; }
        .stats { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; }
        .stat-item { text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px; }
        .stat-number { font-size: 24px; font-weight: bold; color: #667eea; }
        .stat-label { font-size: 12px; color: #666; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Student Management System</h1>
            <p>Modern API-powered Single Page Application</p>
        </div>
        
        <!-- Statistics -->
        <div class="stats">
            <h3>📊 Dashboard Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" id="totalStudents">-</div>
                    <div class="stat-label">Total Students</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="totalClasses">-</div>
                    <div class="stat-label">Classes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="currentPage">-</div>
                    <div class="stat-label">Current Page</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="totalPages">-</div>
                    <div class="stat-label">Total Pages</div>
                </div>
            </div>
        </div>
        
        <!-- Controls -->
        <div class="controls">
            <div class="search-filters">
                <div>
                    <label for="searchInput">🔍 Search Students:</label>
                    <input type="text" id="searchInput" placeholder="Search by name, email, or roll number">
                </div>
                <div>
                    <label for="classFilter">Filter by Class:</label>
                    <select id="classFilter">
                        <option value="">All Classes</option>
                        <option value="XI-A">XI-A</option>
                        <option value="XI-B">XI-B</option>
                        <option value="XII-A">XII-A</option>
                        <option value="XII-B">XII-B</option>
                    </select>
                </div>
                <div>
                    <button onclick="searchStudents()" class="btn-success">Search</button>
                </div>
                <div>
                    <button onclick="openAddModal()" class="btn-success">+ Add Student</button>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <div id="messages"></div>
        
        <!-- Loading indicator -->
        <div id="loading" class="loading" style="display: none;">
            <h3>Loading students...</h3>
        </div>
        
        <!-- Students grid -->
        <div id="studentsContainer" class="students-grid"></div>
        
        <!-- Pagination -->
        <div class="pagination">
            <button onclick="previousPage()" id="prevBtn">← Previous</button>
            <span id="pageInfo">Page 1 of 1</span>
            <button onclick="nextPage()" id="nextBtn">Next →</button>
        </div>
    </div>
    
    <!-- Add/Edit Student Modal -->
    <div id="studentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add New Student</h2>
            
            <form id="studentForm">
                <input type="hidden" id="studentId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input type="text" id="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input type="text" id="lastName" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="rollNumber">Roll Number *</label>
                        <input type="text" id="rollNumber" pattern="[0-9]{7}" title="7 digits required" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="studentClass">Class *</label>
                        <select id="studentClass" required>
                            <option value="">Select Class</option>
                            <option value="XI-A">XI-A</option>
                            <option value="XI-B">XI-B</option>
                            <option value="XII-A">XII-A</option>
                            <option value="XII-B">XII-B</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dateOfBirth">Date of Birth</label>
                        <input type="date" id="dateOfBirth">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone">
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" rows="3"></textarea>
                </div>
                
                <div style="margin-top: 20px; text-align: right;">
                    <button type="button" onclick="closeModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn-success">Save Student</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // API configuration
        const API_BASE_URL = 'api/students.php';
        
        // State management
        let currentPage = 1;
        let totalPages = 1;
        let isLoading = false;
        
        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            loadStudents();
            setupEventListeners();
        });
        
        function setupEventListeners() {
            // Search on Enter key
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchStudents();
                }
            });
            
            // Form submission
            document.getElementById('studentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                saveStudent();
            });
            
            // Close modal on outside click
            window.onclick = function(event) {
                const modal = document.getElementById('studentModal');
                if (event.target === modal) {
                    closeModal();
                }
            }
        }
        
        // API calls
        async function apiCall(url, method = 'GET', data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                }
            };
            
            if (data) {
                options.body = JSON.stringify(data);
            }
            
            try {
                const response = await fetch(url, options);
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.error || `HTTP error! status: ${response.status}`);
                }
                
                return result;
            } catch (error) {
                console.error('API call failed:', error);
                throw error;
            }
        }
        
        // Load students with pagination and filters
        async function loadStudents(page = 1) {
            if (isLoading) return;
            
            isLoading = true;
            showLoading(true);
            
            try {
                const search = document.getElementById('searchInput').value;
                const classFilter = document.getElementById('classFilter').value;
                
                const params = new URLSearchParams({
                    page: page,
                    limit: 12,
                    search: search,
                    class: classFilter,
                    sort: 'first_name',
                    order: 'ASC'
                });
                
                const result = await apiCall(`${API_BASE_URL}?${params}`);
                
                if (result.success) {
                    displayStudents(result.data);
                    updatePagination(result.pagination);
                    updateStats(result.pagination);
                    currentPage = result.pagination.current_page;
                    totalPages = result.pagination.total_pages;
                } else {
                    showMessage('Failed to load students', 'error');
                }
                
            } catch (error) {
                showMessage('Error loading students: ' + error.message, 'error');
            } finally {
                isLoading = false;
                showLoading(false);
            }
        }
        
        // Display students in the grid
        function displayStudents(students) {
            const container = document.getElementById('studentsContainer');
            
            if (students.length === 0) {
                container.innerHTML = '<div class="loading"><h3>No students found</h3><p>Try adjusting your search criteria</p></div>';
                return;
            }
            
            container.innerHTML = students.map(student => `
                <div class="student-card" data-id="${student.student_id}">
                    <div class="student-name">${student.first_name} ${student.last_name}</div>
                    <div class="student-info"><strong>📧</strong> ${student.email}</div>
                    <div class="student-info"><strong>🎓</strong> Roll: ${student.roll_number}</div>
                    <div class="student-info"><strong>🏫</strong> Class: ${student.class}</div>
                    ${student.phone ? `<div class="student-info"><strong>📞</strong> ${student.phone}</div>` : ''}
                    ${student.date_of_birth ? `<div class="student-info"><strong>🎂</strong> ${formatDate(student.date_of_birth)}</div>` : ''}
                    <div class="student-actions">
                        <button onclick="viewStudent(${student.student_id})" class="btn-secondary">View Details</button>
                        <button onclick="editStudent(${student.student_id})" class="btn-success">Edit</button>
                        <button onclick="deleteStudent(${student.student_id}, '${student.first_name} ${student.last_name}')" class="btn-danger">Delete</button>
                    </div>
                </div>
            `).join('');
        }
        
        // Update pagination controls
        function updatePagination(pagination) {
            document.getElementById('pageInfo').textContent = 
                `Page ${pagination.current_page} of ${pagination.total_pages}`;
            
            document.getElementById('prevBtn').disabled = pagination.current_page <= 1;
            document.getElementById('nextBtn').disabled = pagination.current_page >= pagination.total_pages;
        }
        
        // Update dashboard statistics
        function updateStats(pagination) {
            document.getElementById('totalStudents').textContent = pagination.total_records;
            document.getElementById('currentPage').textContent = pagination.current_page;
            document.getElementById('totalPages').textContent = pagination.total_pages;
            
            // Calculate unique classes (simplified - in real app you'd get this from API)
            document.getElementById('totalClasses').textContent = '4';
        }
        
        // Search functionality
        function searchStudents() {
            currentPage = 1;
            loadStudents(currentPage);
        }
        
        // Pagination functions
        function previousPage() {
            if (currentPage > 1) {
                loadStudents(currentPage - 1);
            }
        }
        
        function nextPage() {
            if (currentPage < totalPages) {
                loadStudents(currentPage + 1);
            }
        }
        
        // Modal functions
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Student';
            document.getElementById('studentForm').reset();
            document.getElementById('studentId').value = '';
            document.getElementById('submitBtn').textContent = 'Add Student';
            document.getElementById('studentModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('studentModal').style.display = 'none';
        }
        
        // View student details
        async function viewStudent(id) {
            try {
                const result = await apiCall(`${API_BASE_URL}/${id}`);
                if (result.success) {
                    const student = result.data;
                    const detailsHtml = `
                        <h3>${student.first_name} ${student.last_name}</h3>
                        <p><strong>Email:</strong> ${student.email}</p>
                        <p><strong>Roll Number:</strong> ${student.roll_number}</p>
                        <p><strong>Class:</strong> ${student.class}</p>
                        <p><strong>Phone:</strong> ${student.phone || 'N/A'}</p>
                        <p><strong>Date of Birth:</strong> ${student.date_of_birth || 'N/A'}</p>
                        <p><strong>Address:</strong> ${student.address || 'N/A'}</p>
                        <p><strong>Academic Performance:</strong></p>
                        <ul>
                            <li>Total Grades: ${student.total_grades || 0}</li>
                            <li>Average Marks: ${student.average_marks ? Number(student.average_marks).toFixed(1) + '%' : 'N/A'}</li>
                            <li>Highest Score: ${student.highest_marks || 'N/A'}</li>
                            <li>Lowest Score: ${student.lowest_marks || 'N/A'}</li>
                        </ul>
                    `;
                    
                    showMessage(detailsHtml, 'success');
                }
            } catch (error) {
                showMessage('Error loading student details: ' + error.message, 'error');
            }
        }
        
        // Edit student
        async function editStudent(id) {
            try {
                const result = await apiCall(`${API_BASE_URL}/${id}`);
                if (result.success) {
                    const student = result.data;
                    
                    document.getElementById('modalTitle').textContent = 'Edit Student';
                    document.getElementById('studentId').value = student.student_id;
                    document.getElementById('firstName').value = student.first_name;
                    document.getElementById('lastName').value = student.last_name;
                    document.getElementById('email').value = student.email;
                    document.getElementById('rollNumber').value = student.roll_number;
                    document.getElementById('studentClass').value = student.class;
                    document.getElementById('dateOfBirth').value = student.date_of_birth || '';
                    document.getElementById('phone').value = student.phone || '';
                    document.getElementById('address').value = student.address || '';
                    document.getElementById('submitBtn').textContent = 'Update Student';
                    
                    document.getElementById('studentModal').style.display = 'block';
                }
            } catch (error) {
                showMessage('Error loading student for editing: ' + error.message, 'error');
            }
        }
        
        // Save student (create or update)
        async function saveStudent() {
            const studentId = document.getElementById('studentId').value;
            const isUpdate = studentId !== '';
            
            const studentData = {
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                roll_number: document.getElementById('rollNumber').value,
                class: document.getElementById('studentClass').value,
                date_of_birth: document.getElementById('dateOfBirth').value || null,
                phone: document.getElementById('phone').value || null,
                address: document.getElementById('address').value || null
            };
            
            try {
                const url = isUpdate ? `${API_BASE_URL}/${studentId}` : API_BASE_URL;
                const method = isUpdate ? 'PUT' : 'POST';
                
                const result = await apiCall(url, method, studentData);
                
                if (result.success) {
                    showMessage(result.message, 'success');
                    closeModal();
                    loadStudents(currentPage); // Reload current page
                } else {
                    showMessage('Failed to save student', 'error');
                }
                
            } catch (error) {
                if (error.message.includes('details')) {
                    // Handle validation errors
                    showMessage('Validation Error: ' + error.message, 'error');
                } else {
                    showMessage('Error saving student: ' + error.message, 'error');
                }
            }
        }
        
        // Delete student
        async function deleteStudent(id, name) {
            if (!confirm(`Are you sure you want to delete ${name}? This action cannot be undone.`)) {
                return;
            }
            
            try {
                const result = await apiCall(`${API_BASE_URL}/${id}`, 'DELETE');
                
                if (result.success) {
                    showMessage(result.message, 'success');
                    loadStudents(currentPage); // Reload current page
                } else {
                    showMessage('Failed to delete student', 'error');
                }
                
            } catch (error) {
                showMessage('Error deleting student: ' + error.message, 'error');
            }
        }
        
        // Utility functions
        function showMessage(message, type = 'success') {
            const messagesContainer = document.getElementById('messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = type;
            messageDiv.innerHTML = message;
            
            messagesContainer.innerHTML = '';
            messagesContainer.appendChild(messageDiv);
            
            // Auto-remove message after 5 seconds
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
        
        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
        }
        
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    </script>
</body>
</html>
```

### 4. API Authentication and Security

#### Create file: `api/auth.php`
```php
<?php
class APIAuthentication {
    private $pdo;
    private $secret_key = 'your-secret-key-here'; // In production, use environment variable
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Generate JWT token
    public function generateToken($user_data) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'user_id' => $user_data['user_id'],
            'username' => $user_data['username'],
            'role' => $user_data['role'],
            'exp' => time() + (24 * 60 * 60) // 24 hours
        ]);
        
        $headerEncoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $payloadEncoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, $this->secret_key, true);
        $signatureEncoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
    }
    
    // Verify JWT token
    public function verifyToken($token) {
        if (!$token) {
            return false;
        }
        
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        
        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;
        
        $signature = base64_decode(str_replace(['-', '_'], ['+', '/'], $signatureEncoded));
        $expectedSignature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, $this->secret_key, true);
        
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
        
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payloadEncoded)), true);
        
        if ($payload['exp'] < time()) {
            return false; // Token expired
        }
        
        return $payload;
    }
    
    // API login
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("
            SELECT user_id, username, email, password_hash, role 
            FROM users 
            WHERE username = ? OR email = ?
        ");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new Exception('Invalid credentials');
        }
        
        $token = $this->generateToken($user);
        
        // Update last login
        $stmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);
        
        return [
            'token' => $token,
            'user' => [
                'id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ];
    }
    
    // Middleware for protected routes
    public function requireAuth($required_role = null) {
        $headers = getallheaders();
        $token = null;
        
        // Check Authorization header
        if (isset($headers['Authorization'])) {
            $auth_header = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
                $token = $matches[1];
            }
        }
        
        $payload = $this->verifyToken($token);
        if (!$payload) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        // Check role permissions
        if ($required_role && !$this->hasPermission($payload['role'], $required_role)) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        
        return $payload;
    }
    
    private function hasPermission($user_role, $required_role) {
        $role_hierarchy = ['student' => 1, 'teacher' => 2, 'admin' => 3];
        return $role_hierarchy[$user_role] >= $role_hierarchy[$required_role];
    }
}
?>
```

---

## Practical Activities

### Activity 1: Complete RESTful API Testing (45 minutes)

#### Create file: `api-tester.html`
```html
<!DOCTYPE html>
<html>
<head>
    <title>API Testing Tool</title>
    <style>
        body { font-family: monospace; max-width: 1000px; margin: 20px auto; padding: 20px; }
        .test-section { background: #f5f5f5; padding: 20px; margin: 15px 0; border-radius: 8px; }
        .method { display: inline-block; padding: 5px 10px; border-radius: 4px; color: white; font-weight: bold; margin-right: 10px; }
        .get { background: #28a745; }
        .post { background: #007bff; }
        .put { background: #ffc107; color: black; }
        .delete { background: #dc3545; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background: #0056b3; }
        .response { background: #000; color: #0f0; padding: 15px; border-radius: 4px; margin: 10px 0; overflow-x: auto; }
        .error { background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; border: 1px solid #ef5350; }
    </style>
</head>
<body>
    <h1>🔧 API Testing Tool</h1>
    
    <div class="test-section">
        <span class="method get">GET</span>
        <strong>Get All Students</strong>
        <div>
            <input type="text" id="searchParam" placeholder="Search term (optional)">
            <input type="text" id="classParam" placeholder="Class filter (optional)">
            <button onclick="getAllStudents()">Test GET /api/students</button>
        </div>
        <div id="getAllResponse" class="response"></div>
    </div>
    
    <div class="test-section">
        <span class="method get">GET</span>
        <strong>Get Single Student</strong>
        <div>
            <input type="number" id="getStudentId" placeholder="Student ID">
            <button onclick="getStudent()">Test GET /api/students/{id}</button>
        </div>
        <div id="getStudentResponse" class="response"></div>
    </div>
    
    <div class="test-section">
        <span class="method post">POST</span>
        <strong>Create Student</strong>
        <div>
            <textarea id="createStudentData" rows="8" placeholder="JSON data for new student">
{
    "first_name": "Test",
    "last_name": "Student",
    "email": "test@example.com",
    "roll_number": "1234567",
    "class": "XII-A",
    "date_of_birth": "2007-01-15",
    "phone": "9876543210",
    "address": "123 Test Street"
}
            </textarea>
            <button onclick="createStudent()">Test POST /api/students</button>
        </div>
        <div id="createStudentResponse" class="response"></div>
    </div>
    
    <div class="test-section">
        <span class="method put">PUT</span>
        <strong>Update Student</strong>
        <div>
            <input type="number" id="updateStudentId" placeholder="Student ID to update">
            <textarea id="updateStudentData" rows="6" placeholder="JSON data to update">
{
    "first_name": "Updated",
    "last_name": "Name",
    "phone": "9876543211"
}
            </textarea>
            <button onclick="updateStudent()">Test PUT /api/students/{id}</button>
        </div>
        <div id="updateStudentResponse" class="response"></div>
    </div>
    
    <div class="test-section">
        <span class="method delete">DELETE</span>
        <strong>Delete Student</strong>
        <div>
            <input type="number" id="deleteStudentId" placeholder="Student ID to delete">
            <button onclick="deleteStudent()">Test DELETE /api/students/{id}</button>
        </div>
        <div id="deleteStudentResponse" class="response"></div>
    </div>

    <script>
        const API_BASE = 'api/students.php';
        
        async function makeApiCall(url, method = 'GET', data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            
            if (data) {
                options.body = JSON.stringify(data);
            }
            
            try {
                const response = await fetch(url, options);
                const result = await response.json();
                
                return {
                    status: response.status,
                    data: result,
                    success: response.ok
                };
            } catch (error) {
                return {
                    status: 0,
                    data: { error: error.message },
                    success: false
                };
            }
        }
        
        function displayResponse(elementId, response) {
            const element = document.getElementById(elementId);
            const formatted = JSON.stringify(response, null, 2);
            element.textContent = formatted;
            
            if (!response.success) {
                element.className = 'error';
            } else {
                element.className = 'response';
            }
        }
        
        async function getAllStudents() {
            const search = document.getElementById('searchParam').value;
            const classFilter = document.getElementById('classParam').value;
            
            let url = API_BASE;
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (classFilter) params.append('class', classFilter);
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            const response = await makeApiCall(url);
            displayResponse('getAllResponse', response);
        }
        
        async function getStudent() {
            const id = document.getElementById('getStudentId').value;
            if (!id) {
                alert('Please enter a student ID');
                return;
            }
            
            const response = await makeApiCall(`${API_BASE}/${id}`);
            displayResponse('getStudentResponse', response);
        }
        
        async function createStudent() {
            const dataText = document.getElementById('createStudentData').value;
            
            try {
                const data = JSON.parse(dataText);
                const response = await makeApiCall(API_BASE, 'POST', data);
                displayResponse('createStudentResponse', response);
            } catch (error) {
                displayResponse('createStudentResponse', {
                    success: false,
                    data: { error: 'Invalid JSON: ' + error.message }
                });
            }
        }
        
        async function updateStudent() {
            const id = document.getElementById('updateStudentId').value;
            const dataText = document.getElementById('updateStudentData').value;
            
            if (!id) {
                alert('Please enter a student ID');
                return;
            }
            
            try {
                const data = JSON.parse(dataText);
                const response = await makeApiCall(`${API_BASE}/${id}`, 'PUT', data);
                displayResponse('updateStudentResponse', response);
            } catch (error) {
                displayResponse('updateStudentResponse', {
                    success: false,
                    data: { error: 'Invalid JSON: ' + error.message }
                });
            }
        }
        
        async function deleteStudent() {
            const id = document.getElementById('deleteStudentId').value;
            
            if (!id) {
                alert('Please enter a student ID');
                return;
            }
            
            if (!confirm('Are you sure you want to delete this student?')) {
                return;
            }
            
            const response = await makeApiCall(`${API_BASE}/${id}`, 'DELETE');
            displayResponse('deleteStudentResponse', response);
        }
    </script>
</body>
</html>
```

---

## Exercises

### Exercise 1: Grades API Development (30 minutes)
Create `api/grades.php` that:
- Manages student grades with full CRUD operations
- Implements grade calculations and GPA computation
- Provides subject-wise performance analytics
- Includes grade history and trending data

### Exercise 2: API Rate Limiting (25 minutes)
Build rate limiting middleware that:
- Tracks API requests per IP address
- Implements sliding window rate limiting
- Returns appropriate HTTP status codes
- Logs excessive usage attempts

### Exercise 3: Real-time Notifications (35 minutes)
Develop notification system featuring:
- Server-sent events for real-time updates
- Student enrollment notifications
- Grade posting alerts
- System maintenance announcements

---

## Assessment

### Knowledge Check:
1. What are the main HTTP methods used in REST APIs and their purposes?
2. How do you handle JSON data in PHP?
3. What is the purpose of CORS headers in API development?
4. How do you implement proper error handling in APIs?
5. What are JWT tokens and how do they work for authentication?

### Practical Assessment:
- [ ] Created fully functional RESTful API with all CRUD operations
- [ ] Implemented proper error handling and HTTP status codes
- [ ] Used JSON effectively for data exchange
- [ ] Applied security measures including input validation
- [ ] Demonstrated understanding of API best practices

---

## Homework Assignment

### **Complete School Management API Suite**
Develop a comprehensive API system (`api/`) with:

#### **Multiple Resource APIs:**
1. **Students API** (extend existing)
2. **Teachers API** with department management
3. **Subjects API** with prerequisite tracking
4. **Grades API** with advanced analytics
5. **Authentication API** with JWT tokens

#### **Advanced Features:**
6. **API Documentation** (auto-generated or manual)
7. **Rate Limiting** and throttling mechanisms
8. **Caching** for improved performance
9. **Webhook Support** for external integrations
10. **API Versioning** strategy

#### **Frontend Integration:**
11. **Complete SPA** consuming all APIs
12. **Real-time Updates** using WebSockets or SSE
13. **Offline Support** with service workers
14. **Progressive Web App** features

**Due:** Next class session  
**Assessment:** API design, security implementation, performance optimization, documentation quality

---

## Next Lesson Preview
**Lesson 15: Project Development & Deployment**
- Complete web application architecture
- Production deployment strategies
- Performance optimization techniques
- Final project presentation and evaluation

---

*Modern web development relies heavily on APIs for creating scalable, maintainable applications. Master these concepts to build professional-grade systems.*
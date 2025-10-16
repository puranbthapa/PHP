<?php
/**
 * REST API Implementation
 * Complete REST API for Student Management System
 * 
 * @author Puran Bahadur Thapa
 * @website https://eastlinknet.np
 * @contact WhatsApp: +9779801901140
 * @version 1.0.0
 * @date October 2025
 */

// Include required files
require_once '../security-classes.php';

// Database configuration
$config = [
    'host' => 'localhost',
    'dbname' => 'school_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];

// JWT Secret Key (use environment variable in production)
$jwtSecret = 'your-super-secret-jwt-key-change-in-production';

// Database connection
try {
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => 'Database connection failed']);
    exit;
}

// API Router Class
class APIRouter {
    private $routes = [];
    private $middleware = [];
    
    public function __construct() {
        // Set JSON headers
        header('Content-Type: application/json; charset=utf-8');
        
        // Enable CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
    
    public function addRoute($method, $pattern, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }
    
    public function addMiddleware($middleware) {
        $this->middleware[] = $middleware;
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path
        $basePath = '/Class/xi/PHP/api';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        try {
            // Run middleware
            foreach ($this->middleware as $middleware) {
                $middleware();
            }
            
            // Find matching route
            foreach ($this->routes as $route) {
                if ($route['method'] === $method && $this->matchPattern($route['pattern'], $uri, $params)) {
                    return call_user_func_array($route['handler'], $params);
                }
            }
            
            // No route found
            $this->sendError(404, 'Endpoint not found');
            
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            $this->sendError(500, 'Internal server error');
        }
    }
    
    private function matchPattern($pattern, $uri, &$params) {
        $params = [];
        
        // Convert pattern to regex
        $regex = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        
        if (preg_match($regex, $uri, $matches)) {
            $params = array_slice($matches, 1);
            return true;
        }
        
        return false;
    }
    
    public function sendJSON($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public function sendError($statusCode, $message, $details = null) {
        $response = [
            'error' => true,
            'message' => $message,
            'status' => $statusCode
        ];
        
        if ($details) {
            $response['details'] = $details;
        }
        
        $this->sendJSON($response, $statusCode);
    }
    
    public function getJSONInput() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError(400, 'Invalid JSON input');
        }
        
        return $data ?: [];
    }
}

// JWT Authentication Class
class JWTAuth {
    private $secretKey;
    
    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }
    
    public function generateToken($payload, $expiration = 3600) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiration;
        $payload = json_encode($payload);
        
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $this->secretKey, true);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }
    
    public function validateToken($token) {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return false;
        }
        
        [$header, $payload, $signature] = $parts;
        
        // Verify signature
        $validSignature = hash_hmac('sha256', $header . "." . $payload, $this->secretKey, true);
        $validSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($validSignature));
        
        if (!hash_equals($signature, $validSignature)) {
            return false;
        }
        
        // Decode payload
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payload)), true);
        
        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }
        
        return $payload;
    }
}

// Initialize router and auth
$router = new APIRouter();
$jwtAuth = new JWTAuth($jwtSecret);

// Authentication middleware
function authMiddleware() {
    global $router, $jwtAuth;
    
    // Skip auth for login endpoint
    if (strpos($_SERVER['REQUEST_URI'], '/auth/login') !== false) {
        return;
    }
    
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        $router->sendError(401, 'Authorization token required');
    }
    
    $token = $matches[1];
    $payload = $jwtAuth->validateToken($token);
    
    if (!$payload) {
        $router->sendError(401, 'Invalid or expired token');
    }
    
    // Store user info globally
    $GLOBALS['api_user'] = $payload;
}

// Rate limiting middleware
function rateLimitMiddleware() {
    global $router;
    
    session_start();
    $now = time();
    $window = 3600; // 1 hour
    $maxRequests = 100;
    
    if (!isset($_SESSION['api_requests'])) {
        $_SESSION['api_requests'] = [];
    }
    
    // Clean old requests
    $_SESSION['api_requests'] = array_filter($_SESSION['api_requests'], function($timestamp) use ($now, $window) {
        return ($now - $timestamp) < $window;
    });
    
    if (count($_SESSION['api_requests']) >= $maxRequests) {
        header('X-RateLimit-Remaining: 0');
        header('X-RateLimit-Reset: ' . ($now + $window));
        $router->sendError(429, 'Rate limit exceeded');
    }
    
    $_SESSION['api_requests'][] = $now;
    header('X-RateLimit-Remaining: ' . ($maxRequests - count($_SESSION['api_requests'])));
}

// Add middleware
$router->addMiddleware('rateLimitMiddleware');
$router->addMiddleware('authMiddleware');

// Auth endpoints
$router->addRoute('POST', '/auth/login', function() {
    global $router, $pdo, $jwtAuth;
    
    $input = $router->getJSONInput();
    
    if (empty($input['email']) || empty($input['password'])) {
        $router->sendError(400, 'Email and password required');
    }
    
    // Demo user for testing (use proper authentication in production)
    if ($input['email'] === 'admin@school.com' && $input['password'] === 'admin123') {
        $token = $jwtAuth->generateToken([
            'user_id' => 1,
            'email' => 'admin@school.com',
            'role' => 'admin'
        ], 86400); // 24 hours
        
        $router->sendJSON([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'expires_in' => 86400
        ]);
    }
    
    $router->sendError(401, 'Invalid credentials');
});

// Student CRUD endpoints
$router->addRoute('GET', '/students', function() {
    global $router, $pdo;
    
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = min(100, max(1, (int)($_GET['limit'] ?? 20)));
    $search = $_GET['search'] ?? '';
    
    $offset = ($page - 1) * $limit;
    
    $whereClause = "WHERE deleted_at IS NULL";
    $params = [];
    
    if (!empty($search)) {
        $whereClause .= " AND (name LIKE ? OR email LIKE ?)";
        $searchTerm = "%$search%";
        $params = [$searchTerm, $searchTerm];
    }
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM students $whereClause";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = $countStmt->fetch()['total'];
    
    // Get data
    $dataSql = "SELECT id, name, email, age, grade, total_points, created_at FROM students $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $dataParams = array_merge($params, [$limit, $offset]);
    $dataStmt = $pdo->prepare($dataSql);
    $dataStmt->execute($dataParams);
    $students = $dataStmt->fetchAll();
    
    $router->sendJSON([
        'success' => true,
        'data' => $students,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total,
            'total_pages' => ceil($total / $limit),
            'has_next' => $page < ceil($total / $limit),
            'has_prev' => $page > 1
        ]
    ]);
});

$router->addRoute('GET', '/students/{id}', function($id) {
    global $router, $pdo;
    
    if (!is_numeric($id)) {
        $router->sendError(400, 'Invalid student ID');
    }
    
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ? AND deleted_at IS NULL");
    $stmt->execute([$id]);
    $student = $stmt->fetch();
    
    if (!$student) {
        $router->sendError(404, 'Student not found');
    }
    
    $router->sendJSON([
        'success' => true,
        'data' => $student
    ]);
});

$router->addRoute('POST', '/students', function() {
    global $router, $pdo;
    
    $data = $router->getJSONInput();
    
    // Validate required fields
    $required = ['name', 'email', 'age', 'grade'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $router->sendError(400, "Field '$field' is required");
        }
    }
    
    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $router->sendError(400, 'Invalid email format');
    }
    
    // Check duplicate email
    $checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
    $checkStmt->execute([$data['email']]);
    if ($checkStmt->fetch()) {
        $router->sendError(409, 'Email already exists');
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, age, grade, total_points) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['email'],
            (int)$data['age'],
            $data['grade'],
            (int)($data['total_points'] ?? 0)
        ]);
        
        $studentId = $pdo->lastInsertId();
        
        $router->sendJSON([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => ['id' => (int)$studentId]
        ], 201);
        
    } catch (PDOException $e) {
        $router->sendError(500, 'Failed to create student');
    }
});

$router->addRoute('PUT', '/students/{id}', function($id) {
    global $router, $pdo;
    
    if (!is_numeric($id)) {
        $router->sendError(400, 'Invalid student ID');
    }
    
    $checkStmt = $pdo->prepare("SELECT id FROM students WHERE id = ? AND deleted_at IS NULL");
    $checkStmt->execute([$id]);
    if (!$checkStmt->fetch()) {
        $router->sendError(404, 'Student not found');
    }
    
    $data = $router->getJSONInput();
    
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $router->sendError(400, 'Invalid email format');
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, age = ?, grade = ?, total_points = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([
            $data['name'],
            $data['email'],
            (int)$data['age'],
            $data['grade'],
            (int)($data['total_points'] ?? 0),
            $id
        ]);
        
        $router->sendJSON([
            'success' => true,
            'message' => 'Student updated successfully'
        ]);
        
    } catch (PDOException $e) {
        $router->sendError(500, 'Failed to update student');
    }
});

$router->addRoute('DELETE', '/students/{id}', function($id) {
    global $router, $pdo;
    
    if (!is_numeric($id)) {
        $router->sendError(400, 'Invalid student ID');
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE students SET deleted_at = NOW() WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() === 0) {
            $router->sendError(404, 'Student not found');
        }
        
        $router->sendJSON([
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
        
    } catch (PDOException $e) {
        $router->sendError(500, 'Failed to delete student');
    }
});

// Health check endpoint
$router->addRoute('GET', '/health', function() {
    global $router;
    
    $router->sendJSON([
        'status' => 'healthy',
        'timestamp' => time(),
        'version' => '1.0.0'
    ]);
});

// API info endpoint
$router->addRoute('GET', '/', function() {
    global $router;
    
    $router->sendJSON([
        'name' => 'Student Management API',
        'version' => '1.0.0',
        'endpoints' => [
            'POST /auth/login' => 'Login and get JWT token',
            'GET /students' => 'Get all students (paginated)',
            'GET /students/{id}' => 'Get specific student',
            'POST /students' => 'Create new student',
            'PUT /students/{id}' => 'Update student',
            'DELETE /students/{id}' => 'Delete student',
            'GET /health' => 'Health check'
        ]
    ]);
});

// Dispatch the request
$router->dispatch();
?>
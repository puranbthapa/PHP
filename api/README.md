# API Testing Guide

---
**Developer:** Puran Bahadur Thapa  
**Website:** https://eastlinknet.np  
**Contact:** WhatsApp: +9779801901140  
---

## Student Management REST API

### Base URL
```
http://localhost/Class/xi/PHP/api/
```

### Authentication
First, get a JWT token by logging in:

```bash
curl -X POST http://localhost/Class/xi/PHP/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@school.com",
    "password": "admin123"
  }'
```

Use the returned token in subsequent requests:
```
Authorization: Bearer YOUR_JWT_TOKEN_HERE
```

### API Endpoints

#### 1. Get API Information
```bash
curl -X GET http://localhost/Class/xi/PHP/api/
```

#### 2. Health Check
```bash
curl -X GET http://localhost/Class/xi/PHP/api/health
```

#### 3. Get All Students
```bash
# Basic request
curl -X GET http://localhost/Class/xi/PHP/api/students \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"

# With pagination and search
curl -X GET "http://localhost/Class/xi/PHP/api/students?page=1&limit=10&search=alice" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

#### 4. Get Single Student
```bash
curl -X GET http://localhost/Class/xi/PHP/api/students/1 \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

#### 5. Create New Student
```bash
curl -X POST http://localhost/Class/xi/PHP/api/students \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "age": 18,
    "grade": "XII-A",
    "total_points": 85
  }'
```

#### 6. Update Student
```bash
curl -X PUT http://localhost/Class/xi/PHP/api/students/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "name": "John Smith",
    "email": "johnsmith@example.com",
    "age": 18,
    "grade": "XII-B",
    "total_points": 90
  }'
```

#### 7. Delete Student
```bash
curl -X DELETE http://localhost/Class/xi/PHP/api/students/1 \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Testing with PowerShell (Windows)

#### Get Token
```powershell
$response = Invoke-RestMethod -Uri "http://localhost/Class/xi/PHP/api/auth/login" -Method POST -ContentType "application/json" -Body '{"email":"admin@school.com","password":"admin123"}'
$token = $response.token
```

#### Use Token for API Calls
```powershell
$headers = @{ Authorization = "Bearer $token" }

# Get all students
Invoke-RestMethod -Uri "http://localhost/Class/xi/PHP/api/students" -Method GET -Headers $headers

# Create student
$body = @{
    name = "Jane Doe"
    email = "jane@example.com" 
    age = 17
    grade = "XII-A"
    total_points = 95
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost/Class/xi/PHP/api/students" -Method POST -ContentType "application/json" -Headers $headers -Body $body
```

### Expected Response Format

#### Success Response
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "age": 17,
    "grade": "XII-A",
    "total_points": 95,
    "created_at": "2025-01-01 12:00:00"
  }
}
```

#### Error Response
```json
{
  "error": true,
  "message": "Student not found",
  "status": 404
}
```

#### Paginated Response
```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 50,
    "total_pages": 3,
    "has_next": true,
    "has_prev": false
  }
}
```

### HTTP Status Codes Used
- `200` - Success
- `201` - Created
- `400` - Bad Request (validation errors)
- `401` - Unauthorized (missing/invalid token)
- `404` - Not Found
- `409` - Conflict (duplicate email)
- `429` - Too Many Requests (rate limited)
- `500` - Internal Server Error

### Rate Limiting
- **Limit**: 100 requests per hour per IP
- **Headers**: 
  - `X-RateLimit-Remaining`: Number of requests left
  - `X-RateLimit-Reset`: Unix timestamp when limit resets

### Security Features
- JWT-based authentication
- Rate limiting per IP address
- Input validation and sanitization
- CORS headers for cross-origin requests
- SQL injection prevention with prepared statements
- XSS prevention in JSON responses

### Testing Checklist
- [ ] API returns correct HTTP status codes
- [ ] Authentication works (valid/invalid tokens)
- [ ] Rate limiting blocks excessive requests
- [ ] Input validation catches invalid data
- [ ] Pagination works correctly
- [ ] Search functionality works
- [ ] CORS headers allow cross-origin requests
- [ ] Error messages are informative but not revealing sensitive info
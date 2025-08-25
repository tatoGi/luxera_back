# API Authentication Documentation

This document describes the API endpoints for user authentication and profile management.

## Base URL
All API endpoints are prefixed with `/api`

## Authentication Endpoints

### 1. User Login
**POST** `/api/login`

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "user@example.com",
        "phone": "+1234567890",
        "address": "123 Main St"
    }
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Invalid email or password"
}
```

**Validation Error Response (422):**
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### 2. User Registration
**POST** `/api/register`

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "address": "123 Main St",
    "phone": "+1234567890",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Registration successful",
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "user@example.com",
        "phone": "+1234567890",
        "address": "123 Main St"
    }
}
```

**Validation Error Response (422):**
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password confirmation does not match."]
    }
}
```

### 3. User Logout
**POST** `/api/logout`

**Headers:**
```
Authorization: Bearer {session_token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Logout successful"
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "User not authenticated"
}
```

## Profile Management Endpoints

### 4. Get User Profile
**GET** `/api/profile`

**Headers:**
```
Authorization: Bearer {session_token}
```

**Success Response (200):**
```json
{
    "success": true,
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "user@example.com",
        "phone": "+1234567890",
        "address": "123 Main St"
    }
}
```

### 5. Update User Profile
**PUT** `/api/profile`

**Headers:**
```
Authorization: Bearer {session_token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Smith",
    "email": "john.smith@example.com",
    "phone": "+1234567890",
    "address": "456 Oak Ave",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Smith",
        "email": "john.smith@example.com",
        "phone": "+1234567890",
        "address": "456 Oak Ave"
    }
}
```

## Authentication Notes

- The API uses session-based authentication with the `webuser` guard
- Protected routes require the user to be authenticated
- All responses include a `success` boolean flag and appropriate HTTP status codes
- Validation errors return detailed field-specific error messages
- Password fields are automatically hashed before storage

## Testing the API

You can test these endpoints using tools like:
- Postman
- cURL
- Insomnia
- Any HTTP client

### Example cURL commands:

**Login:**
```bash
curl -X POST http://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'
```

**Register:**
```bash
curl -X POST http://your-domain.com/api/register \
  -H "Content-Type: application/json" \
  -d '{"first_name":"John","last_name":"Doe","email":"user@example.com","password":"password123","password_confirmation":"password123","phone":"+1234567890","address":"123 Main St"}'
```

**Get Profile (requires authentication):**
```bash
curl -X GET http://your-domain.com/api/profile \
  -H "Authorization: Bearer {session_token}"
``` 

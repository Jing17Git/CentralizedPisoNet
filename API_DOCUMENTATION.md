# CentralizedPisoNet API Documentation

## Overview
This API allows external PisoNet machines to communicate with the centralized management system. All API requests require authentication via API token.

## Base URL
```
http://localhost:8000/api
```

## Authentication
All API requests must include an API token in the header:
```
X-API-Token: your_api_token_here
```

Or as a query parameter:
```
?api_token=your_api_token_here
```

**Default Token (Change in Production):** `pisonet_secure_token_2024_change_in_production`

---

## Machine Management API

### 1. Get All Machines
**Endpoint:** `GET /api/machines`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "machine_name": "PisoNet Station 1",
      "machine_code": "PC-01",
      "ip_address": "192.168.1.101",
      "location": "Main Floor - Left Side",
      "status": "online"
    }
  ],
  "count": 5
}
```

### 2. Get Specific Machine
**Endpoint:** `GET /api/machines/{machineCode}`

**Example:** `GET /api/machines/PC-01`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "machine_name": "PisoNet Station 1",
    "machine_code": "PC-01",
    "ip_address": "192.168.1.101",
    "location": "Main Floor - Left Side",
    "status": "online"
  }
}
```

### 3. Update Machine Status
**Endpoint:** `POST /api/machines/{machineCode}/status`

**Request Body:**
```json
{
  "status": "online"
}
```

**Valid Status Values:** `online`, `offline`, `maintenance`

**Response:**
```json
{
  "success": true,
  "message": "Machine status updated successfully",
  "data": { ... }
}
```

### 4. Machine Heartbeat
**Endpoint:** `POST /api/machines/{machineCode}/heartbeat`

**Description:** Send periodic heartbeat to indicate machine is online

**Response:**
```json
{
  "success": true,
  "message": "Heartbeat received",
  "timestamp": "2024-01-15T10:30:00.000000Z"
}
```

---

## Session Management API

### 1. Start Session
**Endpoint:** `POST /api/sessions/start`

**Request Body:**
```json
{
  "machine_code": "PC-01",
  "user_name": "John Doe",
  "minutes": 30
}
```

**Response:**
```json
{
  "success": true,
  "message": "Session started successfully",
  "data": {
    "session_id": "SES-ABC123XYZ",
    "pc_unit_number": 1,
    "user_session_name": "John Doe",
    "start_time": "2024-01-15T10:00:00",
    "remaining_time": 30,
    "status": "Active"
  }
}
```

### 2. End Session
**Endpoint:** `POST /api/sessions/{sessionId}/end`

**Example:** `POST /api/sessions/SES-ABC123XYZ/end`

**Response:**
```json
{
  "success": true,
  "message": "Session ended successfully",
  "data": { ... }
}
```

### 3. Extend Session
**Endpoint:** `POST /api/sessions/{sessionId}/extend`

**Request Body:**
```json
{
  "additional_minutes": 15
}
```

**Response:**
```json
{
  "success": true,
  "message": "Session extended successfully",
  "data": { ... }
}
```

### 4. Get Session Details
**Endpoint:** `GET /api/sessions/{sessionId}`

**Response:**
```json
{
  "success": true,
  "data": {
    "session_id": "SES-ABC123XYZ",
    "pc_unit_number": 1,
    "user_session_name": "John Doe",
    "start_time": "2024-01-15T10:00:00",
    "remaining_time": 25,
    "status": "Active"
  }
}
```

### 5. Get Active Session by Machine
**Endpoint:** `GET /api/sessions/machine/{machineCode}`

**Example:** `GET /api/sessions/machine/PC-01`

**Response:**
```json
{
  "success": true,
  "data": { ... }
}
```

---

## Coin Insert API

### 1. Record Coin Insert
**Endpoint:** `POST /api/coins/insert`

**Request Body:**
```json
{
  "machine_code": "PC-01",
  "coins_inserted": 2,
  "minutes_purchased": 10,
  "amount": 10.00
}
```

**Response:**
```json
{
  "success": true,
  "message": "Coin transaction recorded successfully",
  "data": {
    "id": 123,
    "machine_id": 1,
    "coins_inserted": 2,
    "minutes_purchased": 10,
    "amount": 10.00,
    "created_at": "2024-01-15T10:00:00"
  }
}
```

### 2. Get Today's Coins for Machine
**Endpoint:** `GET /api/coins/machine/{machineCode}/today`

**Example:** `GET /api/coins/machine/PC-01/today`

**Response:**
```json
{
  "success": true,
  "data": {
    "transactions": [ ... ],
    "summary": {
      "total_coins": 45,
      "total_amount": 225.00,
      "total_minutes": 225,
      "transaction_count": 15
    }
  }
}
```

---

## System Status API

### Check System Status
**Endpoint:** `GET /api/system/status`

**Response:**
```json
{
  "success": true,
  "status": "online",
  "timestamp": "2024-01-15T10:30:00.000000Z",
  "version": "1.0.0"
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing API token."
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Machine not found"
}
```

### 400 Bad Request
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

---

## Rate Limiting
- Default: 60 requests per minute per IP
- Exceeded limit returns 429 Too Many Requests

---

## Example Usage (cURL)

### Start a Session
```bash
curl -X POST http://localhost:8000/api/sessions/start \
  -H "X-API-Token: pisonet_secure_token_2024_change_in_production" \
  -H "Content-Type: application/json" \
  -d '{
    "machine_code": "PC-01",
    "user_name": "John Doe",
    "minutes": 30
  }'
```

### Record Coin Insert
```bash
curl -X POST http://localhost:8000/api/coins/insert \
  -H "X-API-Token: pisonet_secure_token_2024_change_in_production" \
  -H "Content-Type: application/json" \
  -d '{
    "machine_code": "PC-01",
    "coins_inserted": 2,
    "minutes_purchased": 10,
    "amount": 10.00
  }'
```

### Machine Heartbeat
```bash
curl -X POST http://localhost:8000/api/machines/PC-01/heartbeat \
  -H "X-API-Token: pisonet_secure_token_2024_change_in_production"
```

---

## Best Practices

1. **Keep Token Secure:** Never expose API token in client-side code
2. **Send Heartbeats:** Send machine heartbeat every 30-60 seconds
3. **Handle Errors:** Always check response status and handle errors gracefully
4. **Validate Data:** Validate all data before sending to API
5. **Use HTTPS:** In production, always use HTTPS for API requests

---

## Support
For technical support or questions, contact the system administrator.

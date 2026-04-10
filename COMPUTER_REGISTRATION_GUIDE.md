# Computer Registration Guide

## How to Register Each Computer with IP Address

### Step 1: Find Computer's IP Address

**On Windows:**
1. Open Command Prompt (cmd)
2. Type: `ipconfig`
3. Look for "IPv4 Address" under your network adapter
4. Example: `192.168.1.101`

**On Linux:**
1. Open Terminal
2. Type: `ip addr` or `ifconfig`
3. Look for "inet" address
4. Example: `192.168.1.101`

### Step 2: Register Computer in Admin Panel

1. Login to admin panel: `http://your-server-ip/login`
   - Email: richarddbautista1@gmail.com
   - Password: admin123

2. Navigate to **PC Units** in the sidebar

3. Click **"+ Add PC Unit"** button

4. Fill in the form:
   - **PC Number**: Unique identifier (e.g., PC-01, PC-02, PC-03)
   - **Branch ID**: Location identifier (default: 1)
   - **IP Address**: The computer's IP address (REQUIRED)
   - **Status**: 
     - Available - Ready for use
     - In Use - Currently occupied
     - Offline - Not operational

5. Click **"Add PC Unit"**

### Step 3: Verify Registration

- The computer will appear in the PC Units table
- You can see its IP address, status, and activity
- Each IP address must be unique (no duplicates allowed)

### Managing Registered Computers

**Edit Computer:**
- Click the ✎ (edit) icon
- Update PC number, IP address, or status
- Click "Update PC Unit"

**Toggle Active/Inactive:**
- Click the ◉/○ (toggle) icon
- Active computers can accept sessions
- Inactive computers are disabled

**Delete Computer:**
- Click the ✕ (delete) icon
- Confirm deletion
- This removes the computer from the system

### Important Notes

1. **IP Address is Required** - Every computer must have a unique IP address
2. **Static IP Recommended** - Set static IP on each computer to avoid changes
3. **Same Network** - All computers should be on the same local network as the server
4. **Firewall Settings** - Ensure computers can communicate with the server

### Network Setup Example

```
Server:        192.168.1.100
PC-01:         192.168.1.101
PC-02:         192.168.1.102
PC-03:         192.168.1.103
PC-04:         192.168.1.104
...and so on
```

### Troubleshooting

**Problem: Can't connect to computer**
- Verify IP address is correct
- Check if computer is on the same network
- Ping the computer: `ping 192.168.1.101`
- Check firewall settings

**Problem: Duplicate IP error**
- Each computer needs a unique IP
- Check if another device is using the same IP
- Assign a different IP address

**Problem: Computer shows offline**
- Check network connection
- Verify computer is powered on
- Check if IP address changed (use static IP)

### API Integration (For Client Software)

If you're developing client software for each computer:

**Register/Update Computer Status:**
```
POST /api/machines/{machineCode}/status
Body: { "status": "online" }
```

**Send Heartbeat:**
```
POST /api/machines/{machineCode}/heartbeat
```

**Get Computer Info:**
```
GET /api/machines/{machineCode}
```

---

For more information, refer to:
- API_DOCUMENTATION.md
- HARDWARE_GUIDE.md
- ORANGEPI_CLIENT_SETUP.md

# Orange Pi PisoNet - Quick Reference Card

## 🚀 Quick Setup (30 Minutes)

### 1. Flash OS (5 min)
```bash
# Download Ubuntu Server 22.04 for Orange Pi
# Flash with balenaEtcher
# Insert MicroSD into Orange Pi
```

### 2. First Boot (5 min)
```bash
# Connect: Monitor, Keyboard, Mouse, Ethernet, Power
# Login: orangepi / orangepi
# Change password immediately
```

### 3. Install Software (10 min)
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y python3 python3-pip git
sudo pip3 install OrangePi.GPIO requests
```

### 4. Download Client (2 min)
```bash
mkdir -p ~/pisonet-client
cd ~/pisonet-client
# Copy pisonet_client.py from ORANGEPI_CLIENT_SETUP.md
nano pisonet_client.py
# Paste code and save (Ctrl+X, Y, Enter)
chmod +x pisonet_client.py
```

### 5. Configure (3 min)
Edit these lines in pisonet_client.py:
```python
SERVER_URL = "http://192.168.1.100:8000/api"  # Your server IP
API_TOKEN = "your_api_token_here"
MACHINE_CODE = "PC-01"  # Change for each machine
```

### 6. Test (5 min)
```bash
# Test GPIO
python3 test_gpio.py

# Test API
python3 test_api.py

# Test full system
python3 pisonet_client.py
```

---

## 🔌 Wiring Quick Reference

### Coin Acceptor → Orange Pi
```
Coin Acceptor          Orange Pi
─────────────          ─────────
RED (VCC)      →       12V Power Supply (separate)
BLACK (GND)    →       Pin 6 (GND)
WHITE (COIN)   →       Pin 11 (GPIO0)
```

### Pin 11 Location
```
     3.3V  (1) (2)  5V
    GPIO2  (3) (4)  5V
    GPIO3  (5) (6)  GND ← Connect coin GND here
    GPIO4  (7) (8)  GPIO14
      GND  (9) (10) GPIO15
 →  GPIO0 (11)(12) GPIO1  ← Connect coin signal here
```

---

## ⚙️ Configuration Checklist

### Per Machine Setup
- [ ] Machine Code: PC-01, PC-02, PC-03, etc.
- [ ] Static IP: 192.168.1.101, .102, .103, etc.
- [ ] Server URL configured
- [ ] API Token set
- [ ] Coin acceptor wired
- [ ] GPIO tested
- [ ] Auto-start enabled

---

## 🧪 Testing Commands

```bash
# Test GPIO
cd ~/pisonet-client
python3 test_gpio.py

# Test API
python3 test_api.py

# Run client manually
python3 pisonet_client.py

# Check service status
sudo systemctl status pisonet-client

# View logs
sudo journalctl -u pisonet-client -f

# Restart service
sudo systemctl restart pisonet-client
```

---

## 🔧 Common Issues & Fixes

### Coin Not Detected
```bash
# Check wiring
# Test with: python3 test_gpio.py
# Verify coin acceptor has 12V power
```

### Cannot Connect to Server
```bash
# Test network
ping 192.168.1.100

# Test API
curl -H "X-API-Token: your_token" \
     http://192.168.1.100:8000/api/system/status
```

### Service Won't Start
```bash
# Check logs
sudo journalctl -u pisonet-client -n 50

# Test manually
cd ~/pisonet-client
python3 pisonet_client.py
```

---

## 📊 System Status

### Check Everything
```bash
# Network
ip addr show

# Service
sudo systemctl status pisonet-client

# Logs (last 20 lines)
sudo journalctl -u pisonet-client -n 20

# GPIO permissions
groups orangepi
```

---

## 🔄 Auto-Start Setup

```bash
# Create service
sudo nano /etc/systemd/system/pisonet-client.service

# Enable service
sudo systemctl daemon-reload
sudo systemctl enable pisonet-client
sudo systemctl start pisonet-client

# Check status
sudo systemctl status pisonet-client
```

---

## 💰 Coin Values

Default configuration:
- **₱5 per coin** = **5 minutes**
- Adjustable in pisonet_client.py:
  ```python
  COIN_VALUE = 5.0
  MINUTES_PER_COIN = 5
  ```

---

## 📱 Server Dashboard

Access at: **http://192.168.1.100:8000**

Login:
- Email: admin@pisonet.com
- Password: password (change this!)

Monitor:
- Real-time machine status
- Active sessions
- Earnings
- Reports

---

## 🛠️ Maintenance

### Daily
- Check dashboard for offline machines
- Verify all stations working

### Weekly
- Check logs for errors
- Test coin acceptors
- Clean hardware

### Monthly
- Update system: `sudo apt update && sudo apt upgrade`
- Backup server database
- Check all connections

---

## 📞 Emergency Contacts

**Server Issues:**
- Check: DEPLOYMENT_GUIDE.md
- Logs: storage/logs/laravel.log

**Client Issues:**
- Check: ORANGEPI_CLIENT_SETUP.md
- Logs: `sudo journalctl -u pisonet-client -f`

**Hardware Issues:**
- Check: HARDWARE_GUIDE.md
- Test: GPIO and coin acceptor

---

## 🎯 Quick Commands Reference

```bash
# System
sudo reboot                    # Restart Orange Pi
sudo shutdown -h now          # Shutdown

# Service
sudo systemctl start pisonet-client
sudo systemctl stop pisonet-client
sudo systemctl restart pisonet-client
sudo systemctl status pisonet-client

# Logs
sudo journalctl -u pisonet-client -f        # Follow logs
sudo journalctl -u pisonet-client -n 50     # Last 50 lines

# Network
ip addr show                   # Show IP address
ping 192.168.1.100            # Test server connection

# Client
cd ~/pisonet-client
python3 pisonet_client.py     # Run manually
python3 test_gpio.py          # Test GPIO
python3 test_api.py           # Test API
```

---

## 📋 Machine Configuration Template

```python
# Machine 1
MACHINE_CODE = "PC-01"
Static IP: 192.168.1.101

# Machine 2
MACHINE_CODE = "PC-02"
Static IP: 192.168.1.102

# Machine 3
MACHINE_CODE = "PC-03"
Static IP: 192.168.1.103

# Machine 4
MACHINE_CODE = "PC-04"
Static IP: 192.168.1.104

# Machine 5
MACHINE_CODE = "PC-05"
Static IP: 192.168.1.105
```

---

## 🔒 Security Checklist

- [ ] Changed default password
- [ ] Changed API token
- [ ] Firewall enabled
- [ ] SSH key-based auth (optional)
- [ ] Regular updates scheduled

---

## 📈 Performance Tips

1. Use Class 10 MicroSD or better
2. Enable swap if needed
3. Disable unnecessary services
4. Use wired Ethernet (not WiFi)
5. Keep system updated

---

**Print this card and keep it near your PisoNet stations! 📄**

For detailed guides, see:
- ORANGEPI_CLIENT_SETUP.md
- HARDWARE_GUIDE.md
- DOCUMENTATION_INDEX.md

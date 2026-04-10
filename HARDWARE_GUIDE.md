# Hardware Shopping List & Wiring Guide

## 🛒 Complete Shopping List

### For Central Server (1x)
| Item | Quantity | Estimated Price (₱) | Notes |
|------|----------|---------------------|-------|
| PC/Laptop for Server | 1 | 15,000 - 30,000 | Can use existing PC |
| Router/Switch | 1 | 1,500 - 3,000 | Gigabit recommended |
| UPS (Uninterruptible Power Supply) | 1 | 3,000 - 5,000 | For server protection |

**Server Subtotal: ₱19,500 - ₱38,000**

---

### Per Orange Pi Station (multiply by number of stations)

| Item | Quantity | Estimated Price (₱) | Where to Buy |
|------|----------|---------------------|--------------|
| **Orange Pi Board** | | | |
| Orange Pi 3 LTS (2GB) | 1 | 2,500 - 3,000 | Shopee, Lazada |
| Orange Pi 4 LTS (4GB) | 1 | 3,500 - 4,500 | Shopee, Lazada |
| Orange Pi 5 (4GB) | 1 | 5,000 - 6,500 | Shopee, Lazada |
| **Storage** | | | |
| MicroSD Card 32GB Class 10 | 1 | 300 - 500 | Any electronics store |
| **Power Supply** | | | |
| 5V 3A USB-C Power Adapter | 1 | 250 - 400 | Shopee, Lazada |
| **Coin Acceptor** | | | |
| Multi-Coin Acceptor (CH-923) | 1 | 800 - 1,200 | Shopee, Lazada, Raon |
| 12V 2A Power Supply (for coin acceptor) | 1 | 200 - 350 | Electronics store |
| **Display** | | | |
| Monitor 19-24" | 1 | 3,000 - 8,000 | Any computer shop |
| HDMI Cable | 1 | 100 - 200 | Any electronics store |
| **Input Devices** | | | |
| USB Keyboard | 1 | 200 - 500 | Any computer shop |
| USB Mouse | 1 | 150 - 300 | Any computer shop |
| **Networking** | | | |
| Ethernet Cable Cat6 (per meter) | 10-20m | 20 - 30/meter | Hardware store |
| **Optional Components** | | | |
| Relay Module 5V | 1 | 50 - 150 | Shopee, Lazada |
| Jumper Wires (Female-Female) | 10pcs | 50 - 100 | Electronics store |
| Breadboard (for testing) | 1 | 80 - 150 | Electronics store |
| **Enclosure** | | | |
| Acrylic/Metal Case | 1 | 300 - 800 | Custom or online |
| Cable Ties & Organizers | 1 set | 50 - 100 | Hardware store |

**Per Station Subtotal: ₱7,000 - ₱15,000** (depending on Orange Pi model)

---

### Recommended Orange Pi Models

**Budget Option:**
- **Orange Pi 3 LTS (2GB)** - ₱2,500-3,000
- Good for basic browsing
- 2GB RAM sufficient for light use

**Balanced Option:**
- **Orange Pi 4 LTS (4GB)** - ₱3,500-4,500
- Better performance
- 4GB RAM for multitasking

**Premium Option:**
- **Orange Pi 5 (4GB/8GB)** - ₱5,000-8,000
- Best performance
- Gaming capable
- Future-proof

---

## 🔌 Detailed Wiring Diagram

### Orange Pi GPIO Pinout

```
Orange Pi 40-Pin GPIO Header
============================

        3.3V  [ 1] [ 2]  5V
       GPIO2  [ 3] [ 4]  5V
       GPIO3  [ 5] [ 6]  GND
       GPIO4  [ 7] [ 8]  GPIO14
         GND  [ 9] [10]  GPIO15
 COIN→ GPIO0  [11] [12]  GPIO1
      GPIO21  [13] [14]  GND
      GPIO22  [15] [16]  GPIO23
        3.3V  [17] [18]  GPIO24
      GPIO10  [19] [20]  GND
       GPIO9  [21] [22]  GPIO25
      GPIO11  [23] [24]  GPIO8
         GND  [25] [26]  GPIO7
       GPIO30  [27] [28]  GPIO31
       GPIO5  [29] [30]  GND
       GPIO6  [31] [32]  GPIO12
      GPIO13  [33] [34]  GND
      GPIO19  [35] [36]  GPIO16
      GPIO26  [37] [38]  GPIO20
         GND  [39] [40]  GPIO21
```

---

### Coin Acceptor Wiring

```
┌─────────────────────────────────────────────────────────┐
│                    COIN ACCEPTOR                        │
│                      (CH-923)                           │
│                                                         │
│  [RED]    [BLACK]   [WHITE]   [YELLOW]   [BLUE]       │
│   VCC      GND      COIN      COUNTER    INHIBIT       │
└───┬────────┬─────────┬──────────┬──────────┬───────────┘
    │        │         │          │          │
    │        │         │          │          │
    ▼        ▼         ▼          ▼          ▼
    
  12V DC   12V GND   Signal    (unused)   (unused)
  Power    Power     Output
  Supply   Supply
    │        │         │
    │        │         │
    │        │         └──────────────────────┐
    │        │                                 │
    │        └─────────────┐                   │
    │                      │                   │
    ▼                      ▼                   ▼
┌─────────────────────────────────────────────────────────┐
│                    ORANGE PI                            │
│                                                         │
│                    Pin 11 (GPIO0) ◄─────────────────────┤
│                    Pin 6 (GND) ◄────────────────────────┤
│                                                         │
└─────────────────────────────────────────────────────────┘

Note: Coin acceptor needs separate 12V power supply
      Only the signal wire connects to Orange Pi
```

---

### Complete Station Wiring

```
                    ┌──────────────────┐
                    │   12V 2A Power   │
                    │     Supply       │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │  Coin Acceptor   │
                    │     (CH-923)     │
                    └────────┬─────────┘
                             │ Signal Wire
                             │
    ┌────────────────────────┼────────────────────────┐
    │                        ▼                        │
    │              ┌──────────────────┐               │
    │              │   Orange Pi 5    │               │
    │              │   (Main Board)   │               │
    │              └──────────────────┘               │
    │                     │  │  │                     │
    │         ┌───────────┘  │  └───────────┐         │
    │         │              │              │         │
    │         ▼              ▼              ▼         │
    │    ┌────────┐    ┌─────────┐    ┌────────┐    │
    │    │ HDMI   │    │ USB     │    │Ethernet│    │
    │    │ Cable  │    │ Ports   │    │ Cable  │    │
    │    └───┬────┘    └────┬────┘    └───┬────┘    │
    │        │              │              │         │
    └────────┼──────────────┼──────────────┼─────────┘
             │              │              │
             ▼              ▼              ▼
        ┌─────────┐   ┌──────────┐   ┌─────────┐
        │ Monitor │   │ Keyboard │   │ Router  │
        │         │   │  Mouse   │   │ Switch  │
        └─────────┘   └──────────┘   └─────────┘

Power Connections:
- Orange Pi: 5V 3A USB-C
- Coin Acceptor: 12V 2A DC Jack
- Monitor: 220V AC
```

---

## 🔧 Assembly Instructions

### Step 1: Prepare Orange Pi

1. **Flash OS to MicroSD:**
   - Download Ubuntu Server 22.04 for Orange Pi
   - Use balenaEtcher to flash image
   - Insert MicroSD into Orange Pi

2. **Connect Peripherals:**
   - Connect HDMI to monitor
   - Connect USB keyboard and mouse
   - Connect Ethernet cable
   - Connect 5V power supply (last)

3. **First Boot:**
   - Wait for boot (2-3 minutes)
   - Login with default credentials
   - Change password immediately

### Step 2: Install Coin Acceptor

1. **Mount Coin Acceptor:**
   - Secure to enclosure or desk
   - Ensure coins can drop freely
   - Position for easy customer access

2. **Connect Power:**
   - Connect RED wire to 12V+ of power supply
   - Connect BLACK wire to 12V- (GND) of power supply
   - Plug in 12V power supply

3. **Connect Signal Wire:**
   - Connect WHITE wire to Orange Pi Pin 11 (GPIO0)
   - Connect BLACK wire to Orange Pi Pin 6 (GND)
   - Use female-to-female jumper wires if needed

### Step 3: Program Coin Acceptor

**CH-923 Programming:**

1. **Enter Programming Mode:**
   - Press and hold SET button
   - Insert sample coin
   - Release SET button

2. **Set Coin Value:**
   - Press SET button number of times = coin value
   - Example: Press 5 times for ₱5 coin
   - Wait for confirmation beep

3. **Repeat for Each Coin Type:**
   - ₱1 coin: Press SET 1 time
   - ₱5 coin: Press SET 5 times
   - ₱10 coin: Press SET 10 times

4. **Exit Programming:**
   - Wait 10 seconds
   - Test with actual coins

### Step 4: Software Installation

Follow the **ORANGEPI_CLIENT_SETUP.md** guide:

1. Update system
2. Install Python and dependencies
3. Configure network
4. Install client script
5. Test GPIO and API
6. Enable auto-start service

### Step 5: Testing

1. **Test Coin Detection:**
   ```bash
   python3 test_gpio.py
   ```
   - Insert coin
   - Should print "COIN DETECTED!"

2. **Test API Connection:**
   ```bash
   python3 test_api.py
   ```
   - Should show system status

3. **Test Full System:**
   ```bash
   python3 pisonet_client.py
   ```
   - Insert coin
   - Check server dashboard
   - Verify session starts

---

## 📐 Enclosure Design

### Recommended Dimensions

```
┌─────────────────────────────────────┐
│                                     │
│         Monitor (19-24")            │
│                                     │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│  Keyboard Area                      │
│  ┌──────────────────────────────┐   │
│  │      Keyboard                │   │
│  └──────────────────────────────┘   │
│  ┌────────┐                         │
│  │ Mouse  │                         │
│  └────────┘                         │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│  ┌──────────┐  ┌────────────────┐   │
│  │   Coin   │  │  Orange Pi     │   │
│  │ Acceptor │  │  + Power       │   │
│  │          │  │  Supplies      │   │
│  └──────────┘  └────────────────┘   │
└─────────────────────────────────────┘
```

### Materials for Enclosure

- **Wood/MDF Board:** ₱500-1,000
- **Acrylic Sheet:** ₱300-600
- **Screws & Brackets:** ₱100-200
- **Cable Management:** ₱50-100

---

## 💰 Total Cost Estimate

### For 5-Station Setup

| Component | Cost per Station | Total (5 stations) |
|-----------|------------------|-------------------|
| Orange Pi 4 LTS | ₱4,000 | ₱20,000 |
| Accessories & Cables | ₱1,500 | ₱7,500 |
| Coin Acceptor + PSU | ₱1,200 | ₱6,000 |
| Monitor (used/new) | ₱4,000 | ₱20,000 |
| Keyboard & Mouse | ₱400 | ₱2,000 |
| Enclosure & Misc | ₱800 | ₱4,000 |
| **Subtotal per station** | **₱11,900** | **₱59,500** |

### Central Server & Network

| Component | Cost |
|-----------|------|
| Server PC (can use existing) | ₱0 - ₱30,000 |
| Router/Switch | ₱2,000 |
| UPS | ₱4,000 |
| Ethernet Cables | ₱500 |
| **Subtotal** | **₱6,500 - ₱36,500** |

### **GRAND TOTAL: ₱66,000 - ₱96,000** (for 5 stations)

**Per Station Average: ₱13,200 - ₱19,200**

---

## 🛍️ Where to Buy in Philippines

### Online Stores
- **Shopee** - Orange Pi boards, coin acceptors
- **Lazada** - Electronics, accessories
- **TipidPC** - Computer parts
- **PCHub** - Computer components

### Physical Stores (Metro Manila)
- **Gilmore IT Center** - Computer parts
- **Raon Electronics** - Coin acceptors, components
- **Greenhills Shopping Center** - Electronics
- **PC Express** - Computer peripherals
- **Octagon Computer** - PC components

### Coin Acceptor Suppliers
- **Raon Electronics, Manila**
- **Shopee/Lazada** (search: "CH-923 coin acceptor")
- **Industrial electronics suppliers**

---

## 📋 Pre-Installation Checklist

- [ ] All hardware components purchased
- [ ] Central server set up and running
- [ ] Network infrastructure ready (router, cables)
- [ ] Orange Pi boards flashed with OS
- [ ] Coin acceptors programmed
- [ ] Enclosures built or purchased
- [ ] Power outlets available for all stations
- [ ] Client software downloaded
- [ ] API token configured
- [ ] Static IPs planned for each station

---

## 🔍 Quality Check

Before deploying each station:

1. **Hardware:**
   - [ ] All connections secure
   - [ ] No loose wires
   - [ ] Coin acceptor mounted firmly
   - [ ] Power supplies working

2. **Software:**
   - [ ] OS updated
   - [ ] Client script installed
   - [ ] Auto-start enabled
   - [ ] API connection tested

3. **Functionality:**
   - [ ] Coin detection working
   - [ ] Session starts correctly
   - [ ] Time countdown accurate
   - [ ] Server receives data

---

**Your hardware setup guide is complete! 🎉**

Follow this guide to build your Orange Pi-based PisoNet stations!

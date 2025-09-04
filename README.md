# 🚖 Cab Booking Platform

**TroyRides** is a cab booking system that connects **customers, drivers (contractors)**, and **administrators** in a single platform.  
It provides a structured booking flow with ride approvals, surge pricing, and multiple payment methods.  

---

## ✨ Features

### 👑 Super Admin
- Create & manage **employees** (with login credentials)  
- Verify and approve **driver (contractor) profiles**  
- Receive **notifications & emails** for new ride requests  
- Approve, reject, or **super reject** ride requests  
  - **Reject:** Request forwarded to employees for acceptance  
  - **Super Reject:** Request visible to all contractors (drivers)  

### 🚗 Contractors (Drivers)
- Register via **"Become a Driver"** button on website  
- Complete profile & verify email via OTP  
- Wait for **super admin approval** before accessing services  
- Accept ride requests (if made available after rejection/super rejection)  
- Update ride status manually (Start, Ongoing, Completed)  

### 👨‍💼 Employees
- Created by **super admin** only  
- Can accept ride requests when **super admin rejects** a booking  

### 🧑‍🤝‍🧑 Customers
- Register and log in to the platform  
- Book rides **up to 2 weeks in advance**  
- Search rides by **pickup & drop-off locations**  
- Choose payment method:  
  - **Cash** (pay directly to driver)  
  - **Card** (payment deducted only after ride completion)  
- View booking status & ride history  

---

## 💰 Surge Pricing
- Extra charges applied based on:  
  - Night rides 🌙  
  - Weekend rides 📅  
  - Holiday rides 🎉  

---

## 🛠️ Tech Stack

- **Backend:** Laravel (PHP)  
- **Frontend:** HTML, CSS, Bootstrap  
- **Database:** MySQL  
- **Authentication:** Laravel Auth / OTP (email verification)  
- **Payment Integration:** Stripe   
- **Notifications:** Email + pusher

---
git clone https://github.com/yourusername/troyrides.git
cd troyrides

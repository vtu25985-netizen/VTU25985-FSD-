🚀 Inventory Management System - Setup Instructions

📋 REQUIREMENTS:
- XAMPP (download from https://www.apachefriends.org/)
- Web browser (Chrome, Firefox, etc.)

⚡ QUICK SETUP (15 minutes):

1️⃣ INSTALL XAMPP:
- Download XAMPP from the website
- Install it (default location: C:\xampp)
- Open XAMPP Control Panel
- Start Apache and MySQL (both should turn green)

2️⃣ EXTRACT FILES:
- Extract this zip folder to: C:\xampp\htdocs\inventory_system\
- Make sure all files are in that folder

3️⃣ SETUP DATABASE:
- Open browser and go to: http://localhost/phpmyadmin/
- Click "New" on the left sidebar
- Enter database name: inventory_db
- Click "Create"
- Click on the "SQL" tab
- Copy and paste the SQL code below
- Click "Go"

SQL CODE:
-----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    quantity INT(11) NOT NULL DEFAULT 0,
    price DECIMAL(10,2) NOT NULL,
    supplier VARCHAR(100) NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO products (name, quantity, price, supplier) VALUES 
('Laptop Dell XPS', 15, 999.99, 'Dell Inc.'),
('Mouse Logitech', 45, 25.50, 'Logitech'),
('Keyboard Mechanical', 8, 89.99, 'Corsair'),
('Monitor 24 inch', 5, 199.99, 'Samsung'),
('USB Flash Drive 32GB', 120, 12.99, 'SanDisk');
-----------------------------------------------------------------

4️⃣ START USING:
- Go to: http://localhost/inventory_system/
- Login credentials:
  • Admin: username: admin, password: admin123
  • User: username: user, password: user123

🎯 FEATURES:
✅ Admin: Add, Edit, Delete products + Dashboard
✅ User: View, Search, Filter products
✅ Low stock alerts (items < 10 highlighted in red)
✅ Responsive design (works on mobile)

🔧 TROUBLESHOOTING:
❌ If you see "Not Found": Make sure files are in C:\xampp\htdocs\inventory_system\
❌ If database error: Check that MySQL is running (green in XAMPP)
❌ If login fails: Run the database setup again

📞 NEED HELP?
Contact me if you have any issues!

Enjoy the Inventory Management System! 🎉

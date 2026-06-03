CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,

    role_name VARCHAR(50) NOT NULL UNIQUE
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    full_name VARCHAR(100) NOT NULL,

    email VARCHAR(100) UNIQUE,

    phone VARCHAR(20),

    role_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (role_id)
    REFERENCES roles(id)
);
CREATE TABLE customer_types (
    id INT AUTO_INCREMENT PRIMARY KEY,

    type_name VARCHAR(50) NOT NULL UNIQUE
);
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(100) NOT NULL,

    email VARCHAR(100) UNIQUE,

    phone VARCHAR(20) NOT NULL,

    gender ENUM('Male', 'Female', 'Other'),

    birth_date DATE,

    address TEXT,

    customer_type_id INT,

    assigned_staff_id INT,

    note TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_type_id)
    REFERENCES customer_types(id),

    FOREIGN KEY (assigned_staff_id)
    REFERENCES users(id)
);
CREATE TABLE interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    customer_id INT NOT NULL,

    staff_id INT NOT NULL,

    interaction_type VARCHAR(50) NOT NULL,

    content TEXT,

    interaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_id)
    REFERENCES customers(id),

    FOREIGN KEY (staff_id)
    REFERENCES users(id)
);
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,

    customer_id INT NOT NULL,

    staff_id INT NOT NULL,

    appointment_time DATETIME NOT NULL,

    location VARCHAR(255),

    purpose TEXT,

    status ENUM('Pending', 'Completed', 'Cancelled')
    DEFAULT 'Pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_id)
    REFERENCES customers(id),

    FOREIGN KEY (staff_id)
    REFERENCES users(id)
);
INSERT INTO roles(role_name)
VALUES
('Admin'),
('Staff'),
('Manager');
INSERT INTO customer_types(type_name)
VALUES
('VIP'),
('Potential'),
('Normal');
INSERT INTO users(
    username,
    password,
    full_name,
    email,
    phone,
    role_id
)

VALUES
(
    'admin',
    '123456',
    'Nguyen Admin',
    'admin@gmail.com',
    '0123456789',
    1
),

(
    'staff01',
    '123456',
    'Tran Staff',
    'staff@gmail.com',
    '0987654321',
    2
);
INSERT INTO customers(
    full_name,
    email,
    phone,
    gender,
    birth_date,
    address,
    customer_type_id,
    assigned_staff_id,
    note
)

VALUES
(
    'Le Van A',
    'a@gmail.com',
    '0901111111',
    'Male',
    '2002-05-10',
    'Ha Noi',
    1,
    2,
    'Khach VIP'
),

(
    'Tran Thi B',
    'b@gmail.com',
    '0902222222',
    'Female',
    '2001-08-15',
    'Hai Phong',
    2,
    2,
    'Khach tiem nang'
);
INSERT INTO interactions(
    customer_id,
    staff_id,
    interaction_type,
    content
)

VALUES
(
    1,
    2,
    'Phone Call',
    'Da goi dien tu van goi dich vu'
),

(
    2,
    2,
    'Email',
    'Da gui email gioi thieu san pham'
);
INSERT INTO appointments(
    customer_id,
    staff_id,
    appointment_time,
    location,
    purpose,
    status
)

VALUES
(
    1,
    2,
    '2026-05-20 09:00:00',
    'Ha Noi Office',
    'Tu van hop dong',
    'Pending'
);
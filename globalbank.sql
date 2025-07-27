-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  role ENUM('superadmin','bankadmin','accountant','auditor','customer'),
  status ENUM('active','inactive'),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Banks table (Multi-tenant)
CREATE TABLE banks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  swift_code VARCHAR(20),
  country VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  account_number VARCHAR(50) UNIQUE,
  currency CHAR(3),
  balance DECIMAL(15,2) DEFAULT 0,
  status ENUM('active','inactive','closed') DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Transactions
CREATE TABLE IF NOT EXISTS transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  account_id INT,
  type ENUM('deposit','withdraw','transfer'),
  amount DECIMAL(15,2),
  currency CHAR(3),
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Journal Entries
CREATE TABLE journal_entries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  debit_account_id INT,
  credit_account_id INT,
  amount DECIMAL(15,2),
  currency CHAR(3),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Audit Logs
CREATE TABLE audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

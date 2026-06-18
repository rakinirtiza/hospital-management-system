
CREATE DATABASE IF NOT EXISTS hospital_db;
USE hospital_db;

CREATE TABLE admins_info (
 admin_id INT AUTO_INCREMENT PRIMARY KEY,
 admin_fname VARCHAR(100),
 admin_phone VARCHAR(30),
 admin_email VARCHAR(150),
 admin_password VARCHAR(255),
 admin_role VARCHAR(20) DEFAULT 'admin'
);

CREATE TABLE departments_info (
 department_id INT AUTO_INCREMENT PRIMARY KEY,
 department_name VARCHAR(100) NOT NULL,
 department_description TEXT,
 required_doctors INT DEFAULT 0
);

CREATE TABLE doctors_info (
 doctor_id INT AUTO_INCREMENT PRIMARY KEY,
 doctor_fname VARCHAR(100),
 doctor_lname VARCHAR(100),
 doctor_phone VARCHAR(30),
 doctor_email VARCHAR(150),
 doctor_dob DATE,
 doctor_doj DATE,
 doctor_gender VARCHAR(20),
 doctor_country VARCHAR(100),
 doctor_degree VARCHAR(200),
 department_id INT,
 doctor_salary DECIMAL(10,2) DEFAULT 0,
 doctor_password VARCHAR(255),
 doctor_room VARCHAR(50),
 doctor_fees DECIMAL(10,2) DEFAULT 0,
 doctor_image VARCHAR(255),
 doctor_role VARCHAR(20) DEFAULT 'doctor'
);

CREATE TABLE users_info (
 user_id INT AUTO_INCREMENT PRIMARY KEY,
 user_fname VARCHAR(100),
 user_lname VARCHAR(100),
 user_phone VARCHAR(30),
 user_email VARCHAR(150),
 user_dob DATE,
 user_gender VARCHAR(20),
 user_address TEXT,
 user_password VARCHAR(255),
 user_role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE patients_info (
 patient_id INT AUTO_INCREMENT PRIMARY KEY,
 patient_fname VARCHAR(100),
 patient_lname VARCHAR(100),
 patient_phone VARCHAR(30),
 patient_email VARCHAR(150),
 patient_dob DATE,
 patient_gender VARCHAR(20),
 patient_address TEXT,
 patient_disease VARCHAR(255),
 department_id INT,
 doctor_id INT,
 patient_admission DATE,
 patient_room VARCHAR(50)
);

CREATE TABLE appointments_info (
 appointment_id INT AUTO_INCREMENT PRIMARY KEY,
 patient_full_name VARCHAR(200),
 patient_phone VARCHAR(30),
 patient_email VARCHAR(150),
 user_id INT,
 doctor_id INT,
 appointment_date DATE,
 patient_message TEXT
);

CREATE TABLE prescriptions_info (
 prescription_id INT AUTO_INCREMENT PRIMARY KEY,
 doctor_id INT,
 patient_id INT,
 patient_name VARCHAR(200),
 patient_age INT,
 patient_gender VARCHAR(20),
 prescription_date DATETIME DEFAULT CURRENT_TIMESTAMP,
 prescription_notes TEXT
);

CREATE TABLE prescription_medicines (
 medicine_id INT AUTO_INCREMENT PRIMARY KEY,
 prescription_id INT,
 medicine_name VARCHAR(255),
 medicine_dosage VARCHAR(100),
 medicine_frequency VARCHAR(100),
 medicine_duration VARCHAR(100),
 medicine_instruction TEXT
);

CREATE TABLE feedback_info (
 feedback_id INT AUTO_INCREMENT PRIMARY KEY,
 patient_name VARCHAR(150),
 patient_phone VARCHAR(30),
 feedback_text TEXT,
 rating INT,
 feedback_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE careers_info (
 career_id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150),
 email VARCHAR(150),
 phone VARCHAR(30),
 position VARCHAR(150),
 message TEXT
);

CREATE TABLE contact_us (
 contact_id INT AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(150),
 phone VARCHAR(30),
 email VARCHAR(150),
 message TEXT
);

CREATE TABLE PriceList (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 pname VARCHAR(30) NOT NULL,
 bprice DECIMAL(10,2) NOT NULL,
 sprice DECIMAL(10,2) NOT NULL,
 reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins_info(admin_fname,admin_email,admin_phone,admin_password,admin_role)
VALUES ('Admin','admin@hospital.com','01700000000','admin123','admin');

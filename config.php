<?php
$host = 'localhost';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass);

// Error checking & database/table creation
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

if (!$mysqli->set_charset("utf8mb4")) {
    die("Error loading character set utf8mb4: " . $mysqli->error);
}

$db = 'mindfulness';
if ($mysqli->query("CREATE DATABASE IF NOT EXISTS $db") === FALSE) {
    die("Error creating database: " . $mysqli->error);
}
$mysqli->select_db($db);

$sql = "CREATE TABLE IF NOT EXISTS user_roles (
            role_id INT AUTO_INCREMENT PRIMARY KEY,
            role_name VARCHAR(50) NOT NULL UNIQUE,
            description TEXT
        );
        
        CREATE TABLE IF NOT EXISTS users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            email VARCHAR(255),
            full_name VARCHAR(255),
            role_id INT NOT NULL DEFAULT 3,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES user_roles(role_id)
        );
        
        CREATE TABLE IF NOT EXISTS sub_tasks (
            sub_task_id INT AUTO_INCREMENT PRIMARY KEY,
            sub_task_name VARCHAR(100) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS activities (
            activity_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            sub_task_id INT NOT NULL,
            activity_name VARCHAR(255) NOT NULL,
            schedule_datetime DATETIME NOT NULL,
            duration_text VARCHAR(50),
            is_done BOOLEAN DEFAULT FALSE,
            start_datetime DATETIME,
            end_datetime DATETIME,
            created_by INT NOT NULL,
            updated_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_activities_user FOREIGN KEY (user_id) REFERENCES users(user_id),
            CONSTRAINT fk_activities_subtask FOREIGN KEY (sub_task_id) REFERENCES sub_tasks(sub_task_id)
        );
        
        CREATE TABLE IF NOT EXISTS activity_progress (
            progress_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            sub_task_id INT NOT NULL,
            activity_id INT NOT NULL,
            is_done BOOLEAN DEFAULT FALSE,
            progress_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_progress_user FOREIGN KEY (user_id) REFERENCES users(user_id),
            CONSTRAINT fk_progress_subtask FOREIGN KEY (sub_task_id) REFERENCES sub_tasks(sub_task_id),
            CONSTRAINT fk_progress_activity FOREIGN KEY (activity_id) REFERENCES activities(activity_id)
        );
        
        CREATE TABLE IF NOT EXISTS user_logs (
            log_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            activity_id INT,
            action VARCHAR(100) NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_logs_user FOREIGN KEY (user_id) REFERENCES users(user_id),
            CONSTRAINT fk_logs_activity FOREIGN KEY (activity_id) REFERENCES activities(activity_id)
        );
        
        INSERT IGNORE INTO user_roles (role_name, description) VALUES
            ('Admin', 'Has full system access and management privileges'),
            ('Customer Service', 'Supports user concerns and monitors activity reports'),
            ('User', 'Regular app user performing mindfulness activities');";

// Checks if all queries were executed
if ($mysqli->multi_query($sql)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    }while ($mysqli->more_results() && $mysqli->next_result());
}else {
    die("Error creating tables: " . $mysqli->error);
}
?>
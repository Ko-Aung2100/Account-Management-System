CREATE DATABASE File_Management;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO Users (username, email, password) VALUES
('john_doe', 'john.doe@example.com', 'P@$$wOrd123'),
('jane_smith', 'jane.smith@example.com', 'securePass456'),
('peter_jones', 'peter.jones@example.com', 'mySecret789'),
('alice_wonder', 'alice.wonder@example.com', 'wonderland'),
('bob_builder', 'bob.builder@example.com', 'buildIt123');

ALTER TABLE Users ADD verified TINYINT DEFAULT 0;
ALTER TABLE Users ADD verify_token VARCHAR(255);

ALTER TABLE Users ADD COLUMN secret VARCHAR(255) DEFAULT NULL;

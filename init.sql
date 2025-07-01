CREATE DATABASE IF NOT EXISTS ceiqu2Ceil6f;
USE ceiqu2Ceil6f;

CREATE TABLE visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(15) NOT NULL,
    user_agent VARCHAR(4096) NOT NULL,
    view_date DATETIME NOT NULL,
    page_url VARCHAR(2048) NOT NULL,
    views_count INT NOT NULL DEFAULT 1,
    UNIQUE KEY page_visit (ip_address, user_agent(255), page_url(255)),
    INDEX i_ip_address (ip_address),
    INDEX i_view_date (view_date),
    INDEX i_page_url (page_url(255))
);

-- Sample data for testing
INSERT INTO visits (ip_address, user_agent, view_date, page_url, views_count) VALUES
('127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36', '2025-07-01 10:00:00', 'http://localhost/index1.html', 1),
('127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36', '2025-07-01 10:00:00', 'http://localhost/index2.html', 1);

CREATE DATABASE IF NOT EXISTS ceiqu2Ceil6f;
USE ceiqu2Ceil6f;

CREATE TABLE visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NOT NULL,
    view_date DATETIME NOT NULL,
    page_url VARCHAR(2048) NOT NULL,
    views_count INT NOT NULL DEFAULT 1,
    user_agent_hash CHAR(64) NOT NULL,
    page_url_hash CHAR(64) NOT NULL,
    UNIQUE KEY page_visit (ip_address, user_agent_hash, page_url_hash),
    INDEX i_ip_address (ip_address),
    INDEX i_view_date (view_date),
    INDEX i_page_url_hash (page_url_hash),
    INDEX i_user_agent_hash (user_agent_hash)
);

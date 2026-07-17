-- Database Schema for Rajrupa Mukherjee Website
-- Created for modern, professional website with dynamic content management

-- Create database
CREATE DATABASE IF NOT EXISTS rajrupa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rajrupa;

-- Users table for admin authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Performances table
CREATE TABLE performances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    venue VARCHAR(255),
    city VARCHAR(100),
    organizer VARCHAR(255),
    performance_date DATE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_year (year),
    INDEX idx_featured (is_featured)
);

-- Awards and Recognitions table
CREATE TABLE awards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year INT NOT NULL,
    award_name VARCHAR(255) NOT NULL,
    organization VARCHAR(255),
    description TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_year (year),
    INDEX idx_featured (is_featured)
);

-- Training and Workshops table
CREATE TABLE training_workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('training', 'workshop', 'course') NOT NULL,
    duration VARCHAR(100),
    location VARCHAR(255),
    start_date DATE,
    end_date DATE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_active (is_active)
);

-- Gallery categories
CREATE TABLE gallery_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gallery items (photos and videos)
CREATE TABLE gallery_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(500) NOT NULL,
    file_type ENUM('image', 'video') NOT NULL,
    thumbnail_path VARCHAR(500),
    category_id INT,
    is_featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES gallery_categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_type (file_type),
    INDEX idx_featured (is_featured),
    INDEX idx_order (display_order)
);

-- Press clippings table
CREATE TABLE press_clippings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    publication_name VARCHAR(255),
    publication_date DATE,
    author VARCHAR(255),
    content TEXT,
    external_url VARCHAR(500),
    file_path VARCHAR(500),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_date (publication_date),
    INDEX idx_featured (is_featured)
);

-- Comments and testimonials table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    comment_text TEXT NOT NULL,
    rating INT DEFAULT 5,
    is_approved BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_approved (is_approved),
    INDEX idx_featured (is_featured),
    INDEX idx_rating (rating)
);

-- Guru information table
CREATE TABLE guru_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guru_name VARCHAR(255) NOT NULL,
    title VARCHAR(255),
    biography TEXT,
    achievements TEXT,
    photo_path VARCHAR(500),
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_replied BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_read (is_read),
    INDEX idx_replied (is_replied)
);

-- Site settings table
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'number', 'boolean', 'file') DEFAULT 'text',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- SEO metadata table
CREATE TABLE seo_metadata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(100) UNIQUE NOT NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    og_title VARCHAR(255),
    og_description TEXT,
    og_image VARCHAR(500),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default data

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password_hash, role) VALUES 
('admin', 'admin@rajrupa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert gallery categories
INSERT INTO gallery_categories (name, slug, description) VALUES 
('Photos', 'photos', 'Photographs of performances and events'),
('Videos', 'videos', 'Video clips of dance performances'),
('Behind the Scenes', 'behind-the-scenes', 'Behind the scenes and rehearsal photos');

-- Insert site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES 
('site_title', 'Rajrupa Mukherjee | Renowned Bharatanatyam Dancer', 'text', 'Website title'),
('site_description', 'Rajrupa Mukherjee - Renowned Bharatanatyam Dancer in India', 'textarea', 'Website description'),
('contact_email', 'rajrupa.m79@gmail.com', 'text', 'Contact email address'),
('contact_phone', '+91 98317 39628', 'text', 'Contact phone number'),
('contact_address', '58, Ashokegarh (Dunlop Bridge) Kolkata 700 108', 'textarea', 'Contact address'),
('social_facebook', '', 'text', 'Facebook profile URL'),
('social_instagram', '', 'text', 'Instagram profile URL'),
('social_youtube', '', 'text', 'YouTube channel URL'),
('analytics_tracking_id', 'UA-5992376-49', 'text', 'Google Analytics tracking ID');

-- Insert SEO metadata for main pages
INSERT INTO seo_metadata (page_name, meta_title, meta_description, meta_keywords) VALUES 
('home', 'Rajrupa Mukherjee | Bharatanatyam Dancer in India | Bharatanatyam Dance Teacher in Kolkata', 'Rajrupa Mukherjee - Renowned Bharatanatyam Dancer in India, Bharatanatyam Dance Teacher in Kolkata, Top 10 Indian Bharatanatyam Dancer', 'Rajrupa Mukherjee, Bharatanatyam Dancer, Indian Classical Dance, Kolkata, Dance Teacher'),
('performances', 'Performances | Rajrupa Mukherjee | Bharatanatyam Dance Performances', 'Explore the performances of Rajrupa Mukherjee, renowned Bharatanatyam dancer, showcasing her journey in Indian classical dance', 'Rajrupa Mukherjee performances, Bharatanatyam performances, Indian classical dance, dance festivals'),
('awards', 'Awards & Recognitions | Rajrupa Mukherjee', 'Awards and recognitions received by Rajrupa Mukherjee for her contributions to Bharatanatyam and Indian classical dance', 'Rajrupa Mukherjee awards, Bharatanatyam awards, dance recognitions, Indian classical dance honors'),
('training', 'Training & Workshops | Rajrupa Mukherjee', 'Bharatanatyam training programs and workshops conducted by Rajrupa Mukherjee in Kolkata', 'Bharatanatyam training, dance workshops, Kolkata dance classes, Indian classical dance education'),
('guru', 'Her Guru | Rajrupa Mukherjee', 'Information about Rajrupa Mukherjee\'s Guru and the lineage of Bharatanatyam tradition', 'Bharatanatyam Guru, dance teacher, Indian classical dance lineage, Guru Shishya parampara'),
('gallery', 'Gallery | Rajrupa Mukherjee', 'Photo and video gallery of Rajrupa Mukherjee\'s Bharatanatyam performances and events', 'Rajrupa Mukherjee photos, Bharatanatyam videos, dance gallery, classical dance images'),
('contact', 'Contact Rajrupa Mukherjee | Bharatanatyam Dancer', 'Contact information for Rajrupa Mukherjee, renowned Bharatanatyam dancer and teacher in Kolkata', 'Rajrupa Mukherjee contact, Bharatanatyam classes, dance teacher Kolkata, classical dance contact');

-- Insert sample performance data
INSERT INTO performances (year, title, description, venue, city, organizer, is_featured) VALUES 
(2010, 'Chiranjiv Bharati School', 'Lecture demonstration and performance', 'Chiranjiv Bharati School', 'Gurgaon', 'School Management', TRUE),
(2009, 'Epicentre Performance', 'Classical Bharatanatyam performance', 'Epicentre', 'Gurgaon', 'Old World Hospitality Ltd.', TRUE),
(2008, 'Srimanta Shankardev Kalakshetra', 'Classical dance performance', 'Srimanta Shankardev Kalakshetra', 'Assam', 'Cultural Organization', FALSE),
(2007, 'International Conference on Pattern Recognition', 'Cultural performance at academic conference', 'Indian Statistical Institute', 'Kolkata', 'ISI', FALSE),
(2006, 'Sri Ragam Fine Arts Festival', '16th Year Annual Art Festival', 'Sri Ragam Fine Arts', 'Chennai', 'Sri Ragam Fine Arts', TRUE);

-- Insert sample award data
INSERT INTO awards (year, award_name, organization, description, is_featured) VALUES 
(2020, 'Excellence in Bharatanatyam', 'West Bengal State Music Academy', 'Award for outstanding contribution to Bharatanatyam', TRUE),
(2018, 'National Dance Award', 'Sangeet Natak Academy', 'Recognition for excellence in Indian classical dance', TRUE),
(2015, 'Young Achiever Award', 'Cultural Ministry', 'Award for young talent in classical dance', FALSE);

-- Insert sample training data
INSERT INTO training_workshops (title, description, type, duration, location, is_active) VALUES 
('Beginner Bharatanatyam Course', 'Introduction to Bharatanatyam for beginners', 'course', '6 months', 'Kolkata', TRUE),
('Advanced Dance Workshop', 'Intensive workshop for advanced students', 'workshop', '2 weeks', 'Kolkata', TRUE),
('Summer Dance Camp', 'Special summer training program', 'training', '1 month', 'Kolkata', TRUE);

-- Insert sample gallery items
INSERT INTO gallery_items (title, description, file_path, file_type, category_id, is_featured, display_order) VALUES 
('Performance at Kolkata', 'Classical Bharatanatyam performance in Kolkata', 'images/gallery/kolkata-performance.jpg', 'image', 1, TRUE, 1),
('Dance Pose', 'Traditional Bharatanatyam pose', 'images/gallery/dance-pose.jpg', 'image', 1, FALSE, 2),
('Teaching Session', 'Bharatanatyam teaching session', 'images/gallery/teaching.jpg', 'image', 3, FALSE, 3);

-- Insert sample press clippings
INSERT INTO press_clippings (title, publication_name, publication_date, author, content, is_featured) VALUES 
('Rising Star in Bharatanatyam', 'The Telegraph', '2020-03-15', 'Cultural Correspondent', 'Rajrupa Mukherjee emerges as a promising talent in the world of Bharatanatyam...', TRUE),
('Classical Dance Revival', 'Times of India', '2019-11-20', 'Arts Editor', 'The revival of classical dance through dedicated artists like Rajrupa Mukherjee...', FALSE);

-- Insert sample comments
INSERT INTO comments (name, email, comment_text, rating, is_approved, is_featured) VALUES 
('Priya Sharma', 'priya@email.com', 'Amazing performance! Truly inspiring.', 5, TRUE, TRUE),
('Rahul Verma', 'rahul@email.com', 'Beautiful expression and grace in every movement.', 5, TRUE, FALSE),
('Anita Desai', 'anita@email.com', 'Learned so much from the workshop. Thank you!', 5, TRUE, TRUE);

-- Insert guru information
INSERT INTO guru_info (guru_name, title, biography, achievements, is_primary) VALUES 
('Guru Shri Khagendranath Barman', 'Bharatanatyam Maestro', 'Direct disciple of Guru Mother Late Smt. Rukmini Devi Arundale. Renowned Bharatanatyam exponent and teacher with decades of experience.', 'Founded Natanam Kalakshetra, trained numerous disciples, recipient of Sangeet Natak Academy Award', TRUE),
('Smt. Puspa Chatterjee', 'Dance Teacher', 'Talented dancer and disciple of Guru Shri Khagendranath Barman. Dedicated to preserving and teaching the traditional form of Bharatanatyam.', 'Over 30 years of teaching experience, trained many successful dancers', FALSE);

-- Create views for common queries

CREATE VIEW featured_performances AS
SELECT * FROM performances WHERE is_featured = TRUE ORDER BY year DESC;

CREATE VIEW recent_gallery_items AS
SELECT * FROM gallery_items WHERE is_featured = TRUE ORDER BY created_at DESC LIMIT 10;

CREATE VIEW approved_comments AS
SELECT * FROM comments WHERE is_approved = TRUE ORDER BY created_at DESC;

CREATE VIEW upcoming_training AS
SELECT * FROM training_workshops WHERE is_active = TRUE AND start_date >= CURDATE() ORDER BY start_date;

-- Create stored procedures for common operations

DELIMITER //

CREATE PROCEDURE sp_get_featured_content()
BEGIN
    SELECT 
        'performances' as content_type,
        COUNT(*) as count,
        MAX(year) as latest_year
    FROM performances WHERE is_featured = TRUE
    
    UNION ALL
    
    SELECT 
        'awards' as content_type,
        COUNT(*) as count,
        MAX(year) as latest_year
    FROM awards WHERE is_featured = TRUE
    
    UNION ALL
    
    SELECT 
        'gallery' as content_type,
        COUNT(*) as count,
        MAX(created_at) as latest_date
    FROM gallery_items WHERE is_featured = TRUE;
END //

CREATE PROCEDURE sp_get_contact_stats()
BEGIN
    SELECT 
        'total_messages' as stat_type,
        COUNT(*) as value
    FROM contact_messages
    
    UNION ALL
    
    SELECT 
        'unread_messages' as stat_type,
        COUNT(*) as value
    FROM contact_messages WHERE is_read = FALSE
    
    UNION ALL
    
    SELECT 
        'pending_comments' as stat_type,
        COUNT(*) as value
    FROM comments WHERE is_approved = FALSE;
END //

DELIMITER ;

-- Create triggers for audit logging

CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(50) NOT NULL,
    action VARCHAR(10) NOT NULL,
    record_id INT NOT NULL,
    old_values JSON,
    new_values JSON,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_table (table_name),
    INDEX idx_action (action),
    INDEX idx_user (user_id)
);

DELIMITER //

CREATE TRIGGER performances_after_insert
AFTER INSERT ON performances
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (table_name, action, record_id, new_values)
    VALUES ('performances', 'INSERT', NEW.id, JSON_OBJECT(
        'year', NEW.year,
        'title', NEW.title,
        'description', NEW.description,
        'venue', NEW.venue,
        'city', NEW.city
    ));
END //

CREATE TRIGGER performances_after_update
AFTER UPDATE ON performances
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (table_name, action, record_id, old_values, new_values)
    VALUES ('performances', 'UPDATE', NEW.id, 
        JSON_OBJECT(
            'year', OLD.year,
            'title', OLD.title,
            'description', OLD.description,
            'venue', OLD.venue,
            'city', OLD.city
        ),
        JSON_OBJECT(
            'year', NEW.year,
            'title', NEW.title,
            'description', NEW.description,
            'venue', NEW.venue,
            'city', NEW.city
        )
    );
END //

DELIMITER ;

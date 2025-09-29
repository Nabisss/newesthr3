CREATE DATABASE IF NOT EXISTS calicrane_training;
USE calicrane_training;




CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    modules_count INT DEFAULT 0,
    progress INT DEFAULT 0,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,-- Add these tables to your existing database
CREATE TABLE instructors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE course_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO instructors (name, email, department) VALUES
('Michael Johnson', 'michael.johnson@calicrane.com', 'Operations'),
('Sarah Williams', 'sarah.williams@calicrane.com', 'Safety'),
('Robert Brown', 'robert.brown@calicrane.com', 'Maintenance');

INSERT INTO course_categories (name, description) VALUES
('Safety Training', 'Courses related to workplace safety and procedures'),
('Technical Skills', 'Courses focused on technical equipment operation'),
('Maintenance', 'Courses about equipment maintenance and inspection'),
('Leadership', 'Courses for team leadership and management');

-- Add category_id and instructor_id columns to courses table if they don't exist
ALTER TABLE courses ADD COLUMN IF NOT EXISTS category_id INT DEFAULT 1;
ALTER TABLE courses ADD COLUMN IF NOT EXISTS instructor_id INT DEFAULT 1;
ALTER TABLE courses ADD COLUMN IF NOT EXISTS is_safety_training BOOLEAN DEFAULT FALSE;
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    progress INT DEFAULT 0,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE user_lesson_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id)
);

-- Add to your existing db.sql file
ALTER TABLE courses ADD COLUMN is_safety_training BOOLEAN DEFAULT FALSE;
ALTER TABLE courses ADD COLUMN image_path VARCHAR(255) DEFAULT 'images/course1.jpg';

-- Mark existing courses as safety training
UPDATE courses SET is_safety_training = TRUE;

-- Add sample non-safety courses
INSERT INTO courses (title, description, modules_count, image_path, is_safety_training) VALUES
('Advanced Crane Operations', 'Advanced techniques for crane operations in complex environments.', 6, 'images/course4.jpg', FALSE),
('Team Leadership Skills', 'Develop leadership skills for managing crane operation teams.', 5, 'images/course5.jpg', FALSE);

-- Insert sample data
INSERT INTO courses (title, description, modules_count, progress, status) VALUES
('Operating Cranes and Trucks', 'Learn the fundamentals of safely operating cranes and trucks in various working conditions.', 5, 100, 'completed'),
('Safety Gears, Tools, and Equipment', 'Understand the proper use and maintenance of safety equipment for crane and truck operations.', 4, 60, 'in_progress'),
('Maintenance and Inspection for Cranes and Trucks', 'Master the procedures for routine maintenance and inspection to ensure equipment safety.', 6, 0, 'not_started');

-- Insert lessons for course 1
INSERT INTO lessons (course_id, title, content, order_index) VALUES
(1, 'Module 1: Introduction to Crane Operations', '<h3>Module 1 Content</h3><p>Introduction to basic crane operations and safety protocols.</p>', 1),
(1, 'Module 2: Crane Controls and Functions', '<h3>Module 2 Content</h3><p>Understanding crane controls and their functions.</p>', 2),
(1, 'Module 3: Load Management', '<h3>Module 3 Content</h3><p>Techniques for proper load management.</p>', 3),
(1, 'Module 4: Safety Procedures', '<h3>Module 4 Content</h3><p>Essential safety procedures for crane operations.</p>', 4),
(1, 'Module 5: Practical Assessment', '<h3>Module 5 Content</h3><p>Practical assessment of crane operation skills.</p>', 5);

-- Insert lessons for course 2
INSERT INTO lessons (course_id, title, content, order_index) VALUES
(2, 'Module 1: Personal Protective Equipment', '<h3>Module 1 Content</h3><p>Introduction to personal protective equipment.</p>', 1),
(2, 'Module 2: Safety Tools', '<h3>Module 2 Content</h3><p>Understanding various safety tools.</p>', 2),
(2, 'Module 3: Equipment Maintenance', '<h3>Module 3 Content</h3><p>Maintenance procedures for safety equipment.</p>', 3),
(2, 'Module 4: Emergency Equipment', '<h3>Module 4 Content</h3><p>Emergency equipment and its proper usage.</p>', 4);

-- Insert lessons for course 3
INSERT INTO lessons (course_id, title, content, order_index) VALUES
(3, 'Module 1: Routine Maintenance', '<h3>Module 1 Content</h3><p>Introduction to routine maintenance procedures.</p>', 1),
(3, 'Module 2: Inspection Protocols', '<h3>Module 2 Content</h3><p>Detailed inspection protocols for cranes and trucks.</p>', 2),
(3, 'Module 3: Diagnostic Techniques', '<h3>Module 3 Content</h3><p>Diagnostic techniques for equipment issues.</p>', 3),
(3, 'Module 4: Preventive Maintenance', '<h3>Module 4 Content</h3><p>Preventive maintenance strategies.</p>', 4),
(3, 'Module 5: Repair Procedures', '<h3>Module 5 Content</h3><p>Common repair procedures for cranes and trucks.</p>', 5),
(3, 'Module 6: Documentation', '<h3>Module 6 Content</h3><p>Proper documentation of maintenance activities.</p>', 6);

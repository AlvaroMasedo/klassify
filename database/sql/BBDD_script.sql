-- =========================
-- BBDD KLASSIFY (SIMPLE)
-- =========================

CREATE DATABASE IF NOT EXISTS klassify
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE klassify;

-- -------------------------
-- INSTITUCIONS (centres)
-- -------------------------
CREATE TABLE institutions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NULL,
  city VARCHAR(100) NULL
) ENGINE=InnoDB;

-- -------------------------
-- USERS
-- role: student / teacher / admin
-- teacher_status: pending / verified / rejected (si role=teacher)
-- -------------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  surname VARCHAR(150) NULL,
  nickname VARCHAR(60) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,

  role ENUM('student','teacher','admin') NOT NULL DEFAULT 'student',
  teacher_status ENUM('pending','verified','rejected') NULL,

  specialization VARCHAR(150) NULL,
  institution_id INT NULL,

  is_private BOOLEAN NOT NULL DEFAULT FALSE,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (institution_id) REFERENCES institutions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------------
-- TEACHER REQUEST (simple)
-- (l' admin aproba o rebutja)
-- -------------------------
CREATE TABLE teacher_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  institutional_email VARCHAR(150) NOT NULL,
  institution_name VARCHAR(150) NOT NULL,
  institution_email VARCHAR(150) NOT NULL,
  status ENUM('submitted','approved','rejected') NOT NULL DEFAULT 'submitted',
  admin_notes VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------
-- CURSOS i ASSIGNATURES
-- -------------------------
CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL
) ENGINE=InnoDB;

-- -------------------------
-- RESOURCES (posts)
-- type: video / document / link / exam / image / audio
-- visibility: public / private
-- file_url: referencia a AWS S3 o enllaç extern
-- -------------------------
CREATE TABLE resources (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,

  title VARCHAR(200) NOT NULL,
  description TEXT NULL,

  type ENUM('video','document','link','exam','image','audio') NOT NULL,
  visibility ENUM('public','private') NOT NULL DEFAULT 'public',

  course_id INT NULL,
  subject_id INT NULL,

  file_url VARCHAR(600) NULL,     -- URL S3 o URL externa
  file_name VARCHAR(255) NULL,
  file_size BIGINT NULL,
  mime_type VARCHAR(120) NULL,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL,
  FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------------
-- COMMENTS
-- -------------------------
CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  resource_id INT NOT NULL,
  user_id INT NOT NULL,
  comment TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------
-- FAVORITES (guardar)
-- -------------------------
CREATE TABLE favorites (
  user_id INT NOT NULL,
  resource_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (user_id, resource_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------
-- FOLLOWS (seguir usuaris)
-- -------------------------
CREATE TABLE follows (
  follower_id INT NOT NULL,
  followed_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (follower_id, followed_id),
  FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (followed_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------
-- REPORTS (denuncies)
-- -------------------------
CREATE TABLE reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reporter_id INT NOT NULL,
  resource_id INT NOT NULL,
  reason VARCHAR(200) NOT NULL,
  status ENUM('open','resolved') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------
-- INCIDENTS (incidencies)
-- type: technical / user
-- -------------------------
CREATE TABLE incidents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type ENUM('technical','user') NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('open','in_progress','closed') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------
-- CALENDAR (organització simple)
-- -------------------------
CREATE TABLE calendar_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  resource_id INT NULL,
  title VARCHAR(200) NOT NULL,
  event_date DATE NOT NULL,
  notes VARCHAR(255) NULL,

  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE SET NULL
) ENGINE=InnoDB;

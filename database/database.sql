-- Busitema Internationalization database schema

CREATE DATABASE IF NOT EXISTS busitema_international
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE busitema_international;

CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(40) NOT NULL DEFAULT 'admin',
    last_login_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Site-wide single-value settings (office address, phone, email, hours, etc.)
-- Modelled as key/value pairs so new settings can be added without a migration.
CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(100) NOT NULL PRIMARY KEY,
    `value` TEXT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contact page "Your contact persons" cards.
CREATE TABLE IF NOT EXISTS contact_people (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    role VARCHAR(150) NOT NULL,
    bio TEXT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    phone VARCHAR(40) NULL,
    photo VARCHAR(255) NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Partner institutions shown on the Partnerships > Our Partners page.
-- seo_* columns are optional overrides - the automatic SEO title/description
-- come from name/about, the automatic image from logo. See app/helpers/seo.php.
CREATE TABLE IF NOT EXISTS partners (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL UNIQUE,
    slug VARCHAR(220) NOT NULL UNIQUE,
    region VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    about TEXT NULL,
    logo VARCHAR(255) NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    seo_title VARCHAR(200) NULL,
    seo_description VARCHAR(300) NULL,
    seo_image VARCHAR(255) NULL,
    is_indexed TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Cards shown on the Partnerships > Partnership Areas page.
CREATE TABLE IF NOT EXISTS partnership_areas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL UNIQUE,
    description TEXT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Scholarships, exchange, research grants, internships and fellowships shown
-- on the Opportunities page. eligibility and how_to_apply are stored as one
-- item per line and split into lists when rendered. seo_* columns are
-- optional overrides - see app/helpers/seo.php.
CREATE TABLE IF NOT EXISTS opportunities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL UNIQUE,
    slug VARCHAR(220) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    status ENUM('Open', 'Upcoming', 'Closed') NOT NULL DEFAULT 'Open',
    deadline VARCHAR(100) NOT NULL,
    summary VARCHAR(500) NOT NULL,
    overview TEXT NOT NULL,
    eligibility TEXT NULL,
    how_to_apply TEXT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    seo_title VARCHAR(200) NULL,
    seo_description VARCHAR(300) NULL,
    seo_image VARCHAR(255) NULL,
    is_indexed TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Campus cards shown on the About > About Busitema page.
CREATE TABLE IF NOT EXISTS campuses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    district VARCHAR(150) NOT NULL,
    focus VARCHAR(190) NOT NULL,
    description TEXT NOT NULL,
    link VARCHAR(255) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_new TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- News & Events published by the Internationalization Office (separate from the
-- university-wide news feed shown lower on the homepage). seo_* columns are
-- optional overrides - see app/helpers/seo.php.
CREATE TABLE IF NOT EXISTS news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    type ENUM('News', 'Event') NOT NULL DEFAULT 'News',
    event_date DATE NULL,
    location VARCHAR(190) NULL,
    summary VARCHAR(300) NOT NULL,
    body TEXT NOT NULL,
    image VARCHAR(255) NULL,
    published_at DATE NOT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    seo_title VARCHAR(200) NULL,
    seo_description VARCHAR(300) NULL,
    seo_image VARCHAR(255) NULL,
    is_indexed TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Homepage hero slider slides.
CREATE TABLE IF NOT EXISTS hero_slides (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) NOT NULL,
    eyebrow VARCHAR(190) NULL,
    title VARCHAR(200) NOT NULL UNIQUE,
    description VARCHAR(300) NULL,
    primary_label VARCHAR(100) NOT NULL,
    primary_url VARCHAR(255) NOT NULL,
    secondary_label VARCHAR(100) NULL,
    secondary_url VARCHAR(255) NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

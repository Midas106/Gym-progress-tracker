-- Iron Log — database schema
-- Import this once via phpMyAdmin (or `mysql -u root < schema.sql`) before using the app.

CREATE DATABASE IF NOT EXISTS gym_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gym_tracker;

-- One row per day of the week (0 = Sunday .. 6 = Saturday, matching JS Date.getDay()).
CREATE TABLE IF NOT EXISTS schedule (
  dow TINYINT PRIMARY KEY,
  name VARCHAR(50) NOT NULL DEFAULT 'Rest'
);

-- Exercises assigned to a given split day. Deleting one here does NOT delete its history in `logs`.
CREATE TABLE IF NOT EXISTS exercises (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dow TINYINT NOT NULL,
  name VARCHAR(100) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  FOREIGN KEY (dow) REFERENCES schedule(dow),
  UNIQUE KEY uniq_day_exercise (dow, name)
);

-- One row per exercise per calendar day. Re-saving the same exercise on the same date updates it in place.
CREATE TABLE IF NOT EXISTS logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  exercise_name VARCHAR(100) NOT NULL,
  log_date DATE NOT NULL,
  weight DECIMAL(6,2) NOT NULL DEFAULT 0,
  sets INT NOT NULL DEFAULT 0,
  reps INT NOT NULL DEFAULT 0,
  UNIQUE KEY uniq_exercise_date (exercise_name, log_date)
);

-- Manually-marked "went to the gym" days, independent of any exercise log.
-- Clicking a day on the calendar toggles a row here.
CREATE TABLE IF NOT EXISTS attendance (
  log_date DATE PRIMARY KEY
);

-- Seed the week with sane defaults (edit anytime from the app itself).
INSERT INTO schedule (dow, name) VALUES
  (0, 'Rest'), (1, 'Push'), (2, 'Pull'), (3, 'Legs'),
  (4, 'Rest'), (5, 'Full Body'), (6, 'Rest')
ON DUPLICATE KEY UPDATE name = VALUES(name);

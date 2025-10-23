<?php
require_once __DIR__ . '/core/init.php';

$db = DB::getInstance();

$sqlStatements = [
  // users
  "CREATE TABLE IF NOT EXISTS users (\n  id INT AUTO_INCREMENT PRIMARY KEY,\n  username VARCHAR(50) NOT NULL UNIQUE,\n  password VARCHAR(255) NOT NULL,\n  salt VARBINARY(32) NOT NULL,\n  name VARCHAR(100) NOT NULL,\n  joined DATETIME NOT NULL,\n  grupo INT NOT NULL DEFAULT 1\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
  // faculties
  "CREATE TABLE IF NOT EXISTS faculties (\n  id INT AUTO_INCREMENT PRIMARY KEY,\n  name VARCHAR(100) NOT NULL UNIQUE,\n  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
  // courses
  "CREATE TABLE IF NOT EXISTS courses (\n  id INT AUTO_INCREMENT PRIMARY KEY,\n  faculty_id INT NOT NULL,\n  name VARCHAR(150) NOT NULL,\n  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n  UNIQUE KEY uq_course_faculty_name (faculty_id, name),\n  CONSTRAINT fk_course_faculty FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE CASCADE\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
  // subjects
  "CREATE TABLE IF NOT EXISTS subjects (\n  id INT AUTO_INCREMENT PRIMARY KEY,\n  name VARCHAR(120) NOT NULL UNIQUE,\n  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
  // course_subjects
  "CREATE TABLE IF NOT EXISTS course_subjects (\n  id INT AUTO_INCREMENT PRIMARY KEY,\n  course_id INT NOT NULL,\n  subject_id INT NOT NULL,\n  UNIQUE KEY uq_course_subject (course_id, subject_id),\n  CONSTRAINT fk_cs_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,\n  CONSTRAINT fk_cs_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
  // registrations
  "CREATE TABLE IF NOT EXISTS registrations (\n  id INT AUTO_INCREMENT PRIMARY KEY,\n  user_id INT NOT NULL,\n  faculty_id INT NOT NULL,\n  course_id INT NOT NULL,\n  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n  UNIQUE KEY uq_user_registration (user_id),\n  CONSTRAINT fk_reg_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,\n  CONSTRAINT fk_reg_faculty FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE RESTRICT,\n  CONSTRAINT fk_reg_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE RESTRICT\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
];

foreach ($sqlStatements as $sql) {
  $db->query($sql);
}

// seed admin user if absent
$db->query('SELECT id FROM users WHERE username = ? LIMIT 1', ['admin']);
if (!$db->count()) {
  $salt = Hash::salt(32);
  $db->insert('users', [
    'username' => 'admin',
    'password' => Hash::make('admin123', $salt),
    'salt' => $salt,
    'name' => 'Administrador',
    'joined' => date('Y-m-d H:i:s'),
    'grupo' => 2,
  ]);
}

echo "Migração concluída. Utilizador admin/admin123 criado (se não existia).";

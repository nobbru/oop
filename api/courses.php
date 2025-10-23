<?php
require_once __DIR__ . '/../core/init.php';
header('Content-Type: application/json');

$facultyId = (int) (isset($_GET['faculty_id']) ? $_GET['faculty_id'] : 0);
$db = DB::getInstance();
$courses = [];
if ($facultyId > 0) {
    $db->query('SELECT id, name FROM courses WHERE faculty_id = ? ORDER BY name', [$facultyId]);
    $courses = $db->results();
}

echo json_encode($courses ?: []);

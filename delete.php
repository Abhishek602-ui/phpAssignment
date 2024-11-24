<?php
include 'includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT image FROM student WHERE id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && $student['image'] && file_exists("uploads/{$student['image']}")) {
        unlink("uploads/{$student['image']}");
    }

    $stmt = $pdo->prepare("DELETE FROM student WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;

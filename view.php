<?php
include 'includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$query = "
    SELECT student.*, classes.name AS class_name 
    FROM student 
    LEFT JOIN classes ON student.class_id = classes.class_id
    WHERE student.id = ?
";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body class="container">
    <h1>Student Details</h1>
    <a href="index.php" class="btn btn-secondary">Back</a>
    <div class="mt-3">
        <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
        <p><strong>Class:</strong> <?= htmlspecialchars($student['class_name']) ?></p>
        <p><strong>Created At:</strong> <?= htmlspecialchars($student['created_at']) ?></p>
        <p><strong>Image:</strong></p>
        <img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Student Image" width="150">
    </div>
</body>
</html>

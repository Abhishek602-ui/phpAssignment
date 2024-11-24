<?php
include 'includes/db.php';

$query = "
    SELECT student.*, classes.name AS class_name 
    FROM student 
    LEFT JOIN classes ON student.class_id = classes.class_id
    ORDER BY student.created_at DESC
";
$students = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body class="container">
    <h1>Student List</h1>
    <a href="create.php" class="btn btn-primary">Add Student</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Class</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= htmlspecialchars($student['class_name']) ?></td>
                    <td>
                        <img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Image" width="50">
                    </td>
                    <td>
                        <a href="view.php?id=<?= $student['id'] ?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit.php?id=<?= $student['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $student['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

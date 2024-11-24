<?php
include 'includes/db.php';

// Add class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO classes (name) VALUES (?)");
        $stmt->execute([$name]);
        header('Location: classes.php');
        exit;
    }
}

// Delete class
if (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM classes WHERE class_id = ?");
    $stmt->execute([$class_id]);
    header('Location: classes.php');
    exit;
}

// Fetch all classes
$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body class="container">
    <h1>Manage Classes</h1>
    <a href="index.php" class="btn btn-secondary">Back</a>
    <form action="" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="name" class="form-label">Class Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Class</button>
    </form>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $class): ?>
                <tr>
                    <td><?=

<?php
include 'includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM student WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $image = $_FILES['image'];

    if (!empty($name)) {
        $imagePath = $student['image'];
        if ($image['error'] == 0) {
            $allowed_extensions = ['jpg', 'png'];
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);

            if (in_array(strtolower($extension), $allowed_extensions)) {
                $filename = time() . '_' . $image['name'];
                move_uploaded_file($image['tmp_name'], "uploads/$filename");
                $imagePath = $filename;

                // Delete old image
                if ($student['image'] && file_exists("uploads/{$student['image']}")) {
                    unlink("uploads/{$student['image']}");
                }
            }
        }

        $stmt = $pdo->prepare("
            UPDATE student 
            SET name = ?, email = ?, address = ?, class_id = ?, image = ? 
            WHERE id = ?
        ");
        $stmt->execute([$name, $email, $address, $class_id, $imagePath, $id]);

        header('Location: index.php');
        exit;
    } else {
        $error = "Name is required.";
    }
}

$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body class="container">
    <h1>Edit Student</h1>
    <a href="index.php" class="btn btn-secondary">Back</a>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($student['email']) ?>">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea id="address" name="address" class="form-control"><?= htmlspecialchars($student['address']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <select id="class_id" name="class_id" class="form-select">
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['class_id'] ?>" <?= $class['class_id'] == $student['class_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($class['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" id="image" name="image" class="form-control">
            <img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Current Image" width="100" class="mt-2">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</body>
</html>

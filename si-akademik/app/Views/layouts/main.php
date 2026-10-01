<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <?php include __DIR__ . '/../partials/navbar.php'; ?>
   <main class="container mt-4">
        <?php require $content; ?>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?> 
</body>
</html>
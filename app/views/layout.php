<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
</head>
<body>
    <main>
        <?php require_once __DIR__ . $page['path']; ?>
    </main>
</body>
</html>
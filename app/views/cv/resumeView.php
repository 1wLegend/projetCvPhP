<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cv_id'])) {
    $cvId = $_POST['cv_id'];
    
    if (empty($cvId)) {
        error_log('cv_id is empty');
        header('Location: /home');
        exit;
    }

    $pdo = getPdo();
    $query = $pdo->prepare('SELECT * FROM resumes WHERE cv_id = :id');
    $query->execute(['id' => $cvId]);
    $cv = $query->fetch();

    if ($cv) {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>CV de <?php echo htmlspecialchars($cv['full_name']); ?></title>
            <link rel="stylesheet" href="/public/css/resumeView.css">
        </head>
        <body>
            <div class="cv-container">
                <h1><?php echo htmlspecialchars($cv['full_name']); ?></h1>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($cv['email']); ?></p>
                <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($cv['phone']); ?></p>

                <h2>Compétences</h2>
                <ul>
                    <?php
                    $competences = explode(',', $cv['skills']);
                    foreach ($competences as $competence) {
                        echo '<li>' . htmlspecialchars(trim($competence)) . '</li>';
                    }
                    ?>
                </ul>

                <h2>Expérience professionnelle</h2>
                <p><?php echo nl2br(htmlspecialchars($cv['experience'])); ?></p>

                <h2>Éducation</h2>
                <p><?php echo nl2br(htmlspecialchars($cv['education'])); ?></p>
                
                <a href="/home">Retour à l'accueil</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        error_log('No CV found for id: ' . $cvId);
        header('Location: /home');
        exit;
    }
} else {
    error_log('Invalid request method or cv_id not set');
    header('Location: /login');
    exit;
}

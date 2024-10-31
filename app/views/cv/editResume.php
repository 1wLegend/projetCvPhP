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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'experience' => $_POST['experience'] ?? '',
                'education' => $_POST['education'] ?? '',
                'skills' => $_POST['skills'] ?? '',
                'style' => $_POST['style'] ?? '',
            ];

            $updateQuery = $pdo->prepare('
                UPDATE resumes 
                SET full_name = :full_name, email = :email, phone = :phone, 
                    experience = :experience, education = :education, 
                    skills = :skills, style = :style 
                WHERE cv_id = :cv_id
            ');

            $updateResult = $updateQuery->execute([
                ':full_name' => $data['full_name'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':experience' => $data['experience'],
                ':education' => $data['education'],
                ':skills' => $data['skills'],
                ':style' => $data['style'],
                ':cv_id' => $cvId,
            ]);

            
        }
        ?>
        <link rel="stylesheet" href="/public/css/resume.css">
        <body>
            <div class="cv-container">
                <h1>Modifier le CV de <?php echo htmlspecialchars($cv['full_name']); ?></h1>

                <form method="POST" action="">
                    <input type="hidden" name="cv_id" value="<?php echo htmlspecialchars($cvId); ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Nom complet :</label>
                            <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($cv['full_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($cv['email']); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Téléphone :</label>
                            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($cv['phone']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="style">Style de CV (optionnel) :</label>
                            <input type="text" name="style" id="style" value="<?php echo htmlspecialchars($cv['style']); ?>">
                        </div>
                    </div>

                    <div class="form-group-full">
                        <label for="experience">Expérience :</label>
                        <textarea name="experience" id="experience" required><?php echo htmlspecialchars($cv['experience']); ?></textarea>
                    </div>

                    <div class="form-group-full">
                        <label for="education">Éducation :</label>
                        <textarea name="education" id="education" required><?php echo htmlspecialchars($cv['education']); ?></textarea>
                    </div>

                    <div class="form-group-full">
                        <label for="skills">Compétences :</label>
                        <textarea name="skills" id="skills" required><?php echo htmlspecialchars($cv['skills']); ?></textarea>
                    </div>

                    <div class="button-group">
                        <button type="submit">Mettre à jour le CV</button>
                        <a href="/home" class="btn-link">Retour à l'accueil</a>
                    </div>
                </form>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
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

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => $_POST['full_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'experience' => $_POST['experience'],
        'education' => $_POST['education'],
        'skills' => $_POST['skills'],
        'style' => $_POST['style'],
        'user_id' => $_SESSION['id']
    ];

    if ($member->addResume($data)) {
        header("Location: /dashboard");
        exit;
    } else {
        echo "Erreur lors de la création du CV.";
    }
}
?>

<link rel="stylesheet" href="/public/css/resume.css">
<div class="container">
    <h1>Créer un CV</h1>

    <form action="" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="full_name">Nom complet :</label>
                <input type="text" name="full_name" id="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone">Téléphone :</label>
                <input type="text" name="phone" id="phone" required>
            </div>
            <div class="form-group">
                <label for="style">Style de CV (optionnel) :</label>
                <input type="text" name="style" id="style">
            </div>
        </div>

        <div class="form-group-full">
            <label for="experience">Expérience :</label>
            <textarea name="experience" id="experience" required></textarea>
        </div>

        <div class="form-group-full">
            <label for="education">Éducation :</label>
            <textarea name="education" id="education" required></textarea>
        </div>

        <div class="form-group-full">
            <label for="skills">Compétences :</label>
            <textarea name="skills" id="skills" required></textarea>
        </div>

        <div class="button-group">
            <button type="submit">Créer CV</button>
            <a href="/dashboard" class="btn-link">Accueil</a>
        </div>
    </form>
</div>
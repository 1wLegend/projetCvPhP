<?php
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    if (isset($member)) {
        $user = $member->getUserById($userId);
        $cvs = $member->getUserResumes($userId);
        $last_login = $member->getLastConnexion($userId);
    } else {
        $user = null;
        $cvs = [];
        $last_login = null;
    }
} else {
    $user = null;
    $cvs = [];
    $last_login = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $userId = intval($_POST['user_id']);
    $username = $_POST['username'];
    $picture = null;

    if (!empty($_FILES['picture']['name'])) {
        $picture = $member->handleProfilePicture($username);
    }

    if ($member->editUser($userId, $username, $picture)) {
        $_SESSION['username'] = $username;
        if ($picture) {
            $_SESSION['picture'] = $picture;
        }
        echo "<script>
        alert('Profil mis à jour avec succès.');
        window.location.href = '/userPage?id=" . $userId . "';
        </script>";
    } else {
        echo "<script>alert('Erreur lors de la mise à jour du profil.');</script>";
    }
}

if (isset($_GET['delete_cv'])) {
    $member->deleteResume($_GET['delete_cv']);
    echo "<script>
    window.location.href = '/dashboard';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Profil</title>
    <link rel="stylesheet" href="../../public/css/profil.css">
    <script>
        function openModal(userId, username) {
            document.getElementById('modal').style.display = 'flex';
            document.getElementById('user_id').value = userId;
            document.getElementById('username').value = username;
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Détails du Profil</h1>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if ($user): ?>
        <div class="user-info">
            <?php 
                if ($member->isLogged()) {
                    if (!empty($user['picture'])) {
                        $avatar = '/../upload/' . $user['picture'];
                        echo '<img src="' . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') . '" alt="Avatar" onerror="this.onerror=null; this.src=\'../../public/img/defaut_avatar.jpg\';">';
                    } else {
                        echo '<img src="../../public/img/defaut_avatar.jpg" alt="Avatar">';
                    }
                }
            ?>
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Role : <?php echo htmlspecialchars($user['role']);?></p>

            <button class="custom-button" onclick="openModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')">Modifier le profil</button>

        </div>

        <?php if (!empty($cvs)): ?>
        <div class="resume">
            <h3>CVs de l'utilisateur</h3>
            <table border="0">
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Style</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cvs as $cv): ?>
                    <tr>
                        <td><?= htmlspecialchars($cv['full_name']) ?></td>
                        <td><?= htmlspecialchars($cv['email']) ?></td>
                        <td><?= htmlspecialchars($cv['phone']) ?></td>
                        <td><?= htmlspecialchars($cv['style']) ?></td>
                        <td><?= htmlspecialchars($cv['created_at']) ?></td>
                        <td class="action-links">
                            <div class="button-group">
                                <form method="POST" action="/resumeView" style="margin-right: 5px;">
                                    <input type="hidden" name="cv_id" value="<?php echo $cv['cv_id']; ?>">
                                    <button type="submit" class="btn btn-view">Voir</button>
                                </form>
                                <form method="POST" action="/downloadResume" style="margin-right: 5px;">
                                    <input type="hidden" name="cv_id" value="<?php echo $cv['cv_id']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $cv['user_id']; ?>">
                                    <button type="submit" class="btn btn-download">Télécharger</button>
                                </form>
                                <form method="POST" action="/editResume" style="margin-right: 5px;">
                                    <input type="hidden" name="cv_id" value="<?php echo $cv['cv_id']; ?>">
                                    <button type="submit" class="btn btn-edit">Modifier</button>
                                </form>
                                <a href="/userPage?delete_cv=<?= urlencode($cv['cv_id']) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce CV ?')" class="btn btn-danger">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="empty-message">Aucun CV trouvé pour cet utilisateur.</p>
        <?php endif; ?>

        <?php if ($last_login): ?>
        <div class="logs">
            <h3>Dernière Connexion</h3>
            <p><?php echo htmlspecialchars($last_login['connexion_time']); ?></p>
        </div>
        <?php else: ?>
        <p>Aucun log de connexion trouvé pour cet utilisateur.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Aucun utilisateur trouvé avec cet ID.</p>
    <?php endif; ?>

    <a href="/home" class="back-button">Retour au Dashboard</a>
</div>

<div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center;">
    <div style="background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; width: 100%;">
        <h3>Modifier le profil</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" id="user_id" name="user_id">
            
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            
            <label for="picture">Image de profil :</label>
            <input type="file" id="picture" name="picture">
            
            <button type="submit" name="update_profile">Enregistrer les modifications</button>
            <button type="button" onclick="closeModal()">Annuler</button>
        </form>
    </div>
</div>

</body>
</html>

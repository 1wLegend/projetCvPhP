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

?>

<link rel="stylesheet" href="../../public/css/profil.css">

<div class="container">
    <h1>Détails du Profil</h1>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
        <?php unset($_SESSION['error_message']); ?>
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
                        <form method="POST" action="/resumeView" style="margin-right: 5px;">
                                    <input type="hidden" name="cv_id" value="<?php echo $cv['cv_id']; ?>">
                                    <button type="submit" class="btn btn-view">Voir</button>
                                </form>
                                <form method="POST" action="/downloadResume" style="margin-right: 5px;">
                                    <input type="hidden" name="cv_id" value="<?php echo $cv['cv_id']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $cv['user_id']; ?>">
                                    <button type="submit" class="btn btn-download">Télécharger</button>
                                </form>
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

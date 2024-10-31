<?php

if (isset($member)) {
    $user_logs = $member->getAllMembers();
} else {
    $user_logs = [];
}

if (isset($_GET["delete_user"]))
{
    $member->deleteUserById($_GET["delete_user"]);
    echo "<script> window.location.href = '/panelAdmin'; </script>";
}


?>
<link rel="stylesheet" href="/public/css/dashboard.css">
<h1>Dashboard des utilisateurs</h1>

<div class="user-grid">
    <?php foreach ($user_logs as $user): ?>
    <div class="user-card">
        <?php 
          if ($member->isLogged() ){
            if (!empty($user['picture'])) {
                $avatar = '/../upload/' . $user['picture'];
                echo '<img src="' . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') . '" alt="Avatar" onerror="this.onerror=null; this.src=\'../../public/img/defaut_avatar.jpg\';">';
            } else{
            echo'<img src = "../../public/img/defaut_avatar.jpg">';
            }
        }
        ?>
        <h3><?php echo htmlspecialchars($user['username']); ?></h3>
        <p>@Contact : <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                <?php echo htmlspecialchars($user['email']); ?></a></p>
        <a href="/profil?id=<?php echo $user['id']; ?>">Voir le profil</a>
        <a href="/panelAdmin?delete_user=<?= $user['id'] ?>"onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce CV ?')">Supprimer</a>
    </div>
    <?php endforeach; ?>
</div>

<div class="create-new">
    <a href="/home">Accueil</a>
</div>

<script>
    <?php foreach ($user_logs as $user): 
        $avatar = $member->handleAndGetAvatar($user['username'], $user['id']); ?>
        console.log("User ID: " + "<?php echo $user['id']; ?>" + " - Avatar Path: " + "<?php echo htmlspecialchars($avatar); ?>");
    <?php endforeach; ?>
</script>

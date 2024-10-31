<<?php
    $username = $member->get('username') ?: 'Guest';
    $id = $member->get('id') ?:'None';
    $email = $member->get('email') ?: 'Not provided';
    $picture = $member->get('picture') ?: '../public/img/defaut_avatar.jpg';
    $role = $member->get('role') ?:'Unknown';
?>

<link rel="stylesheet" href="/public/css/styles.css">
<script src="https://kit.fontawesome.com/459ca3d53b.js" crossorigin="anonymous"></script>
<div class="icon-right-page">
    <i class="fa-solid fa-moon" style="display: none;"></i>
    <i class="fa-regular fa-moon"></i>
</div>

<ul>
    <img id="flip-logo" src="../public/img/yllusion.png" alt="Yllusion RP">
    <hr class="line">
    <li><a href="home"><i class="fa-solid fa-house separation-deux-nav"></i>Accueil</a></li>
    <li><a href="dashboard">
            <p class="separation-deux-nav">Espace WhiteList</p>
        </a></li>
    <li><a href=""><i class="fa-solid fa-question"></i> Questionnaire</a></li>
    <li><a href=""><i class="fa-solid fa-circle-info"></i> Informations HRP</a></li>
    <li><a href="/resume"><i class="fa-solid fa-user"></i> Crée son personnage</a></li>
    <li><a href="/users"><i class="fa-solid fa-user-tie"></i> Liste des personnages</a></li>
    <?php
    if ($member->isLogged() === true && $member->isAdmin() === true) {
        echo '<li><a href="/panelAdmin"><i class="fa-duotone fa-solid fa-hammer"></i> Panel Admin</a></li>';
    }
    ?>
    <div class="bottom-page">
        <li><a href="#contact"><i class="fa-solid fa-envelope"></i> Un problème ?</a></li>
    </div>
</ul>

<div class="navbartopjustevatar">
    <div class="navbartopjustevatar-content">
        <div class="navbartopjustevatar-content-avatar">
            <?php 
           if ($member->isLogged()) {
            if (!empty($picture)) {
                $avatar = '/../upload/' . $picture;
                echo '<img src="' . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') . '" alt="Avatar" onerror="this.onerror=null; this.src=\'../../public/img/defaut_avatar.jpg\';">';
            } else {
                echo '<img src="../public/img/defaut_avatar.jpg" alt="Avatar">';
            }
            echo '<a href="/userPage?id=' . $id . '"><p>' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . '</p></a>';
        }
        
        ?>
        </div>
        <div class="navbartopjustevatar-content-logout">
            <?php 
            if ($member->isLogged()) {
                echo '<a href="/logout">Se déconnecter</a>';
            } else {
                echo '<a href="/allLogin" class="btn btn-ghost">Se connecter</a>';
            }
            ?>
        </div>
    </div>
</div>

<div class="pageurl">
    <div class="url-bar">
        <div class="buttons">
            <div class="button close"></div>
            <div class="button minimize"></div>
            <div class="button maximize"></div>
        </div>
        <p>ACCUEIL/PAGE.HTML</p>
    </div>
</div>

<div class="middle-box">
    <div class="middle-box-content">
        <div class="middle-box-content-title">
            <h1>Bienvenue sur le Yllusion RP</h1><br>
        </div>
        <div class="middle-box-content-text">
            <p>Yllusion RP est un serveur RP sur GTA V qui a pour but de vous offrir une expérience de jeu unique.
                Vous pourrez incarner le personnage de votre choix et vivre des aventures incroyables dans la ville
                de Los Santos.</p>
        </div>
    </div>
</div>
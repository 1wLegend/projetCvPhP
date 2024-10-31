<?php

if (isset($_SESSION['user_token'])) {
  header("Location: quizz.php");
  exit();
}
?>

<link rel="stylesheet" href="/public/css/allLogin.css">
<div class="container">
    <div class="top-section">
        <h1>Bienvenue sur <span class="highlight">Yllusion Roleplay</span></h1>
        <p class="info-text panel">Il est raconté que des fois le panel marche mieux quand vous êtes connecté.</p>
    </div>

    <a href="login"><button class="btn emailLogging">Connexion par mail</button></a>

    <div class="divider">
        <hr class="divider-line">
        <p class="account-text"> Connexion via les réseaux </p>
        <hr class="divider-line">
    </div>

    <div class="buttons">
        <a href="init-oauth"><button class="btn discord">Se connecter via Discord !</button></a>
        <button class="btn google" onclick="connectGoogle()">Se connecter via Google !</button>
    </div>

    <div class="divider">
        <hr class="divider-line">
        <p class="account-text">Vous n'avez pas de compte ?</p>
        <hr class="divider-line">
    </div>

    <div class="bottom-section">
        <p class="info-text noaccount">
            Tu souhaites intégrer notre communauté et vivre une expérience RolePlay inédite ?<br>
            Ne perds plus une minute, clique sur le bouton ci-dessous pour débuter l'aventure !
        </p>
        <a href="register"><button class="btn create">Créer votre compte !</button></a>
    </div>

    <footer class="footer">
        <div class="footer-divider">
            <hr class="footer-line">
            <p>© Yllusion RP</p>
            <hr class="footer-line">
        </div>
    </footer>
</div>

<script>
function connectGoogle() {
    window.location.href = "<?php echo $client->createAuthUrl(); ?>";
}
</script>

<script src="script.js"></script>
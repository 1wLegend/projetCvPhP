<?php 
    $member->handleLogin($page, $member);
?>
<link rel="stylesheet" href="/public/css/register.css">
<div class="register-container">
    <h1>Connexion à <span class="highlight">Yllusion</span></h1>
    <?php if (isset($error)): ?>
        <div role="alert" class="alert alert-error mb-4">
            <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 shrink-0 stroke-current"
                    fill="none"
                    viewBox="0 0 24 24">
                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="usr" class="input-field" placeholder="Username / Email" required><br>
        <input type="password" name="pwd" class="input-field" placeholder="Password" required><br>
        <input type="submit" name="sub" class="submit-btn" value="Se connecter">
    </form>
    <br>
    <a href="/allLogin" class="login-link">Menu de connexion</a>
    <p>Pas encore de compte ? <a href="/register" class="login-link">S'inscrire</a></p>
</div>
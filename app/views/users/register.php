<?php 
$member->handleRegister($page, $member);
?>  

<link rel="stylesheet" href="/public/css/register.css">
<div class="register-container">
        <h1>Inscription sur <span class="highlight">Yllusion</span></h1>
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
        <form action="" method="post" enctype="multipart/form-data">
            <input type="email" name="email" id="email" placeholder="Email" required><br>

            <?php if (!empty($username_error)) { echo "<span style='color: red;'>$username_error</span><br>"; } ?>
            <input type="text" name="username" id="username" placeholder="Username" required><br>

            <input type="password" name="password" id="password"  placeholder="Password"required><br>

            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required><br>

            <label for="picture">Image de profil :</label>  
            <input type="file" class="file-input file-input-ghost w-full max-w-xs" name="picture" id="picture"><br> 
            
            <input type="submit" name="submit" value="S'inscrire" class="submit-btn">
        </form>
        <br>
        <a href="/allLogin" class="login-link">Menu de connexion</a>
        <p>Déjà un compte ? <a href="/login" class="login-link">Se connecter</a></p>
    </div>  
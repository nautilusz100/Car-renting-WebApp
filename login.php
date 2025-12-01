<?php
    session_start();
    if (isset($_SESSION['user_email'])) {
        header("location: index.php");
        exit();
    }
    $email = $_POST['email'] ?? '';
    $passw = $_POST['passw'] ?? '';
    $fail = false;
    if ($_POST){
        $users = json_decode(file_get_contents('data\users.json'), true);
        $matches = array_filter($users, fn($u) => $u['email'] == $email);

        if (count($matches) > 0)
        {
            $keys = array_keys($matches);
            $firsthit = $matches[$keys[0]];
            if (password_verify($passw, $firsthit['hashed_password'])){
                $_SESSION['user_email'] = $keys[0];
                $_SESSION['admin'] = $firsthit['admin'];
                header("location: index.php");
                exit();
            } else $fail = true;
        } else $fail = true;
    }


?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <header class="top-bar">
        <a href="index.php" class="logo" >iKarRental</a>
        <div class="buttons">
            <a href="login.php" class="button">Bejelentkezés</a>
            <a href="reg.php" class="yellow_button">Regisztráció</a>
        </div>
    </header>
    <div class="form_div_login">
        <h1 id="belepes">Belépés<h1>
        <form action="login.php" method="POST" novalidate>
            <label for="email" class="labels_form"> E-mail cím</label><br>
            <input type="text" id="email" name="email" value="<?= $email ?>"><br>
            <label for="passw" class="labels_form">Jelszó</label><br>
            <input type="password" id="passw" name="passw" value="<?= $passw ?>"><br>
            <input class="yellow_button" type="submit" value="Belépes">
        </form>
        <?php if ($fail): ?>
        <span class="error">Hibás email cím vagy jelszó!</span>
    <?php endif; ?>
    </div>

    
</body>
</html>
<?php
    session_start();
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $passw = $_POST['passw'] ?? '';
    $passwagain = $_POST['passwagain'] ?? '';


    if(count($_POST) > 0)
    {
        $errors = [];
        if ( $full_name === '' ){
            $errors['full_name'] = 'A nevet meg kell adni!';
        } else if ( count(explode(' ', $full_name)) < 2 ) {
            $errors['full_name'] = 'Legalább két szó kell!';
        }
        if ( $email === ''){
            $errors['email'] = 'Az e-mailt meg kell adni!';
        } else if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ){
            $errors['email'] = 'Az e-mail nem valid!';
        }
        if ($passw === '') {
            $errors['passw'] = 'A jelszót meg kell adni!';
        } else {
            if (strlen($passw) < 8) {
                $errors['passw'] = 'A jelszónak legalább 8 karakter hosszúnak kell lennie!';
            }
            if (count(array_filter(str_split($passw), fn($x) => $x >= 'A' && $x <= 'Z')) === 0) {
                $errors['passw'] = 'A jelszónak tartalmaznia kell legalább egy nagybetűt!';
            }
            if (count(array_filter(str_split($passw), fn($x) => $x >= 'a' && $x <= 'z')) === 0) {
                $errors['passw'] = 'A jelszónak tartalmaznia kell legalább egy kisbetűt!';
            }

            if (count(array_filter(str_split($passw), fn($x) => $x >= '0' && $x <= '9')) === 0) {
                $errors['passw'] = 'A jelszónak tartalmaznia kell legalább egy számot!';
            }
        }
        if($passw !== $passwagain)
        {
            $errors['passwagain'] = 'A jelszavak nem egyeznek!';
        }
        if(count($errors) == 0)
        { 
            $reg = json_decode(file_get_contents('data/users.json'), true);
            if(!isset($reg[$email])){
                $hashed_password = password_hash($passw, PASSWORD_DEFAULT);
                $reg[$email] = [
                    'fullname' => $full_name,
                    'email' => $email,
                    'hashed_password' => $hashed_password,
                    'admin' => 0
                ];
            }else{
                $errors['valid'] = 'Ilyen email címmel már létezik felhasználó!';
            }

            file_put_contents('data/users.json', json_encode($reg, JSON_PRETTY_PRINT));



        }
    }


?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="styles/reg.css">
</head>
<body>
    <header class="top-bar">
        <a href="index.php" class="logo" >iKarRental</a>
        <div class="buttons">
            <?php if (isset($_SESSION['user_email'])): ?>
                <a href="profile.php" class="profile_button">
                    <img class="profile_img" href="profile.php"src="https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?t=st=1735511198~exp=1735514798~hmac=788df6cec3ad76bf16d5833f54b5a53bc4168d782eac3980b0f9c1f9460ee707&w=740">
                </a>
                <a href="logout.php" class="yellow_button">Kijelentkezés</a>
            <?php else: ?>
                <a href="login.php" class="button">Bejelentkezés</a>
                <a href="reg.php" class="yellow_button">Regisztráció</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="form_div_reg">
        <h1 id="belepes">Regisztráció<h1>
        <form action="reg.php" method="POST" novalidate>
            <label for="full_name" class="labels_form">Teljes név</label><br>
            <input type="text" id="full_name" name="full_name" value="<?= $full_name ?>">
            <a class="error"><?= $errors['full_name'] ?? '' ?></a><br>
            <label for="email" class="labels_form"> E-mail cím</label><br>
            <input type="text" id="email" name="email" value="<?= $email ?>">
            <a class="error"><?= $errors['email'] ?? '' ?></a><br>
            <label for="passw" class="labels_form">Jelszó</label><br>
            <input type="password" id="passw" name="passw" value="<?= $passw ?>">
            <a class="error"><?= $errors['passw'] ?? '' ?></a><br>
            <label for="passwagain" class="labels_form">Jelszó mégegyszer</label><br>
            <input type="password" id="passwagain" name="passwagain" value="<?= $passw ?>">
            <a class="error"><?= $errors['passwagain'] ?? '' ?></a><br>
            <input class="yellow_button" type="submit" value="Regisztráció">
        </form>
        <a class="error"><?= $errors['valid'] ?? '' ?></a><br>
        <?php if(count($_POST) && count($errors) == 0) : ?>
        <span style='color: green'> Sikeres regisztráció!</span>
        <?php endif; ?>
    </div>
    
</body>
</html>
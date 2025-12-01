<?php
    session_start();
    $month = date('m');
    $day = date('d');
    $year = date('Y');

    $today = $year . '-' . $month . '-' . $day;
    $newDate = date('Y-m-d', strtotime($today . ' +5 days'));

    if(!isset($_GET['car_id']))
        {
            header('location: index.php');
            exit();
        }
    $car_id = $_GET['car_id'];
    $cars = json_decode(file_get_contents('data\cars.json'), true);
    $r = null;
    foreach ($cars as $car)
    {
        if($car['id'] == $car_id)
        {
            $r = $car;
            break;
        }

    }
    if(!$r === null)
    {
        header('location: index.php');
        exit();
    }
   
    
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car</title>
    <link rel="stylesheet" href="styles/carwindow.css">
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
    <div class="eltuntet">
        <div id="car_name">
            <a id="brand_text"><?= $r['brand'] ?> </a>
            <a id="model_text"><?= $r['model'] ?></a>
        </div>
        <div class="information">
            <div class="imgdiv">
                <img src="<?= $r['image']?>">
            </div>
            <div class="infos">
                <div class="info_panel">
                    <div class="card">
                        <div id="infos_car">
                            <?php
                            $fuel_type = '';
                            if ($r['fuel_type'] === 'Petrol') {
                                $fuel_type = 'Benzin';
                            } elseif ($r['fuel_type'] === 'Diesel') {
                                $fuel_type = 'Dízel';
                            } else {
                                $fuel_type = 'Elektromos';
                            }
                            ?>
                            <a>Üzemanyag: <?= $fuel_type ?></a><br>
                            <a>Váltó: <?= $r['transmission'] === 'Manual' ? 'Manuális' :  'Automata'?></a><br>
                            <a>Gyártási év: <?= $r['year']?></a><br>
                            <a>Férőhelyek száma: <?= $r['passengers']?></a><br>
                        </div>
                        <div class="alul_legyen">
                            <div id="car_price" >
                                <a><?= number_format((int)$r['daily_price_huf'],0,' ', '.') ?> Ft</a>
                                <a id="nap">/nap</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="buttons2">

                    <form action="reserv.php?car_id=<?=$r['id']?>" method="POST" novalidate>
                            <input type="date" id="startdate" name="startdate" value="<?= $today; ?>">
                            <label for="startdate">-tól</label>
                            <input type="date" id="enddate" name="enddate" value="<?= $newDate; ?>" >
                            <label for="enddate">-ig</label><br>
                            <?php if (isset($_SESSION['user_email'])): ?>
                            <input class="yellow_button2" type="submit" value="Lefoglalom">
                            <?php endif; ?>
                    </form>
                    <?php if (!isset($_SESSION['user_email'])): ?>
                            <a class="blue_button" href="index.php">Lefoglalom</a><br>
                            <a class="error">Be kell jelenkezni, hogy tudjon foglalni!</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="success_div">
            <div class="imgdiv">
                <img src="<?= $r['image']?>">
            </div>
            <div class="infos2">
                <div class="info_panel">
                    <div class="card2">
                        <div id="infos_car">
                            <?php
                            $fuel_type = '';
                            if ($r['fuel_type'] === 'Petrol') {
                                $fuel_type = 'Benzin';
                            } elseif ($r['fuel_type'] === 'Diesel') {
                                $fuel_type = 'Dízel';
                            } else {
                                $fuel_type = 'Elektromos';
                            }
                            ?>
                            <a>Üzemanyag: <?= $fuel_type ?></a><br>
                            <a>Váltó: <?= $r['transmission'] === 'Manual' ? 'Manuális' :  'Automata'?></a><br>
                            <a>Gyártási év: <?= $r['year']?></a><br>
                            <a>Férőhelyek száma: <?= $r['passengers']?></a><br>
                        </div>
                    </div>
                </div>
            </div>
        <a id="succes_text"> Sikeres foglalás!</a>
        <a id="text_succ"> </a>
        <a href="profile.php" class="yellow_button">Profilom</a>
    </div>
    <div class="error_div">
        <img src="https://www.freeiconspng.com/thumbs/error-icon/error-icon-4.png">
        <a id="error_text"> Sikeretelen foglalás!</a>
        <a id="text_error"> </a>
        <a href="carwindow.php?car_id=<?=$r['id']?>" class="yellow_button">Vissza a jármű oldalára</a>
    </div>
    <script type="module" src="reserve_ajax.js"></script>
</body>
</html>
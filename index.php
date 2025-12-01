<?php

    session_start();
    $month = date('m');
    $day = date('d');
    $year = date('Y');


    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? ''; 
    
    $cars = json_decode(file_get_contents('data/cars.json'), true);
    $reservs = json_decode(file_get_contents('data/reserved.json'), true);
    $space = $_POST['space'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $artol = $_POST['artol'] ?? '';
    $arig = $_POST['arig'] ?? '';
    if(count($_POST) > 0)
    {
        $filtercars = [];
        foreach ($cars as $car)
        {
            if($car['passengers'] >  $space || $space == '')
            {

                if($car['transmission'] == $transmission || $transmission  == '')
                {
                    if(($car['daily_price_huf'] >= $artol && $car['daily_price_huf'] <= $arig ) || $artol === '' || $arig === '')
                    {
                        $lehet = true;
                        foreach($reservs as $res)
                        {

                            if($car['id'] == (int)$res['id'] && $startdate  != '' && $enddate != '')
                            {
                                $reserved_start = strtotime($res['start']);
                                $reserved_end = strtotime($res['end']);
                                if (!(strtotime($enddate) < $reserved_start || strtotime($startdate) > $reserved_end)) {
                                    $lehet = false;
                                }

                            }

                        }
                        if($lehet)
                        {
                            $filtercars[] = $car;
                        }

                    }
                }
            }
        }
    }else
    {
        $filtercars = $cars;
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
<header class="top-bar">
    <a href="index.php" class="logo">iKarRental</a>
    <div class="buttons">
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="profile.php" class="profile_button">
                <img class="profile_img" href="profile.php"src="https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?t=st=1735511198~exp=1735514798~hmac=788df6cec3ad76bf16d5833f54b5a53bc4168d782eac3980b0f9c1f9460ee707&w=740">
            </a>
            <a href="logout.php" class="yellow_button_alap">Kijelentkezés</a>
        <?php else: ?>
            <a href="login.php" class="button">Bejelentkezés</a>
            <a href="reg.php" class="yellow_button_alap">Regisztráció</a>
        <?php endif; ?>
    </div>
</header>
        <div class="text_search">
            <div class="header_button_div">
                <div class="text">
                    Kölcsönözz autókat könnyedén!
                </div>
                <a href="reg.php" class="yellow_button_alap">Regisztráció</a>
            </div>
            <div class="search_form">
                <form action="index.php" method="POST" novalidate>
                    <input type="number" id="space" name="space" value="<?=$space?>" step="1" placeholder="0">
                    <label for="space"> férőhely</label>
                    <input type="date" id="startdate" name="startdate"  value="<?= $startdate; ?>">
                    <label for="startdate">-tól</label>
                    <input type="date" id="enddate" name="enddate" value="<?= $enddate; ?>">
                    <label for="enddate">-ig</label><br>
                    <select id="transmission" name="transmission" >
                        <option value="default_selected" disabled <?= $transmission === '' ? 'selected' : '' ?>>Váltó típusa</option>
                        <option value="Automatic" <?= $transmission  === 'Automatic' ? 'selected' : '' ?>>Automata</option>
                        <option value="Manual" <?= $transmission === 'Manual' ? 'selected' : '' ?>>Manuális</option>
                    </select>
                    <input type="number" id="artol" name="artol" placeholder="14000"  value="<?= $artol; ?>">
                    <label for="artol"> - </label>
                    <input type="number" id="arig" name="arig" placeholder="21000" value="<?= $arig; ?>" >
                    <label for="artol">Ft</label>
                    <input class="yellow_button_alap" type="submit" value="Szűrés">
                </form>
            </div>
        </div>
        <div class="grid">
            <?php foreach ($filtercars as $car): ?>
                <div class="card">
                    <img src="<?=$car['image'] ?>">
                    <div class="card_div">
                        <div class="info">
                            <h3 id="ar"><?= number_format((int)$car['daily_price_huf'],0,' ', '.') ?> Ft</h3>
                            <div id="block">
                                <a href="carwindow.php?car_id=<?=$car['id']?>" id="brand_text"><?= $car['brand'] ?></a>
                                <a href="carwindow.php?car_id=<?=$car['id']?>" id="model_text"><?= $car['model'] ?></a>
                            </div>
                            <h3><?= $car['passengers'] ?> férőhely - <?= $car['transmission'] === 'Manual' ? 'manuális' :  'automata'?> </h3>
                        </div>
                        <div>
                            <a href="carwindow.php?car_id=<?= $car['id'] ?>" class="yellow_button">Foglalás</a>
                            <?php if ( isset($_SESSION['user_email']) && $_SESSION['admin'] == 1): ?>
                                <a href="edit.php?car_id=<?= $car['id'] ?>" class="edit_button">Szerkesztés</a>
                                <a href="delete.php?car_id=<?= $car['id'] ?>" class="delete_button">Törlés</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ( isset($_SESSION['user_email']) && $_SESSION['admin'] == 1): ?>
                <div class="card">
                    <img src="https://cdn2.iconfinder.com/data/icons/pretty-office-part14-2/256/FAQ_yellow-512.png">
                    <div class="card_div">
                        <div class="centerit">
                            <a href="create_car.php" class="yellow_button">Új kocsi hozzáadása</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
</body>
</html>
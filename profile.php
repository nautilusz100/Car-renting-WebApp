<?php
    session_start();
    if (!isset($_SESSION['user_email'])) {
        header("location: index.php");
        exit();
    }
    $email = $_SESSION['user_email'];
    $users = json_decode(file_get_contents('data/users.json'), true);
    $full_name = $users[$email]['fullname'];
    $reserved = json_decode(file_get_contents('data/reserved.json'), true);
    $cars = json_decode(file_get_contents('data/cars.json'), true);
    $reslist = [];
    foreach ($reserved as $reservation) {
        if ($reservation['email'] === $email) {
            $reslist[] = $reservation;
        }
    }
    if($_SESSION['admin'] == 1)
    {
        $reslist = $reserved;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
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
    <div class="prof">
        <div class="info_tab">
            <div class="profile_div">
                <img src="https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?t=st=1735511198~exp=1735514798~hmac=788df6cec3ad76bf16d5833f54b5a53bc4168d782eac3980b0f9c1f9460ee707&w=740">
            </div>
            <div class="name_div">
                <a id="alap"> Bejelentkezve mint:</a>
                <a id="foglal"><?= $full_name; ?></a>
            </div>
        </div>
        <div id="lista">
            <a>Foglalásaim</a>
        </div>
        <div class="grid">
            <?php foreach ($reslist as $key => $reservation ): ?>
                <div class="card">
                    <?php 
                    $id = $reservation['id'];
                    $car = null;
                    foreach ($cars as $car_data) {
                        if ($car_data['id'] == (int)$id) {
                            $car = $car_data;
                            break;
                        }

                    }
                    ?>
                     <?php if($car != null): ?>
                        <img src="<?=$car['image'] ?>">
                        <div class="card_div">
                            <div>
                                <div id="block">
                                    <h3 id="datum"><?= date('m.d',strtotime($reservation['start'])) ?> - <?= date('m.d',strtotime($reservation['end'])) ?></h3>
                                </div>
                                <a id="brand_text"><?= $car['brand'] ?></a>
                                <a id="model_text"><?= $car['model'] ?></a><br>

                                <a><?= $car['passengers'] ?> férőhely - <?= $car['transmission'] === 'Manual' ? 'manuális' :  'automata'?></a></a>
                                <?php if($_SESSION['admin'] == 1)?>
                                <div class="margin">
                                    <a href="delete_res.php?res_id=<?= $key  ?>" class="delete_button">Foglalás törlése</a>
                                </div>
                                </div>
                        </div>
                    <?php else: ?> 
                        <div class="card_div">
                            <div>
                                <h3>A foglalt autó törölve lett az adatbázisból!</h3>
                            </div>
                        </div>

                    <?php endif; ?> 
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
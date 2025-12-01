<?php
    session_start();

    if (!isset($_SESSION['user_email'])) {
        header("location: index.php");
        exit();
    }
    if($_SESSION['admin'] != 1)
    {
        header('location: index.php');
        exit();
    }
    $cars = json_decode(file_get_contents('data/cars.json'), true);
    $ids = array_keys($cars);
    $max_id= max($ids);
    $new_car_id = $max_id + 1; 

    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $fuel_type = $_POST['fuel_type'] ?? '';
    $passengers = $_POST['passengers'] ?? '';
    $daily_price_huf = $_POST['daily_price_huf'] ?? '';
    $image_url = $_POST['image'] ?? '';

    if(count($_POST) > 0)
    {
        $errors = [];
        if ( trim($brand) === '' ){
            $errors['brand'] = 'A brandet meg kell adni!';
        }
        if ( trim($model) === '' ){
            $errors['model'] = 'A moldet meg kell adni!';
        }
        if(trim($year) === '')
        {
            $errors['year'] = 'A gyártási év megadása kötelező';
        }else 
        {
            if(!(strlen($year) == 4))
            {
                $errors['year'] = 'A gyártási évnek 4 jegyűnek kell lennie!';
            }else
            {
                if(filter_var($year, FILTER_VALIDATE_INT) === false)
                {
                    
                    $errors['year'] = 'A gyártási évnek egész számnak kell lennie!';
                    
                }else
                {
                    $year = intval($year);
                    if($year < 1)
                    {
                        $errors['year'] = 'A gyártási évnek pozitívnak kell lennie!';
                    }
                }
            }
            
        }
        if(trim($transmission) === '')
        {
            $errors['transmission'] = 'A váltónak a fajtáját meg kell adni!';
        }else if($transmission != 'Automatic' && $transmission != 'Manual')
        {
            $errors['transmission'] = 'A váltónak a fajtája csak manuális, vagy automata lehet!'; 
        }
        if(trim($fuel_type) === '')
        {
            $errors['fuel_type'] = 'Az üzemanyag a fajtáját meg kell adni!';
        }else if($fuel_type != 'Petrol' && $fuel_type != 'Electric' &&  $fuel_type != 'Diesel')
        {
            $errors['fuel_type'] = 'Az üzemanyagnak a fajtája csak benzines, elektromos, vagy dízeles lehet!'; 
        }
        if(trim($passengers) === '')
        {
            $errors['passengers'] = 'A férőhelyek számának megadása kötelező';
        }else 
        {
            if(filter_var($passengers, FILTER_VALIDATE_INT) === false)
            {
                
                $errors['passengers'] = 'A férőhelyek számának egész számnak kell lennie!';
                
            }else
            {
                $age = intval($passengers);
                if($passengers < 1)
                {
                    $errors['passengers'] = 'A férőhelyek számának pozitívnak kell lennie!';
                }

            }
        }
        if(trim($daily_price_huf) === '')
        {
            $errors['daily_price_huf'] = 'A napidíj megadása kötelező';
        }else 
        {
            if(filter_var($daily_price_huf, FILTER_VALIDATE_INT) === false)
            {
                
                $errors['daily_price_huf'] = 'A napidíjnak egész számnak kell lennie!';
                
            }else
            {
                $age = intval($daily_price_huf);
                if($daily_price_huf < 1)
                {
                    $errors['daily_price_huf'] = 'A napidíjnak pozitívnak kell lennie!';
                }

            }
        }
        if(trim($image_url) === '')
        {
            $errors['image_url'] = 'A kocsi képének az URL címének megadása kötelező';
        } else if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            $errors['image_url'] = 'A kocsi képének az URL címének validnak kell lennie!';
        }
        if (count($errors) == 0) {
            
            $new_car = [
                'id' => $new_car_id,
                'brand' => $brand,
                'model' => $model,
                'year' => $year,
                'transmission' => $transmission,
                'fuel_type' => $fuel_type,
                'passengers' => $passengers,
                'daily_price_huf' => $daily_price_huf,
                'image' => $image_url,
            ];
            $cars[] = $new_car;
    
            file_put_contents('data/cars.json', json_encode($cars, JSON_PRETTY_PRINT));
        }
    }
    
    
   


?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szerkesztés</title>
    <link rel="stylesheet" href="styles/caredit.css">
</head>
<body>
    <header class="top-bar">
    <a href="index.php" class="logo">iKarRental</a>
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
        <h1 id="belepes">Új autó hozzáadása<h1>
        <form action="create_car.php?>"  method="POST" novalidate>
            <label for="brand" class="labels_form">Márka:</label><br>
            <input type="text" id="brand" name="brand" value="<?= $brand  ?>">
            <a class="error"><?= $errors['brand'] ?? '' ?></a><br>
            <label for="model" class="labels_form">Model:</label><br>
            <input type="text" id="model" name="model" value="<?= $model?>">
            <a class="error"><?= $errors['model'] ?? '' ?></a><br>
            <label for="year" class="labels_form">Év:</label><br>
            <input type="number" id="year" name="year" value="<?= $year ?>">
            <a class="error"><?= $errors['year'] ?? '' ?></a><br>
            <label for="transmission" class="labels_form">Váltó típusa:</label><br>
            <select id="transmission" name="transmission">
                <option value="Automatic" <?= $transmission === 'Automatic' ? 'selected' : '' ?>>Automata</option>
                <option value="Manual" <?= $transmission  === 'Manual' ? 'selected' : '' ?>>Manuális</option>
            </select>
            <a class="error"><?= $errors['transmission'] ?? '' ?></a><br>
            <label for="fuel_type" class="labels_form">Üzemanyag típusa:</label><br>
            <select id="fuel_type" name="fuel_type">
                <option value="Petrol" <?= $fuel_type === 'Petrol' ? 'selected' : '' ?>>Benzines</option>
                <option value="Electric" <?= $fuel_type=== 'Electric' ? 'selected' : '' ?>>Elektromos</option>
                <option value="Diesel" <?= $fuel_type === 'Diesel' ? 'selected' : '' ?>>Dízel</option>
            </select>
            <a class="error"><?= $errors['fuel_type'] ?? '' ?></a><br>
            <label for="passengers" class="labels_form">Férőhely:</label><br>
            <input type="number" id="passengers" name="passengers" value="<?= $passengers ?>" step="1">
            <a class="error"><?= $errors['passengers'] ?? '' ?></a><br>
            <label for="daily_price_huf" class="labels_form">Napi ára:</label><br>
            <input type="number" id="daily_price_huf" name="daily_price_huf" value="<?= $daily_price_huf ?>" step="100">
            <a class="error"><?= $errors['daily_price_huf'] ?? '' ?></a><br>
            <label for="image" class="labels_form">A kocsi képének a linkje:</label><br>
            <input type="text" id="image" name="image" value="<?= $image_url ?>" step="100">
            <a class="error"><?= $errors['image_url'] ?? '' ?></a><br>
            <div class="information">
                <div class="imgdiv">
                    <img src="<?= $image_url ?>">
                </div>
            </div>
            <input class="yellow_button" type="submit" value="Save">
        </form>
        <a class="error"><?= $errors['valid'] ?? '' ?></a><br>
        <?php if(count($_POST) && count($errors) == 0) : ?>
        <span style='color: green'> Sikeres mentés!</span>
        <?php endif; ?>
    </div>

    
</body>
</html>
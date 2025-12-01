<?php
    session_start();

    if (!isset($_SESSION['user_email'])) {
        header("location: index.php");
        exit();
    }
    if($_SESSION['admin'] != 1 || !isset($_GET['car_id']))
    {
        header('location: index.php');
        exit();
    }
    $car_id = $_GET['car_id'];
    $cars = json_decode(file_get_contents('data/cars.json'), true); 

    $updatedCars = [];
    foreach ($cars as $car) {
        if ($car['id'] != $car_id) {
            $updatedCars[] = $car;
        }
    }
    
    file_put_contents('data/cars.json', json_encode($updatedCars, JSON_PRETTY_PRINT));
    header('Location: index.php');
?>
<?php
    session_start();

    if (!isset($_SESSION['user_email'])) {
        header("location: index.php");
        exit();
    }
    if($_SESSION['admin'] != 1 || !isset($_GET['res_id'])) 
    {
        header('location: index.php');
        exit();
    }
    $res_id = $_GET['res_id'];
    $reserved = json_decode(file_get_contents('data/reserved.json'), true); 

    $reservedUpdate = [];
    foreach ($reserved as $key => $res) {
        if ((string)$key !== (string)$res_id) {

            $reservedUpdate[$key] = $res;
        }else
        {
            $carID = $res['id'];
        }
    }
    
    file_put_contents('data/reserved.json', json_encode($reservedUpdate, JSON_PRETTY_PRINT));
    header("location: index.php");
?>
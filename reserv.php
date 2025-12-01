<?php
    session_start();
    
    $response = [];
    $response['success'] = false;
    $response['message'] = '';
    
    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? '';

    if(strtotime($startdate) > strtotime($enddate))
    {
        $response['message'] = 'Hibás időintervallum!';
        echo json_encode($response);
        exit();
    }

    if (isset($_GET['car_id'])) 
    {
        $car_id = $_GET['car_id'];
        $reservs = json_decode(file_get_contents('data/reserved.json'), true);
        $cars = json_decode(file_get_contents('data/cars.json'), true);
        $keys = array_keys($reservs);
        $max_key = max($keys);
        $new_key = $max_key + 1; 
        $sikerese = true;

        if (isset($_SESSION['user_email'])) {
            if (count($_POST) == 2) {
                foreach ($reservs as $reservation) {
                    if ($reservation['id'] == $car_id) {
                        $reserved_start = strtotime($reservation['start']);
                        $reserved_end = strtotime($reservation['end']);
                        if (!(strtotime($enddate) < $reserved_start || strtotime($startdate) > $reserved_end)) {
                            $sikerese = false;
                            break;
                        }
                    }
                }
            }
            $car = null;
            foreach ($cars as $car_data) {
                if ($car_data['id'] == (int)$car_id) {
                    $car = $car_data;
                    break;
                }

            }
            if ($sikerese) {
                $reservs[$new_key] = [
                    'id' => $car_id,
                    'email' => $_SESSION['user_email'],
                    'start' => $startdate,
                    'end' => $enddate,
                ];
                file_put_contents('data/reserved.json', json_encode($reservs, JSON_PRETTY_PRINT));
                $response['success'] = true;


                if($car != null)
                {
                    
                    $start = date('d', strtotime($startdate));
                    $end = date('d', strtotime($enddate));
                    $days = $end - $start;

                    $response['message'] = 'A(z) ' . $car['brand'] . ' ' . 
                    $car['model'] . ' sikersen lefoglalva ' . $startdate . ' - ' . $enddate . 
                    ' intervallumra. Összesen fizetendő ' . number_format(($days * (int)$car['daily_price_huf']),0,' ', '.') .  ' Ft. A foglalásod státuszát a profilodon követheted nyomon!';

                    

                }
                

            } else {
                if($car != null)
                {
                $response['message'] = 'A(z) ' . $car['brand'] . ' ' . 
                    $car['model'] . ' nem elérhető a megadott ' . $startdate . ' - ' . $enddate . 
                    ' intervallumban. Próbálj megadni egy másik intervallumot, vagy keress egy másik járművet.';
                }
            }
        }
    }

    echo json_encode($response);
?>

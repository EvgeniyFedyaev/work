<?php 
   $host = 'localhost'; 
   $db_name = 'database'; 
   $user = 'user'; 
   $password = 'password'; 

    $connection = mysqli_connect($host, $user, $password, $db_name);

    if (!$connection) 
    {
        echo 'Ну удалось соединиться с базой данных. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
        exit;
    }

    $queryEmails = 'SELECT * FROM `emails`';
    $queryUsers = 'SELECT * FROM `users`';

    $resultEmails = mysqli_query($connection, $queryEmails);
    $resultUsers = mysqli_query($connection, $queryUsers);

    $arValidMail = []; 
    $thisDate = date('Y-m-d');

    while($rowEmails = $resultEmails->fetch_assoc())
    {
        while($rowUsers = $resultUsers->fetch_assoc())
        {
            $daysLeft = (strtotime($rowUsers['validts']) - strtotime($thisDate)) / 86400;
            if ($daysLeft <= 3)
            {
                if ($rowEmails['checked'] == '1' && $rowEmails['valid'] == '1')
                {
                    if ($rowEmails['email'] == $rowUsers['email'] && $rowUsers['confirmed'] == '1')
                    {
                        mail($rowUsers['email'], 'Subscription', "$rowUsers['username'], your subscription is expiring soon");
                        break;
                    }
                }
                elseif ($rowEmails['checked'] == '0')
                {
                    $check_email = check_email($rowEmails['email']);
                    $sqlInsert = mysqli_query($connection, "INSERT INTO `emails` (`checked`, `valid`) VALUES ('1', '$check_email')");
                    if ($check_email == '1' && $rowUsers['confirmed'] == '1')
                    {
                        mail($rowUsers['email'], 'Subscription', "$rowUsers['username'], your subscription is expiring soon");
                        break;
                    }
                }
            }
        }
    }
    
    mysqli_close($connection);
?>

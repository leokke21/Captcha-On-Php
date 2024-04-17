<?php 
session_start();
if(isset($_SESSION['user_id'])){
    if(isset($_COOKIE['username'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
    
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style/style.css">
    <title>хОЧУ ПООБЩАТЬСЯ - Просмотр ПРОФИЛЯ</title>
</head>
<body>
    <h3>XОЧУ ПООБЩАТЬСЯ - Просмотр ПРОФИЛЯ</h3>
   <?php
      require_once ('appvars.php');
      require_once ('connectionvars.php');
      if(!isset($_SESSION['user_id'])){
        echo '<p class="login">Пожалуйста, <a href="login.php">войдите</a> для доступа к этой странице.</p>';
        exit();
    }
    else{
        echo('<p class="login">Вы вошли как ' . $_SESSION['username'] . '.<a href="logout.php">Выйти.</a></p>');
        
        echo '<p><a href="index.php">На главную</a></p>';
    }

    if (!isset($_GET['user_id'])){
        $user_id = $_SESSION['user_id'];

        $query2 = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = :user_id";
    }
    else{
        $user_id = $_GET['user_id'];

        $query2 = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = :user_id";

    }
    $result2 = $pdo->PDO->prepare($query2);

    $result2->bindParam(':user_id', $user_id);
    $result2->execute();
    $count2=$result2->rowCount();
    if($count2 == 1) {
        $row2 = $result2->fetch();
        echo '<table>';
        if(!empty($row2['username'])) {
            echo '<tr><td class = "label">Логин: </td><td>'.$row2['username'].'</td></tr>';
        }
        if(!empty($row2['first_name'])) {
            echo '<tr><td class = "label">Имя: </td><td>'.$row2['first_name'].'</td></tr>';
        }
        if(!empty($row2['last_name'])) {
            echo '<tr><td class = "label">Фамилия: </td><td>'.$row2['last_name'].'</td></tr>';
        }
        if(!empty($row2['gender'])) {
            echo '<tr><td class = "label">Пол: </td><td>';
            if($row2['gender'] == 'M') {
                echo 'Мужской';
            }
            else if($row2['gender'] == 'F') {
                echo 'Женский';
            }
            else{
                echo '?';
            }
            echo '</td></tr>';
        }
        if(!empty($row2['birthdate'])) {
            if (!isset($_GET['user_id']) || ($_SESSION[$user_id] == $_GET['user_id'])) {
                echo '<tr><td class = "label">Дата рождения: </td><td>'.$row2['birthdate'].'</td></tr>';
            }
            else {
                list($year, $month, $day) = explode('-', $row2['birthdate']);
                echo '<tr><td class = "label">Год рождения: </td><td>'.$year.'</td></tr>';

            }
        }
        if(!empty($row2['city']) || !empty($row2['state'])) {
            echo '<tr><td class = "label">Область: </td><td>'.$row2['city'].', '.$row2['state'].'</td></tr>';
        }
        if(!empty($row2['picture'])) {
            echo '<tr><td class = "label">Фотография: </td><td><img src = "assets/'.MM_UPLOADPATH.$row2['picture'].'"></td></tr>';
        }
        echo '</table>';
        if(!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
            echo '<p>Хотели бы вы <a href = "editprofile.php">отредактировать профиль</a>?</p>';
        }
        $result3 = NULL;
    }
    else{
        
        echo '<p class = "error">Возникла ошибка с доступом к вашему профилю</p>';
    }
?>

</body>
</html>
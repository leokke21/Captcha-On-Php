<?php
session_start();
if(isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style/style.css">
    <title>Document</title>
</head>
<body>
    <h3>Хочу с тобой пообщаться</h3>
    <?php
    require_once('connectionvars.php');
    require_once('appvars.php');
    // nav
    if(isset($_SESSION['username'])) {
        ?>
        <a href="viewprofile.php">🌈 Просмотреть профиль</a> <br>
        <a href="editprofile.php">😱 Редактировать профиль</a> <br>
        <a href="logout.php">🤢 Выйти <?= $_SESSION['username'] ?> </a> <br>
        <?php
    }
    else {
        ?>
        &#10084; <a href="login.php">Авторизоваться </a> <br>
        &#10084; <a href="signup.php">Зарегестрироваться </a> <br>
        <?php
    }
    $zapros = "Select user_id, first_name, picture FROM mismatch_user Where first_name IS NOT NULL ORDER BY join_date DESC LIMIT 5";
    $result =$pdo->query($zapros);
    echo'<h4>Последние 5 участников: </h4>';
    echo '<table class="firstTable">';

    while($res = $result ->fetch(PDO::FETCH_BOTH)) {
        if (is_file("assets/".MM_UPLOADPATH .$res['picture']) && filesize("assets/".MM_UPLOADPATH.$res['picture']) >0) {
            echo '<tr><td><img src="assets/'.MM_UPLOADPATH.$res['picture'].'" alt="'.$res['first_name'].'" /></td>';

        }
        else {
            echo '<tr><td><img src="assets/'.MM_UPLOADPATH.'nopic.jpg" alt="'.$res['first_name'].'" /></td>';
        }
        if(isset($_SESSION['user_id'])) {
            echo '<td><a href="viewprofile.php?user_id='.$res['user_id'].'">'.$res['first_name'].'</a></td></tr>';
        }
        else {
            echo '<td>'.$res['first_name'].'</td></tr>';
        }
    }
    echo '</table>';
    $result = NULL;
    ?>
</body>
</html>
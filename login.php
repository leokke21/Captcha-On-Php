<?php
require_once('connectionvars.php');
session_start();
$error_msg="";

if(!isset($_SESSION['uwer_id'])) {
    if(isset($_POST['submit'])) {
        $user_username = trim($_POST['username']);
        $user_password = trim($_POST['password']);

        if(!empty($user_username) && !empty($user_password)) {
            $query1 = "SELECT user_id, username FROM mismatch_user WHERE username = ? AND password = sha1(?)";
            $result2 = $pdo ->query($query1,[$user_username, $user_password ]);
            $count2 = $result2->rowCount();
            if($count2==1) {
                $row = $result2->fetch();
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                setcookie('user_id', $row['user_id'], time()+(60*60*24*30));
                setcookie('username', $row['username'], time()+(60*60*24*30));
                $home_url="index.php";
                header('Location: '.$home_url);
            }
            else {
                $error_msg = "Извините, вы должны ввести действительное  имя пользователя или пароль, чтобы войти в систему.";
            }
        }
        else {
            $error_msg = "вы должны ввести свои имя пользователя или пароль, чтобы войти в систему.";

        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ХОЧУ С ТОБОЙ ПООБЩАТЬСЯ - АВТОРИЗАЦИЯ</title>
    <link rel="stylesheet" href="assets/style/style.css">
</head>
<body>
    <h3>ХОЧУ С ТОБОЙ ПООБЩАТЬСЯ - АВТОРИЗАЦИЯ</h3>
    <?php
        if(empty($_SESSION['user_id'])) {
            echo '<p class="error">'.$error_msg.'</p>';
    ?>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <fieldset>
                <legend>Авторизация</legend>
                <label for="username">Логин:</label>
                <input type="text" name="username" value="<?php if(!empty($user_username)) echo $user_username; ?>"> <br>
                <label for="password"> Пароль:</label>
                <input type="password" name="password" value="<?php if(!empty($user_password)) echo $user_password; ?>"> <br>
            </fieldset>
            <input type="submit" value = "Авторизоваться" name='submit'>
    </form>
    <?php
        }
        else {
            echo('<p class = "login">Вы вошли как '.$_SESSION['username'] .'</p>');
            echo('<p><a href="logout.php">Выйти</a></p>');
        }
    ?>
</body>
</html>
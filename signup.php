<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h3>Хочу с тобой пообщаться - РЕГИСТРАЦИЯ</h3>

<?php
     function  create_image()
     {
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);   
        imagefilledrectangle($image,0,0,200,50,$background_color);
        $line_color = imagecolorallocate($image, 64,64,64);
        for($i=0;$i<10;$i++) {
            imageline($image,0,rand()%50,200,rand()%50,$line_color);
        }
        $pixel_color = imagecolorallocate($image, 0,0,255);
        for($i=0;$i<1000;$i++) {
            imagesetpixel($image,rand()%200,rand()%50,$pixel_color);
        }
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $len = strlen($letters);
        $letter = $letters[rand(0, $len-1)];
        $text_color = imagecolorallocate($image, 0,0,0);

        for ($i = 0; $i< 6; $i++) {
            $letter = $letters[rand(0, $len-1)];
            imagestring($image, 5,  5+($i*30), 20, $letter, $text_color);
            $word.=$letter;
        }
        $_SESSION['captcha_string'] = $word;

        $word.=$letter;
        $_SESSION['captcha_string'] = $word;
        imagepng($image, "image.png");
     }
     function display()
     {
         ?>
          <div style="text-align:center;">
             <h3>ВВЕДИТЕ ТЕКСТ, ИЗОБРАЖЕННЫЙ НА КАРТИНКЕ</h3>
             <b>Это проверка на то, робот вы или живой человек </b>
              <div style="display:block;margin-bottom:20px;margin-top:20px;">
                 <img src="image.png">
             </div> //div1 конец

             <form action=" <?php echo $_SERVER['PHP_SELF']; ?>" method="POST" / >
               <input type="text" name="input"/>
               <input type="hidden" name="flag" value="1"/>
               <input type="submit" value="Отправить" name="submit"/>
             </form>

             <form action=" <?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
               <input type="submit" value="Обновить страницу">
	         </form>
          </div> //div2 конец
      <?php
     }
?>

<?php
require_once('connectionvars.php');
require_once('appvars.php');
    
    
if (isset($_POST['submit'])) {
    $error_msg= "";
    $username = trim($_POST['username']);
    $password1 = trim($_POST['password1']);
    $password2 = trim($_POST['password2']);
if(strlen($password1) < 5) {
    $error_msg .= 'Пароль должен быть не менее 5 символов</br>';

}
if(!preg_match('/[A-Z]+/', $password1)){
    $error_msg .= 'В пароле должно быть не менее 1 заглавной буквы</br>';
}
if(!preg_match('/[0-9]+/', $password1)){
    $error_msg .= 'Пароль должен содержать минимум 1 цифру</br>';
}
if($error_msg != "") {
    echo "<p class='error' >{$error_msg}</p>";
    $error_msg = "";
}
else {


if (!empty($username) && !empty ($password1) && !empty ($password2) && ($password1 == $password2)) {
    $zapros1 = "SELECT * FROM mismatch_user WHERE username = ?";
    $result1 = $pdo->query($zapros1,[$username]);
    $kol = $result1->rowCount();
    if($kol==0) {
        $secret = sha1($password1);
        $data = date("Y-m-d");
        $ins ="INSERT INTO mismatch_user (username,password, join_date, picture) VALUES (?, ?, ?, 'nopic.jpg')";
        $inser = $pdo->query($ins,[$username, $secret, $data]);
        echo '<p>Ваш новый аккаунт был успешно создан. Теперь вы можете <a href="login.php">авторизоваться</a></p>';
        $inser = NULL;
        exit();
    }
    else {
        echo '<p class="error">Пользователь с таким именем уже существует</p>';
        $username='';
    }
}
else {
    echo '<p class="error">Вы должны заполнить все поля</p>';
}
    }
}
?>
<p>Пожалуйста, введите ваше имя пользователя и пароль, чтобы войти в блог.</p>
<form method='post' action="<?php echo $_SERVER['PHP_SELF']?>">
    <fieldset>
        <legend>Информация о регистрации</legend>
        <label for="username">Логин:</label>
        <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username ?>"> <br/>
        <label for="password1">Пароль:</label>
        <input type="password" id="password1" name="password1" value="<?php if (!empty($password1)) echo $password1 ?>"> <br/>
        <label for="password2">Повторите пароль:</label>
        <input type="password" id="password2" name="password2" value="<?php if (!empty($password2)) echo $password2 ?> "> 
        <label for="">Введите капчу</label>
        <?php
                create_image();
                display();
                
        ?>


    </fieldset>

    <input type="submit" value="Зарегестрироваться" name="submit">
</form>
</body>
</html>
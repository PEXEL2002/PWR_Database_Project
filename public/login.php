<!DOCTYPE html>
<?php 
require_once __DIR__ .'/../src/models/user_model.php';
session_start(); ?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/log.css">
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="css/menu.css">
    <title>Slalom - Logowanie</title>
</head>
<body>
    <?php
        include __DIR__ . '/menu.php';
    ?>
    <main>
      <section>
        <div id="conteiner">
          <div class="login-box">
            <p class="title">Logowanie</p>
              <div id="login-inputs">
                <?php
                  if(isset($_POST['email']) && isset($_POST['password'])
                  && !empty($_POST['email']) && !empty($_POST['password'])){
                  require_once __DIR__ .'/../src/models/user_model.php';
                  $_SESSION['user'] = new User();
                  $test = $_SESSION['user']->login($_POST['email'], $_POST['password']);
                  if($test == false){
                      echo '<p style="color:red">Niepoprawne dane logowania.</p>';
                  }else{
                      header("Location: main.php");
                  }
              }
                ?>
                <form action="login.php" method="post">
                  <div class="txtbox">
                    <input type="text" placeholder="E-Mail" name="email" value="">
                  </div>
                  <div class="txtbox">
                    <input type="password" placeholder="Hasło" name="password" value="">
                  </div>
                  <input class="bnt" type="submit" name="" value="Zaloguj">
                  </div>
                  <div class="register-link">
                    <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
                  </div>
              </form>
          </div>
        </section>
        <div class="vertical-line"></div>
        <img src="assets/logo.png" alt="logo.png">
    </main>
</body>
</html>
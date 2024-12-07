<!DOCTYPE html>
<?php session_start(); ?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/log.css">
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="css/menu.css">
    <title>Slalom - Rejestracja</title>
</head>
<body>
    <?php
        include __DIR__ . '/menu.php';
    ?>
    <main>
        <img src="assets/logo.png" alt="logo.png">
        <div class="vertical-line"></div>
        <section>
          <div id="conteiner">
            <div class="login-box">
                <form action="register.php" method="post">
                        <p class="title">Rejestracja</p>
<?php
if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['password1']) && isset($_POST['password2'])
    && !empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['email']) && !empty($_POST['password1']) && !empty($_POST['password2'])
){
    if($_POST['password1'] != $_POST['password2']){
        echo '<p style="color:red">Hasła nie są takie same.</p>';
    }else{
        require_once __DIR__ .'/../src/models/user_model.php';
        $user = new User();
        $user->register($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['password1']);
        if($user == false){
            echo '<p class="error">Nie udało się zarejestrować. Konto o takim meilu już istnieje.</p>';
        }else{
            header("Location: login.php");
        }
    }
}
?>
                        <div id="login-inputs">
                          <div class="txtbox">
                            <input type="text" placeholder="Imię" name="name" value="">
                          </div>
                          <div class="txtbox">
                            <input type="text" placeholder="Nazwisko" name="surname" value="">
                          </div>
                          <div class="txtbox">
                              <input type="password" placeholder="Hasło" name="password1" value="">
                          </div>
                          <div class="txtbox">
                            <input type="password" placeholder="Powtórz Hasło" name="password2" value="">
                          </div>
                          <div class="txtbox">
                              <input type="email" placeholder="E-mail" name="email" value="">
                          </div>
                          <input class="bnt" type="submit" name="" value="Rejestruj">
                        </div>
                        <div class="register-link">
                          <p>Masz już konto? <a href="login.php">Kliknij Tutaj</a></p>
                        </div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
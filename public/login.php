<!DOCTYPE html>
<html lang="en">
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
                <div class="txtbox">
                  <input type="text" placeholder="Nazwa użytkownika" name="" value="">
                </div>
                <div class="txtbox">
                  <input type="password" placeholder="Hasło" name="" value="">
                </div>
                <input class="bnt" type="button" name="" value="Zaloguj">
              </div>
              <div class="register-link">
                <a href="#">Nie pamiętasz hasła? (Będzie jak zrobie resztę)</a>
                <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
              </div>
          </div>
        </section>
        <div class="vertical-line"></div>
        <img src="assets/logo.png" alt="logo.png">
    </main>
</body>
</html>
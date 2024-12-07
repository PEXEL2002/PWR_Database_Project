<?php
require_once __DIR__ . '/../src/models/user_model.php';
session_start();

$messages = [
    'name' => '',
    'surname' => '',
    'email' => '',
    'password' => ''
];

$infoUser = $_SESSION['user']->getInfo();
// Sprawdzanie i aktualizacja imienia
if (isset($_POST['name']) && !empty($_POST['name'])) {
    if ($_POST['name'] != $infoUser['name']) {
        $_SESSION['user']->updateName($_POST['name']);
        $infoUser['name'] = $_POST['name']; // Aktualizacja zmiennej
        $messages['name'] = '<p class="success">Zmieniono imię</p>';
    }
}
// Sprawdzanie i aktualizacja nazwiska
if (isset($_POST['surname']) && !empty($_POST['surname'])) {
    if ($_POST['surname'] != $infoUser['surname']) {
        $_SESSION['user']->updateSurname($_POST['surname']);
        $infoUser['surname'] = $_POST['surname']; // Aktualizacja zmiennej
        $messages['surname'] = '<p class="success">Zmieniono nazwisko</p>';
    }
}
// Sprawdzanie i aktualizacja emaila
if (isset($_POST['email']) && !empty($_POST['email'])) {
    if ($_POST['email'] != $infoUser['email']) {
        $_SESSION['user']->updateEmail($_POST['email']);
        $infoUser['email'] = $_POST['email']; // Aktualizacja zmiennej
        $messages['email'] = '<p class="success">Zmieniono email</p>';
    }
}
// Zmiana hasła
if (isset($_POST['oldPassword']) && isset($_POST['newPassword']) && isset($_POST['newPassword2']) && 
    !empty($_POST['oldPassword']) && !empty($_POST['newPassword']) && !empty($_POST['newPassword2'])) {
    if (password_verify($_POST['oldPassword'], $_SESSION['user']->getPassword())) {
        if ($_POST['newPassword'] == $_POST['newPassword2']) {
            $_SESSION['user']->updatePassword($_POST['newPassword']);
            $messages['password'] = '<p class="success">Zmieniono hasło</p>';
        } else {
            $messages['password'] = '<p class="error">Nowe hasła nie są takie same</p>';
        }
    } else {
        $messages['password'] = '<p class="error">Stare hasło jest niepoprawne</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/userInfo.css">
</head>
<body>
    <?php
        include __DIR__ . '/menu.php';
    ?>
    <main>
        <section id='about_user'>
            <form action="userInfo.php" method="post">
                <!-- Sekcja "O mnie" -->
                <h3 class="section-title">O mnie:</h3>

                <label for="name">Imię:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($infoUser['name']); ?>">
                <?php echo $messages['name']; ?>

                <label for="surname">Nazwisko:</label>
                <input type="text" name="surname" id="surname" value="<?php echo htmlspecialchars($infoUser['surname']); ?>">
                <?php echo $messages['surname']; ?>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($infoUser['email']); ?>">
                <?php echo $messages['email']; ?>

                <!-- Przycisk zapisu zmian -->
                <input type="submit" value="Zapisz zmiany">

                <!-- Sekcja "Zmiana hasła" -->
                <h3 class="section-title">Zmiana hasła:</h3>

                <label for="oldPassword">Stare hasło:</label>
                <input type="password" name="oldPassword" id="oldPassword">

                <label for="newPassword">Nowe hasło:</label>
                <input type="password" name="newPassword" id="newPassword">

                <label for="newPassword2">Powtórz nowe hasło:</label>
                <input type="password" name="newPassword2" id="newPassword2">
                <?php echo $messages['password']; ?>

                <!-- Przycisk zmiany hasła -->
                <input type="submit" value="Zmień hasło">
            </form>
        </section>
        <div class="line"></div>
        <section id='history-order'>
            <h1>Historia zamówień </h1>
            <div class='order-items'>
                do zrobienia po panelu admina
            </div>
        </section>

    </main>
</body>
</html>

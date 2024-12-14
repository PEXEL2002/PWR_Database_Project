<nav class="menu">
    <a href="main.php">
        <div id="logo">
            <img src="/public/assets/logo.png" class="logo" alt="Logo">
            <p>Slalom</p>
        </div>
    </a>
    <div id="content">
        <img src="/public/assets/icons/hamburger-menu.png" class="icon" alt="">
    </div>
    <aside id="side-menu" class>
        <div id="close">
            <img src="/public/assets/icons/x.png" class="icon" alt="Close">
        </div>
        <ul>

            <?php
                if (isset($_SESSION['user'])) {
                    if($_SESSION['user']->getRole() == 1) {
                        echo "<li><a href='adminPanel.php'>Panel administratora</a></li>";
                    }else{
                        echo '<li><a href="#">Nasz Sprzęt</a></li>';
                        echo '<li><a href="#">Seris</a></li>';
                        echo '<li><a href="#">Kontakt</a></li>';
                        echo "<li><a href='userInfo.php'>Informacje o koncie</a></li>";
                    }
                    echo "<li><a href='logout.php'>Wyloguj</a></li>";
                } else {
                    echo '<li><a href="#">Nasz Sprzęt</a></li>';
                    echo '<li><a href="#">Seris</a></li>';
                    echo '<li><a href="#">Kontakt</a></li>';
                    echo "<li><a href='login.php'>Zaloguj</a></li>";
                }
            ?>
        </ul>
    </aside>  
</nav>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sideMenu = document.getElementById("side-menu");
        const hamburgerMenu = document.querySelector("#content .icon"); // Ikona hamburgera
        const closeButton = document.querySelector("#close .icon"); // Ikona zamykania

        // Obsługa kliknięcia na ikonę hamburgera
        hamburgerMenu.addEventListener("click", () => {
            sideMenu.classList.add("active"); // Dodaj klasę "active" do <aside>
        });

        // Obsługa kliknięcia na ikonę zamykania
        closeButton.addEventListener("click", () => {
            sideMenu.classList.remove("active"); // Usuń klasę "active" z <aside>
        });
    });
</script>
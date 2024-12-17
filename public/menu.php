<nav class="menu">
    <a href="main.php">
        <div id="logo">
            <img src="/public/assets/logo.png" class="logo" alt="Logo">
            <p>Slalom</p>
        </div>
    </a>
    <div id="content">
        <img src="/public/assets/icons/hamburger-menu.png" class="icon" alt="Menu">
    </div>
    <aside id="side-menu">
        <div id="close">
            <img src="/public/assets/icons/x.png" class="icon" alt="Zamknij">
        </div>
        <ul>
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']->getRole() == 1): ?>
                    <li><a href="adminPanel.php">Panel administratora</a></li>
                <?php else: ?>
                    <li><a href="equipment.php">Nasz Sprzęt</a></li>
                    <li><a href="servises.php">Serwis</a></li>
                    <li><a href="conctact.php">Kontakt</a></li>
                    <li><a href="userInfo.php">Informacje o koncie</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Wyloguj</a></li>
            <?php else: ?>
                <li><a href="equipment.php">Nasz Sprzęt</a></li>
                <li><a href="servises.php">Serwis</a></li>
                <li><a href="conctact.php">Kontakt</a></li>
                <li><a href="login.php">Zaloguj</a></li>
            <?php endif; ?>
        </ul>
    </aside>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sideMenu = document.getElementById("side-menu");
        const hamburgerMenu = document.querySelector("#content .icon");
        const closeButton = document.querySelector("#close .icon");

        // Show side menu
        hamburgerMenu.addEventListener("click", () => {
            sideMenu.classList.add("active");
        });

        // Close side menu
        closeButton.addEventListener("click", () => {
            sideMenu.classList.remove("active");
        });
    });
</script>

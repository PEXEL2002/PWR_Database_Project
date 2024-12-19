<?php
    require_once __DIR__ . '/../src/models/news_model.php';
    require_once __DIR__ . '/../src/models/user_model.php';
    session_start();

    $newsModel = new News();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_news'])) {
        $newsModel->deleteNews($_POST['news_id']);
        $_SESSION['messages'][] = "News został usunięty.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_news'])) {
        $title = $_POST['N_title'];
        $content = $_POST['N_content'];
        $creator = $_SESSION['user']->getId();
        $photoPath = '';

        if (!empty($_FILES['N_photo']['name'])) {
            $photoName = basename($_FILES['N_photo']['name']);
            $photoPath = "assets/newsPhoto/" . $photoName;
            move_uploaded_file($_FILES['N_photo']['tmp_name'], __DIR__ . "/" . $photoPath);
        }

        $newsModel->addNews($title, $content, $photoPath, $creator);
        $_SESSION['messages'][] = "News został dodany.";
    }

    $newsList = $newsModel->getNews();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktualności</title>
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>

<main>
    <?php if (isset($_SESSION['messages'])): ?>
        <div class="messages">
            <?php foreach ($_SESSION['messages'] as $message): ?>
                <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user']) && $_SESSION['user']->getRole() == 1): ?>
        <section id="add-news-section">
            <h2>Dodaj Nowy News</h2>
            <form method="post" enctype="multipart/form-data">
                <label for="N_title">Tytuł:</label>
                <input type="text" name="N_title" required>

                <label for="N_content">Treść:</label>
                <textarea name="N_content" rows="4" required></textarea>

                <label for="N_photo">Zdjęcie (opcjonalne):</label>
                <input type="file" name="N_photo" accept="image/*">

                <button type="submit" name="add_news">Dodaj News</button>
            </form>
        </section>
    <?php endif; ?>

    <section id="news-section">
        <?php foreach ($newsList as $news): ?>
            <div class="news_block">
                <h1><?= htmlspecialchars($news['N_title'], ENT_QUOTES, 'UTF-8') ?></h1>
                <h2>Autor: <?= htmlspecialchars($news['U_name'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p><?= nl2br(htmlspecialchars($news['N_content'], ENT_QUOTES, 'UTF-8')) ?></p>
                <?php if (!empty($news['N_photo'])): ?>
                    <img src="<?= htmlspecialchars($news['N_photo'], ENT_QUOTES, 'UTF-8') ?>" alt="Zdjęcie Newsa">
                <?php endif; ?>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']->getRole() == 1): ?>
                    <form method="post" class="delete-form">
                        <input type="hidden" name="news_id" value="<?= $news['N_id'] ?>">
                        <button type="submit" name="delete_news" class="delete-button">Usuń</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>
</main>

</body>
</html>

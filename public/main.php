<?php
    require_once __DIR__ . '/../src/models/news_model.php';

    $newsModel = new News();
    $newsList = $newsModel->getNews();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/menu.css">
</head>
<body>
    <div id="menu">
    </div>
    <main>
        <?php
            foreach ($newsList as $news) {
                echo "<div class = 'news_blok'>";
                echo "<h1>{$news['N_title']}</h1>";
                echo "<h2>{$news['U_name']}</h2>";
                echo "<p>{$news['N_content']}</p>";
                echo "<img src='{$news['N_image']}' alt=''>";
                echo "</div>";
            }
        ?>
    </main>
</body>
</html>
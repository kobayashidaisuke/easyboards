<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $option; ?>
    <link rel="stylesheet" href="../stylesheets/css/app.css">
    <link rel="icon" href="../gallery/easyboard.svg">
    <title><?= $title; ?></title>
</head>

<body>
    <header class="navbar shadow-sm p-3 mb-5 bg-white" id="top">
        <a href="index.php" class="text-decoration-none">
            <h1 class="h2 text-dark">掲示板</h1>
        </a>
    </header>
    <div class="container">
        <?php include $content; ?>
    </div>
    <footer class="footer bg-dark text-light p-4 mt-4">
        <div class="container text-center">
            <p>Copyright&nbsp;&copy;&nbsp;2021&nbsp;DaisukeKobayashi&nbsp;all&nbsp;right&nbsp;reserved.</p>
        </div>
    </footer>
</body>

</html>

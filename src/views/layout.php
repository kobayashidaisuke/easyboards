<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name=”description” content=”どこでも誰とでもコミュニケーションが取れる掲示板です。利用方法は掲示板に表示されています。自身の発言には責任を持ちましょう。”>
    <?= $option; ?>
    <link rel="stylesheet" href="../stylesheets/css/app.css">
    <link rel="icon" href=".https://raw.githubusercontent.com/kobayashidaisuke/easyboards/bce3d290cd5643d54c0a6dafb47d5c634f2999c7/src/gallery/easyboard.svg">
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
            <p>Copyright&nbsp;&copy;&nbsp;2021&nbsp;DaisukeKobayashi&nbsp;All&nbsp;rights&nbsp;reserved.</p>
        </div>
    </footer>
</body>

</html>

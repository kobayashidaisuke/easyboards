<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name=”description” content=”どこでも誰とでもコミュニケーションが取れる掲示板です。利用方法は掲示板に表示されています。自身の発言には責任を持ちましょう。”>
    <?= $option; ?>
    <link rel="stylesheet" href="../stylesheets/css/app.css">
    <link rel="icon" href="https://user-images.githubusercontent.com/62587652/109342535-5aa05900-78af-11eb-8f7e-1a37e6985d1c.png">
    <title><?= $title; ?>&nbsp;|&nbsp;コミュコミュ！&nbsp;~コロナ禍で失われたコミュニケーションを取り戻そう~</title>
</head>

<body>
    <header class="navbar shadow-sm p-3 bg-white" id="top">
        <a href="index.php" class="text-decoration-none">
            <h1 class="h2 text-dark fw-bold">コミュコミュ！</h1>
        </a>
    </header>
    <div class="container mt-5">
        <?php include $content; ?>
    </div>
    <footer class="footer bg-dark text-light p-4 mt-4">
        <div class="container text-center">
            <p>Copyright&nbsp;&copy;&nbsp;2021&nbsp;DaisukeKobayashi&nbsp;All&nbsp;rights&nbsp;reserved.</p>
        </div>
    </footer>
</body>

</html>

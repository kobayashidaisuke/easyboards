    <?php if (isset($errors)) : ?>
        <section>
            <ul class="text-danger">
                <?php foreach ($errors as $error) : ?>
                    <li>
                        <?= $error; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
    <form method="post" action="newThread.php" class="mb-4">
        <input name="chkno" type="hidden" value="<?= h($chkno); ?>">
        <input name="token" type="hidden" value="<?= h($token); ?>">
        <div class="form-group mb-4">
            <label for="title">タイトル</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= h($thread['title']); ?>">
        </div>
        <div class="form-group mb-4">
            <label for="summary">詳細</label>
            <textarea name="summary" id="summary" class="form-control"><?= h($thread['summary']); ?></textarea>
        </div>
        <input type="submit" name="submit" class="btn btn-primary" value="作成">
    </form>
    <a href="top.php" class="btn btn-secondary text-decoration-none">スレッド選択ページに戻る</a>

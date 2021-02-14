<a href="newThread.php" class="btn btn-primary text-decoration-none mb-4">スレッドを新規作成する</a>
<h2 class="h3 mb-4">スレッド選択</h2>
<?php if (count($selectThreads)) : ?>
<p class="text-danger">見たいスレッドをクリックしてください</p>
    <?php foreach ($selectThreads as $thread) : ?>
        <section class="card shadow-sm mb-4">
            <div class="card-body">
                <a href="thread.php?id=<?= h($thread['id']); ?>" class="mb-3 card-title text-dark text-decoration-none">
                    <h3 class="h4 mb-4"><?= h($thread['title']); ?></h3>
                    <p>詳細：&nbsp;<?= h($thread['summary']); ?></p>
                    <p>作成日時：&nbsp;<?= h($thread['created_at']); ?></p>
                </a>
            </div>
        </section>
    <?php endforeach; ?>
<?php else : ?>
    <p class="text-danger">スレッドが作成されていません。上記のボタンからスレッドを作成してください。</p>
<?php endif; ?>

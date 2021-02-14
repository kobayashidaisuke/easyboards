        <?php if (isset($errors)) : ?>
            <section>
                <ul class="text-danger">
                    <?php foreach ($errors as $error) : ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>
        <?php if (strlen($message)) : ?>
            <section>
                <p><?= $message; ?></p>
            </section>
        <?php endif; ?>
        <main>
            <h3 class="h4 text-dark mb-4">初めての方はこちら</h3>
            <form action="signup.php" method="post">
                <input name="chkno" type="hidden" value="<?= h($chkno); ?>">
                <input name="token" type="hidden" value="<?= h($token); ?>">
                <div class="form-group mb-4">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= h($signup['email']); ?>">
                </div>
                <div class="form-group mb-4">
                    <label for="email-2">間違い防止のため、2度入力してください</label>
                    <input type="email" name="email-2" id="email-2" class="form-control" value="<?= h($signup['email-2']); ?>">
                </div>
                <div class="form-group mb-4">
                    <label for="nickname">ニックネーム</label>
                    <input type="text" name="nickname" id="nickname" class="form-control" value="<?= h($signup['nickname']); ?>">
                </div>
                <div class="form-group mb-4">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                </div>
                <input type="submit" class="btn btn-primary mb-4 text-light" value="登録">
                <p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
            </form>
        </main>

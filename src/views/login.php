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
        <h3 class="h4 text-dark mb-4">ログイン</h3>
        <form action="login.php" method="post">
            <input name="chkno" type="hidden" value="<?= h($chkno); ?>">
            <input name="token" type="hidden" value="<?= h($token); ?>">
            <div class="form-group mb-4">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" inputmode="email" value="<?= h($login['email']); ?>">
            </div>
            <div class="form-group mb-4">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" inputmode="search">
            </div>
            <input type="submit" class="btn btn-primary mb-4 text-light" value="ログイン"></input>
        </form>
    </main>
    <a href="signup.php" class="btn btn-secondary text-decoration-none">初めての方はこちら</a>

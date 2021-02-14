    <div class="row">
        <div class="col-md-2 bg-light mb-5">
            <div class="mb-4">
                <h3 class="h4">使い方</h3>
                <ol>
                    <li class="mb-2">任意の名前、コメント、パスワードを入力して新規投稿のボタンを押してください。パスワードは削除、編集をする際に必要になります。</li>
                    <li class="mb-2">投稿を削除する場合、削除するIDと設定したパスワードを入力して削除ボタンを押してください。</li>
                    <li class="mb-2">投稿を編集する場合、編集するIDと設定したパスワードを入力して編集ボタンを押してください。変更前のデータが新規投稿フォームに反映されます。任意のデータを入力し、新規投稿ボタンを押すと内容が変更されます。</li>
                </ol>
            </div>
            <div>
                <h3 class="h4">注意</h3>
                <ol>
                    <li class="mb-2">掲示板の利用は自己責任です。自身の投稿には責任を持ちましょう。</li>
                    <li class="mb-2">読み手を意識した書き込みをお願いします。</li>
                    <li class="mb-2">トラブル防止のため、個人情報や誹謗中傷は控えてください。</li>
                    <li class="mb-2">この掲示板は匿名ではありません。問題のある投稿をした場合は管理人が投稿の削除、登録情報の削除またはその両方を実行します。</li>
                </ol>
            </div>
        </div>
        <div class="col-md-7 col-sm-12">
            <?php if (isset($errors)) : ?>
                <section>
                    <ul class="text-danger">
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>
            <h3 class="h4 mb-4">ようこそ&nbsp;<?= h($_SESSION["NICKNAME"]); ?>&nbsp;さん</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="h3 card-title mb-4">タイトル：&nbsp;<?= h($title); ?></h2>
                    <p class="card-subtitle mb-2">詳細：&nbsp;<?= h($summary); ?></p>
                    <p class="">作成日時：&nbsp;<?= h($created_at); ?></p>
                </div>
            </div>
            <?php if (isset($submissionForContents)) : ?>
                <?php foreach ($submissionForContents as $content) : ?>
                    <section class="mb-4">
                        <p>
                            <?= h($content["id"]); ?>&nbsp;:&nbsp;<?= h($content["name"]); ?>&nbsp;<?= h($content["created_at"]); ?>
                        </p>
                        <p class="h4"><?= nl2br(h($content["comment"]), false); ?></p>
                        <?php if ($content["extension"] === "mp4") : ?>
                            <div>
                                <video src="<?= h($content['filepass']); ?>" width="426" height="240" preload="none" controls></video>
                            </div>
                        <?php elseif ($content["extension"] === "jpeg" || $content["extension"] === "png" || $content["extension"] === "gif") : ?>
                            <div>
                                <img src="<?= h($content['filepass']); ?>" width="426" height="240">
                            </div>
                        <?php endif; ?>
                    </section>
                    <hr>
                <?php endforeach; ?>
            <?php endif; ?>
            <form method="POST" action="thread.php" enctype="multipart/form-data" class="bg-light p-4 mt-4">
                <input name="chkno" type="hidden" value="<?= h($chkno); ?>">
                <input name="token" type="hidden" value="<?= h($token); ?>">
                <h3 class="h4">コメントする</h3>
                <div class="form-group mb-4">
                    <label for="name">名前</label>
                    <input type="text" name="name" placeholder="名前" id="name" class="form-control" value="<?= $name = $editName === "" ? "名無しさん" : h($editName); ?>">
                </div>
                <div class="form-group mb-4">
                    <label for="comment">コメント</label>
                    <textarea name="comment" placeholder="コメント" cols="30" rows="10" id="comment" class="form-control"><?= h($editComment); ?></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="file">添付</label>
                    <input type="file" name="upfile" id="file" class="form-control">
                    <p>※画像はjpeg方式，png方式，gif方式に対応しています。動画はmp4方式のみ対応しています。</p>
                </div>
                <div class="form-group mb-4">
                    <label for="pass">パスワード</label>
                    <input type="text" name="pass" placeholder="パスワード" id="pass" class="form-control" value="<?= h($editPass); ?>">
                    <input type="submit" name="submit" class="btn btn-primary mt-2" value="送信">
                </div>
                <input type="hidden" name="editPost" value="<?= h($editNum); ?>">
                <input type="hidden" name="id" value="<?= h($id); ?>">
                <a href="#top" class="btn btn-secondary">ページトップへ</a>
            </form>
        </div>
        <section class="col-md-3 col-sm-12 mb-4 bg-light">
            <h4 class="h4 mb-4">他のスレッド</h4>
            <a href="top.php" class="btn btn-primary d-block mb-2">スレッド一覧に戻る</a>
            <?php if (count($selectThreads)) : ?>
                <ol>
                    <?php foreach ($selectThreads as $thread) : ?>
                        <li><a href="thread.php?id=<?= h($thread['id']); ?>" class="btn btn-link h5 p-0"><?= h($thread['title']); ?></a></li>
                        <p><?= h($thread['summary']); ?></p>
                    <?php endforeach; ?>
                </ol>
            <?php else : ?>
                <p class="text-danger col-4">スレッドが作成されていません</p>
            <?php endif; ?>
            <form method="POST" action="thread.php" enctype="multipart/form-data" class="bg-light p-4 mt-4">
                <input name="chkno" type="hidden" value="<?= h($chkno); ?>">
                <input name="token" type="hidden" value="<?= h($token); ?>">
                <input type="hidden" name="editPost" value="<?= h($editNum); ?>">
                <input type="hidden" name="id" value="<?= h($id); ?>">
                <div class="mb-4">
                    <label for="delete">削除</label>
                    <div id="delete" class="form-group">
                        <input type="text" name="deleteNum" placeholder="削除対象番号" class="form-control">
                        <input type="text" name="deletePass" placeholder="パスワード" class="form-control">
                        <input type="submit" name="delete" class="btn btn-primary mt-2" value="削除">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="edit">編集</label>
                    <div id="edit" class="form-group">
                        <input type="text" name="editNum" placeholder="編集対象番号" class="form-control">
                        <input type="text" name="editPass" placeholder="パスワード" class="form-control">
                        <input type="submit" name="edit" class="btn btn-primary mt-2" value="編集">
                    </div>
                </div>
            </form>
            <a href='logout.php' class="btn btn-secondary d-block">ログアウトはこちら</a>
        </section>
    </div>

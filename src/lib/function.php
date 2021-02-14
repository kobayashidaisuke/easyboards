<?php
function addRecord($link, $info)
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $link->prepare(
      "INSERT INTO submission (thread_id, name, comment, pass, whois, created_at) VALUES (:thread_id, :name, :comment, :pass, :whois, :created_at)"
    );
    $created_at = date('Y/m/d H:i:s');
    $sql->bindValue(':thread_id', $info['id'], PDO::PARAM_INT);
    $sql->bindValue(':name', $info['name'], PDO::PARAM_STR);
    $sql->bindValue(':comment', $info['comment'], PDO::PARAM_STR);
    $sql->bindValue(':pass', $info['pass'], PDO::PARAM_STR);
    $sql->bindValue(':whois', $info['whois'], PDO::PARAM_STR);
    $sql->bindValue(':created_at', $created_at, PDO::PARAM_STR);

    $sql->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function addImg($link, $info)
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $link->prepare(
      "INSERT INTO submission (thread_id, name, comment, pass, fname, extension, filepass, whois, created_at) VALUES (:thread_id, :name, :comment, :pass, :fname, :extension, :filepass, :whois, :created_at)"
    );
    $created_at = date('Y/m/d H:i:s');
    $sql->bindValue(':thread_id', $info['id'], PDO::PARAM_INT);
    $sql->bindValue(':name', $info['name'], PDO::PARAM_STR);
    $sql->bindValue(':comment', $info['comment'], PDO::PARAM_STR);
    $sql->bindValue(':pass', $info['pass'], PDO::PARAM_STR);
    $sql->bindValue(':fname', $info['fname'], PDO::PARAM_STR);
    $sql->bindValue(':extension', $info['extension'], PDO::PARAM_STR);
    $sql->bindValue(':filepass', $info['filepass'], PDO::PARAM_STR);
    $sql->bindValue(':whois', $info['whois'], PDO::PARAM_STR);
    $sql->bindValue(':created_at', $created_at, PDO::PARAM_STR);

    $sql->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function deleteRecord($link, $num)
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = "delete from submission where id=:id";
    $sql = $link->prepare($sql);
    $sql->bindValue(":id", $num, PDO::PARAM_INT);
    $sql->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function editRecord($link, $info)
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql =<<<EOT
    UPDATE submission SET
    thread_id=:thread_id,
    name=:name,
    comment=:comment,
    pass=:pass,
    whois=:whois,
    created_at=:created_at
    WHERE id=:id
    EOT;
    $sql = $link->prepare($sql);
    $created_at = date("Y/m/d H:i:s");
    $sql->bindValue(":thread_id", $info['id'], PDO::PARAM_INT);
    $sql->bindValue(":name", $info['name'], PDO::PARAM_STR);
    $sql->bindValue(":comment", $info['comment'], PDO::PARAM_STR);
    $sql->bindValue(":pass", $info['pass'], PDO::PARAM_STR);
    $sql->bindValue(":whois", $info['whois'], PDO::PARAM_STR);
    $sql->bindValue(":created_at", $created_at, PDO::PARAM_STR);
    $sql->bindValue(":id", $info['editPost'], PDO::PARAM_INT);
    $sql->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function editImg($link, $info)
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = <<<EOT
    UPDATE submission SET
    thread_id=:thread_id,
    name=:name,
    comment=:comment,
    pass=:pass,
    fname=:fname,
    extension=:extension,
    filepass=:filepass,
    whois=:whois,
    created_at=:created_at
    WHERE id=:id
    EOT;
    $sql = $link->prepare($sql);
    $created_at = date("Y/m/d H:i:s");
    $sql->bindValue(":thread_id", $info['id'], PDO::PARAM_INT);
    $sql->bindValue(":name", $info['name'], PDO::PARAM_STR);
    $sql->bindValue(":comment", $info['comment'], PDO::PARAM_STR);
    $sql->bindValue(":pass", $info['pass'], PDO::PARAM_STR);
    $sql->bindValue(":fname", $info['fname'], PDO::PARAM_STR);
    $sql->bindValue(":extension", $info['extension'], PDO::PARAM_STR);
    $sql->bindValue(":filepass", $info['filepass'], PDO::PARAM_STR);
    $sql->bindValue(":whois", $info['whois'], PDO::PARAM_STR);
    $sql->bindValue(":created_at", $created_at, PDO::PARAM_STR);
    $sql->bindValue(":id", $info['editPost'], PDO::PARAM_INT);
    $sql->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

//レスを取得
function getSubmissionForContents($id, $link)
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = "SELECT * FROM submission where thread_id =:id";
    $stmt = $link->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $contents = $stmt->fetchAll();
    return $contents;
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

//スレッドを取得
function getThreadForTitle($id, $link)
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = "SELECT * FROM threads where id=:id";
    $stmt = $link->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $contents = $stmt->fetchAll();
    return $contents;
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
//全スレッドを取得 top.php, thread.php
function selectThreads($link)
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = "SELECT id, title, summary, created_at FROM threads";
    $stmt = $link->prepare($sql);
    $stmt->execute();
    $contents = $stmt->fetchAll();
    return $contents;
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

//ログインしていなければ指定のURLに飛べない top.php, newThread.php, thread.php
function is_login()
{
  // セッションのキー：is_loginの値を取得し、判定を行う。
  if (
    isset($_SESSION['is_login']) === true && $_SESSION['is_login'] === true
  ) {
    $is_login = true;
  } else {
    $is_login = false;
  }
  return $is_login;
}

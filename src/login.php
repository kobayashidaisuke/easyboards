<?php
require_once __DIR__ . '/lib/escape.php';
require_once __DIR__ . '/lib/function.php';
require_once __DIR__ . '/lib/mysqli.php';
session_start();

//DB内でPOSTされたメールアドレスを検索
function searchEmailCount($link): int
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = "SELECT COUNT(*) AS cnt FROM userdata where email=:email";
    $stmt = $link->prepare($sql);
    $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_INT);
    $stmt->execute();
    $contents = $stmt->fetchAll();
    return $contents[0]['cnt'];
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function searchEmail($link): array
{
  try {
    $cnt = searchEmailCount($link);
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = "SELECT email, nickname, password FROM userdata where email=:email";
    $stmt = $link->prepare($sql);
    $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_INT);
    $stmt->execute();
    $contents = $stmt->fetchAll();
    $contents['cnt'] = $cnt;
    return $contents;
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

//POSTのvalidate
function validate(array $emails): array
{
  $errors = [];

  //email
  if ((int)$emails['cnt'] === 0) {
    $errors['email'] = '指定されたメールアドレスは登録されていません' . PHP_EOL;
    return $errors;
  } elseif (!strlen($_POST['email'])) {
    $errors['email'] = "メールアドレスを入力してください";
  } elseif (mb_strlen($_POST['email']) > MAXIMUM_LENGTH_50) {
    $errors['email'] = "メールアドレスを50文字以下で入力してください";
  } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "入力された値が不正です";
  }

  //password
  if (!strlen($_POST['password'])) {
    $errors['password'] = 'パスワードを入力してください';
  } elseif (!password_verify($_POST['password'], $emails[0]['password'])) {
    $errors['password'] = 'パスワードが間違っています';
  }

  return $errors;
}

function login(): void
{
  $_SESSION['is_login'] = true;
}

$title = 'ログイン';
$login = [
  'email' => '',
];
$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!is_chkno()) {
    header("location: login.php");
  }
  if (!is_token()) {
    throw new Exception('token mismatched');
  }
  $link = dbConnect();
  $emails = searchEmail($link);
  $errors = validate($emails);
  if (!count($errors)) {
    session_regenerate_id(true); //session_idを新しく生成し、置き換える
    $_SESSION['NICKNAME'] = $emails[0]['nickname']; //パスワード確認後sessionにニックネームを渡す
    login();
    header('Location: top.php');
  } else {
    $login['email'] = $_POST['email'];
  }
}

//新しい照合番号を発番
$_SESSION['chkno'] = $chkno = mt_rand();
//トークンの生成
$_SESSION['token'] = $token = bin2hex(openssl_random_pseudo_bytes(16));

$option = '';
$content = __DIR__ . '/views/login.php';
include __DIR__ . '/views/layout.php';

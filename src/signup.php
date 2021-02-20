<?php
require_once __DIR__ . '/lib/mysqli.php';
include_once __DIR__ . '/lib/escape.php';
session_start();

function searchEmail($link): int
{
    try {
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = "SELECT COUNT(*) FROM userdata where email=:email";
        $stmt = $link->prepare($sql);
        $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $stmt->execute();
        $contents = $stmt->fetchAll();
        return $contents[0]['COUNT(*)'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function searchNickname($link): int
{
    try {
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = "SELECT COUNT(*) FROM userdata where nickname=:nickname";
        $stmt = $link->prepare($sql);
        $stmt->bindValue(':nickname', $_POST['nickname'], PDO::PARAM_STR);
        $stmt->execute();
        $contents = $stmt->fetchAll();
        return $contents[0]['COUNT(*)'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function validate($link): array
{
    $errors = [];
    //e-mail
    $emailCount = searchEmail($link);
    if ($emailCount > 0) {
        $errors['email'] = '指定されたメールアドレスはすでに登録されています';
        return $errors;
    } elseif (!strlen($_POST['email']) || !strlen($_POST['email-2'])) {
        $errors['email'] = "メールアドレスを入力してください";
    } elseif (mb_strlen($_POST['email']) > MAXIMUM_LENGTH_50 || mb_strlen($_POST['email-2']) > MAXIMUM_LENGTH_50) {
        $errors['email'] = "メールアドレスを50文字以下で入力してください";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "入力された値が不正です";
    } elseif ($_POST['email'] !== $_POST['email-2']) {
        $errors['email'] = "入力したメールアドレスが一致しません";
    }
    //ニックネーム
    $nicknameCount = searchNickname($link);
    if ($nicknameCount > 0) {
        $errors['nickname'] = '指定されたニックネームはすでに登録されています';
        return $errors;
    } elseif (!isset($_POST['nickname'])) {
        $errors['nickname'] = ' ニックネームを入力してください';
    } elseif (mb_strlen($_POST['nickname']) > MAXIMUM_LENGTH_50) {
        $errors['nickname'] = 'ニックネームは50字以下で入力してください';
    }
    //パスワード
    if (!preg_match("/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i", $_POST['password'])) {
        $errors['password'] = 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください';
    } elseif (mb_strlen($_POST['password']) > MAXIMUM_LENGTH_50) {
        $errors['password'] = 'パスワードを50字以下で入力してください';
    }
    return $errors;
}

function insertUser($link, $email, $nickname, $password): void
{
    try {
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = $link->prepare("INSERT INTO userdata (email, nickname, password, created_at) VALUES (:email, :nickname, :password, :created_at)");
        $created_at = date("Y/m/d H:i:s");
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->bindValue(':nickname', $nickname, PDO::PARAM_STR);
        $sql->bindValue(':password', $password, PDO::PARAM_STR);
        $sql->bindValue(':created_at', $created_at, PDO::PARAM_STR);
        $sql->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$title = '登録';
$option = '';
$signup = [
    'email' => '',
    'email-2' => '',
    'nickname' => ''
];

$errors = [];
$message = '';

//フォーム多重送信を回避
$is_chkno = isset($_REQUEST["chkno"]) === true && isset($_SESSION["chkno"]) === true && (int)$_REQUEST["chkno"] == $_SESSION["chkno"];
//トークンチェック
$is_token = empty($_POST['token']) || empty($_SESSION['token']) || $_POST['token'] !== $_SESSION['token'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($is_chkno) {
        if ($is_token) {
            throw new Exception('token mismatched');
            return;
        }
        $link = dbConnect();
        $errors = validate($link);
        if (!count($errors)) {
            $signup['email'] = $_POST['email'];
            //パスワードのハッシュ化
            $signup['password'] = password_hash($_POST["password"], PASSWORD_DEFAULT);
            insertUser($link, $signup['email'], $_POST['nickname'], $signup['password']);
            $message = "登録が完了しました";
            $signup['email'] = '';
            $signup['email-2'] = '';
            $signup['nickname'] = '';
            $option = '<meta http-equiv="refresh" content=" 2; url=index.php">';
        } else {
            $signup['email'] = $_POST['email'];
            $signup['email-2'] = $_POST['email-2'];
            $signup['nickname'] = $_POST['nickname'];
        }
    }
}

//新しい照合番号を発番
$_SESSION['chkno'] = $chkno = mt_rand();
//トークンの生成
$_SESSION['token'] = $token = bin2hex(openssl_random_pseudo_bytes(16));


$content = __DIR__ . '/views/signup.php';
include __DIR__ . '/views/layout.php';

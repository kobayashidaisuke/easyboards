<?php
session_start();

$e = "";

if (isset($_SESSION["NICKNAME"])) {
  $e = 'ログアウトしました。';
} else {
  $e = 'セッションがタイムアウトしました。';
}

//セッション変数のクリア
$_SESSION = [];
//セッションクッキーの削除
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}
//セッションクリア
@session_destroy();

$title = 'ログアウト';
$option = '<meta http-equiv="refresh" content=" 2; url=index.php">';
$content = __DIR__ . '/views/logout.php';
include __DIR__ . '/views/layout.php';

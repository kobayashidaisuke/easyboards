<?php
require_once __DIR__ . '/lib/escape.php';
require_once __DIR__ . '/lib/function.php';
require_once __DIR__ . '/lib/mysqli.php';
session_start();

function insertThread($link, $thread): void
{
  try {
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $link->prepare("INSERT INTO threads (title, summary, created_at) VALUES (:title, :summary, :created_at)");
    $created_at = date("Y/m/d H:i:s");
    $sql->bindValue(':title', $thread['title'], PDO::PARAM_STR);
    $sql->bindValue(':summary', $thread['summary'], PDO::PARAM_STR);
    $sql->bindValue(':created_at', $created_at, PDO::PARAM_STR);
    $sql->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function validate($thread): array
{
  $errors = [];
  //タイトル
  if (!strlen($thread['title'])) {
    $errors['title'] = 'タイトルを入力してください' . PHP_EOL;
  } elseif (mb_strlen($thread['title']) > MAXIMUM_LENGTH_50) {
    $errors['title'] = '50文字以下でタイトルを入力してください' . PHP_EOL;
  }
  //詳細
  if (!strlen($thread['summary'])) {
    $errors['summary'] = '詳細を入力してください' . PHP_EOL;
  } elseif (mb_strlen($thread['summary']) > MAXIMUM_LENGTH_50) {
    $errors['summary'] = '50文字以下で詳細を入力してください' . PHP_EOL;
  }
  return $errors;
}

function latestThread($threads): int
{
  for ($i = 0; $i < count($threads); $i++) {
    if ($i === (int)count($threads) - 1) {
      $latestId = $threads[$i]['id'];
    }
  }
  return $latestId;
}

$thread = [
  'title' => '',
  'summary' => '',
];

if (!is_login()) {
  header("location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!is_chkno()) {
    header("location: newThread.php");
  }
  if (!is_token()) {
    throw new Exception('token mismatched');
  }
  $thread = [
    'title' => $_POST['title'],
    'summary' => $_POST['summary'],
  ];
  //バリデーション処理
  $errors = validate($thread);
  if (!count($errors)) {
    $link = dbConnect();
    insertThread($link, $thread);
    $threads = selectThreads($link);
    $latestId = latestThread($threads);
    // スレッド画面に遷移
    header("Location: thread.php?id={$latestId}");
  }
}

//新しい照合番号を発番
$_SESSION['chkno'] = $chkno = mt_rand();
//トークンの生成
$_SESSION['token'] = $token = bin2hex(openssl_random_pseudo_bytes(16));

$title = 'スレッド作成';
$option = '';
$content = __DIR__ . '/views/newThread.php';
include __DIR__ . '/views/layout.php';

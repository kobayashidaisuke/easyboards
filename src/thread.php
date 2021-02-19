<?php
require_once __DIR__ . '/lib/function.php';
require_once __DIR__ . '/lib/escape.php';
require_once __DIR__ . '/lib/mysqli.php';
session_start();

function validateStandard($info)
{
  $errors = [];
  if (!strlen($info['name'])) {
    $errors['name'] = '名前を入力してください';
  } elseif (mb_strlen($info['name']) > MAXIMUM_LENGTH_50) {
    $errors['name'] = '名前は50字以下で入力してください';
  }
  if (!strlen($info['comment'])) {
    $errors['comment'] = 'コメントを入力してください';
  } elseif (mb_strlen($info['comment']) > MAXIMUM_LENGTH_200) {
    $errors['comment'] = 'コメントは200字以下で入力してください';
  }
  if (!strlen($info['pass'])) {
    $errors['pass'] = 'パスワードを入力してください';
  } elseif (mb_strlen($info['pass']) > MAXIMUM_LENGTH_50) {
    $errors['pass'] = 'パスワードは50字以下で入力してください';
  }
  return $errors;
}

function fileCheck()
{
  $errors = [];
  //エラーチェック
  if ($_FILES['upfile']['error'] === UPLOAD_ERR_NO_FILE) {
    $errors['upfile'] = 'ファイルが選択されていません';
  } elseif ($_FILES['upfile']['error'] === UPLOAD_ERR_INI_SIZE) {
    $errors['upfile'] = 'ファイルサイズが大きすぎます';
  } elseif ($_FILES['upfile']['error'] === UPLOAD_ERR_OK) {
    //OK
  } else {
    $errors['upfile'] = 'その他のエラーが発生しました';
  }

  //画像・動画フォルダまでのパス
  $tmpfile = $_FILES['upfile']['tmp_name'];
  $filepass = 'gallery/' . $_FILES['upfile']['name'];
  if (!is_uploaded_file($tmpfile)) {
    $errors['upfile'] = 'ファイルが選択されていません';
  } else {
    if (!move_uploaded_file($tmpfile, $filepass)) {
      $errors['upfile'] = 'ファイルをアップロードできません';
    }
  }
  //拡張子を見る
  $tmp = pathinfo($_FILES["upfile"]["name"]);
  $extension = $tmp["extension"];
  if (
    $extension === "jpg" ||
    $extension === "jpeg" ||
    $extension === "JPG" ||
    $extension === "JPEG"
  ) {
    $extension = "jpeg";
  } elseif ($extension === "png" || $extension === "PNG") {
    $extension = "png";
  } elseif ($extension === "gif" || $extension === "GIF") {
    $extension = "gif";
  } elseif ($extension === "mp4" || $extension === "MP4") {
    $extension = "mp4";
  } else {
    $errors['upfile'] = '非対応ファイルです';
  }
  //DBに格納するファイルネーム設定
  //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
  $date = getdate();
  $fname =
    $_FILES["upfile"]["tmp_name"] .
    $date["year"] .
    $date["mon"] .
    $date["mday"] .
    $date["hours"] .
    $date["minutes"] .
    $date["seconds"];
  $fname = hash("sha256", $fname);

  $files = [
    'fname' => $fname,
    'extension' => $extension,
    'filepass' => $filepass,
    'errors' => $errors
  ];
  return $files;
}

function validateDelete($info)
{
  $errors = [];
  if (!isset($info['deleteNum'])) {
    $errors['deleteNum'] = '削除する番号が入力されていません';
  }
  if (!isset($info['deletePass'])) {
    $errors['deletePass'] = '削除する番号のパスワードが入力されていません';
  }
  return $errors;
}

function validateEdit($info)
{
  $errors = [];
  if (!isset($info['editNum'])) {
    $errors['editNum'] = '編集する番号が入力されていません';
  }
  if (!isset($info['editPass'])) {
    $errors['editPass'] = '編集する番号のパスワードが入力されていません';
  }
  return $errors;
}

function searchInfo($link, $detail)
{
  try {
    $sql = "SELECT id, name, comment, pass, fname, extension, filepass FROM submission where id=:id";
    $stmt = $link->prepare($sql);
    $stmt->bindValue(':id', $detail, PDO::PARAM_INT);
    $stmt->execute();
    $contents = $stmt->fetchAll();
    $contents[0]['error'] = '';
    return $contents[0];
  } catch (PDOException $e) {
    $contents[0]['error'] = 'エラーが発生しました';
    echo $e->getMessage();
    return $contents[0];
  }
}

$summary = '';
$created_at = '';
$title = '';

$editNum = '';
$editName = '';
$editComment = '';
$editPass = '';

if (!is_login()) {
  header("location: index.php");
}

//データベースに接続
$link = dbConnect();
//スレッドIDを取得
$id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];
//ファイルが選択された場合
$is_file = isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES['upfile']['name'] !== '';
//フォーム多重送信を回避
$is_chkno = isset($_REQUEST["chkno"]) == true && isset($_SESSION["chkno"]) == true && (int)$_REQUEST["chkno"] == $_SESSION["chkno"];
//idの受け取り
if (isset($_POST['id'])) {
  $postId = h($_POST['id']);
  $url = "thread.php?id={$postId}";
} else {
  $getId = h($_GET['id']);
  $url = "thread.php?id={$getId}";
}

//トークンチェック
$is_token = empty($_POST['token']) || empty($_SESSION['token']) || $_POST['token'] !== $_SESSION['token'];

if (
  isset($_SESSION['editNum']) &&
  isset($_SESSION['editName']) &&
  isset($_SESSION['editComment']) &&
  isset($_SESSION['editPass'])
) {
  $editNum = $_SESSION['editNum'];
  $editName = $_SESSION['editName'];
  $editComment = $_SESSION['editComment'];
  $editPass = $_SESSION['editPass'];
  unset($_SESSION['editNum']);
  unset($_SESSION['editName']);
  unset($_SESSION['editComment']);
  unset($_SESSION['editPass']);
}

//------------------------------------------------------------------------------------------------------


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($is_chkno) {
    if ($is_token) {
      throw new Exception('token mismatched');
    }
    //新規作成ボタンが押された場合
    if (isset($_POST['submit'])) {
      if ($_POST['editPost']) {
        //ファイルが選択された場合
        if ($is_file) {
          $searchInfo = searchInfo($link, $_POST['editPost']);
          if (!strlen($searchInfo['error'])) {
            if ($_POST['editPost'] === $searchInfo['id']) {
              $files = fileCheck($url);
              if (!count($files['errors'])) {
                $info = [
                  'id' => $_POST['id'],
                  'editPost' => $_POST['editPost'],
                  'name' => $_POST['name'],
                  'comment' => $_POST['comment'],
                  'pass' => $_POST['pass'],
                  'fname' => $files['fname'],
                  'extension' => $files['extension'],
                  'filepass' => $files['filepass'],
                  'whois' => $_SESSION["NICKNAME"]
                ];
                $errors = validateStandard($info);
                if (!count($errors)) {
                  editImg($link, $info);
                  header("Location: $url");
                }
              }
            } else {
              $errors['editPost'] = '編集する番号を正しく入力してください';
            }
          }
        } else {
          //編集番号と等しい時、新しいデータを入れ直す
          $searchInfo = searchInfo($link, $_POST['editPost']);
          if (!strlen($searchInfo['error'])) {
            $info = [
              'id' => $_POST['id'],
              'editPost' => $_POST['editPost'],
              'name' => $_POST['name'],
              'comment' => $_POST['comment'],
              'pass' => $_POST['pass'],
              'whois' => $_SESSION["NICKNAME"]
            ];
            $errors = validateStandard($info);
            if (!count($errors)) {
              editRecord($link, $info);
              header("Location: $url");
            }
          }
        }
      } elseif ($is_file) {
        $files = fileCheck($url);
        if (!count($files['errors'])) {
          $info = [
            'id' => $_POST['id'],
            'name' => $_POST['name'],
            'comment' => $_POST['comment'],
            'pass' => $_POST['pass'],
            'fname' => $files['fname'],
            'extension' => $files['extension'],
            'filepass' => $files['filepass'],
            'whois' => $_SESSION["NICKNAME"]
          ];
          $errors = validateStandard($info);
          if (!count($errors)) {
            addImg($link, $info);
            header("Location: $url");
          }
        }
      } else {
        //新規投稿の場合
        $info = [
          'id' => $_POST['id'],
          'name' => $_POST['name'],
          'comment' => $_POST['comment'],
          'pass' => $_POST['pass'],
          'whois' => $_SESSION["NICKNAME"]
        ];
        $errors = validateStandard($info);
        if (!count($errors)) {
          addRecord($link, $info);
          header("Location: $url");
        }
      }
    }

    if (isset($_POST['delete'])) {
      $info = [
        'deleteNum' => $_POST['deleteNum'],
        'deletePass' => $_POST['deletePass']
      ];
      $errors = validateDelete($info);
      if (!count($errors)) {
        $deleteInfo = searchInfo($link, $_POST['deleteNum']);
        if (!strlen($deleteInfo['error'])) {
          if ($_POST['deletePass'] === $deleteInfo['pass']) {
            deleteRecord($link, $_POST['deleteNum']);
            header("Location: $url");
          } else {
            $errors['pass'] = 'パスワードが一致しません';
          }
        }
      }
    }

    if (isset($_POST['edit'])) {
      $info = [
        'editNum' => $_POST['editNum'],
        'editPass' => $_POST['editPass']
      ];
      $errors = validateEdit($info);
      if (!count($errors)) {
        $editInfo = searchInfo($link, $_POST['editNum']);
        if (!strlen($editInfo['error'])) {
          if ($_POST['editPass'] === $editInfo['pass']) {
            $_SESSION['editNum'] = $editInfo['id'];
            $_SESSION['editName'] = $editInfo['name'];
            $_SESSION['editComment'] = $editInfo['comment'];
            $_SESSION['editPass'] = $editInfo['pass'];
            header("Location: $url");
          } else {
            $errors['pass'] = 'パスワードが一致しません';
          }
        }
      }
    }
  }
}

$threadForTitle = getThreadForTitle($id, $link);
$submissionForContents = getSubmissionForContents($id, $link);
$selectThreads = selectThreads($link);

$summary = $threadForTitle[0]['summary'];
$created_at = $threadForTitle[0]['created_at'];
$title = $threadForTitle[0]['title'];

//新しい照合番号を発番
$_SESSION['chkno'] = $chkno = mt_rand();
//トークンの生成
$_SESSION['token'] = $token = bin2hex(openssl_random_pseudo_bytes(16));

$option = '';
$content = __DIR__ . '/views/thread.php';
include __DIR__ . '/views/layout.php';

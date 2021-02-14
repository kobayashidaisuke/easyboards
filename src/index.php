<?php
include_once __DIR__ . '/lib/escape.php';
session_start();

$title = 'ログイン';
$login = [
  'email' => '',
];
$signup = [
  'email' => '',
  'email-2' => '',
  'nickname' => ''
];

$errors = [];
$message = '';


//新しい照合番号を発番
$_SESSION['chkno'] = $chkno = mt_rand();
//トークンの生成
$_SESSION['token'] = $token = bin2hex(openssl_random_pseudo_bytes(16));


$option = '';
$content = __DIR__ . '/views/index.php';
include __DIR__ . '/views/layout.php';

<?php
require_once __DIR__ . '/lib/function.php';
require_once __DIR__ . '/lib/mysqli.php';
include_once __DIR__ . '/lib/escape.php';
session_start();


if (!is_login()) {
  header("location: index.php");
}

$link = dbConnect();
$title = 'スレッド選択';
$selectThreads = selectThreads($link);

$option = '';
$content = __DIR__ . '/views/top.php';
include __DIR__ . '/views/layout.php';

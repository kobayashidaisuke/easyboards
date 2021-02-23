# スレッド式掲示板

## アプリ概要

* コメント投稿以外にも、編集や削除、ファイル投稿などが可能な掲示板です。
* スレッド式になっており、ユーザーがスレッドを立ち上げることもできます。
* ログイン機能が備わっており、ユーザー登録後のログインで掲示板を利用可能です。
* ログインしていないと掲示板にアクセスできない仕様にしています。

## 使用言語・OS

* PHP 7.4 composer:1.10 apache
* macOS 11 Big Sur

## 機能一覧

1. コメントを登録できる（名前、コメント、パスワード、動画ファイル、画像ファイル）
2. コメントを表示できる
3. コメントを削除できる
4. コメントを編集できる
5. スレッドを立ち上げることができる

## 注力したこと・工夫したこと

* スレッド機能
* ログイン機能
* 素のPHPでバリデーションからセキュリティ対策をしたこと

## 環境構築の手順
* docker composeでappコンテナとdbコンテナを作成、運用
1. docker-compose build
2. docker-compose up -d
3. 指定したポート番号にアクセスし、繋がっていることを確認

## デモ画面
### ログイン画面
<img width="1680" alt="ログイン画面" src="https://user-images.githubusercontent.com/62587652/108823823-497fef80-7604-11eb-8385-43ed84f2a1da.png">

### 新規登録画面
<img width="1680" alt="新規登録画面" src="https://user-images.githubusercontent.com/62587652/108823722-29e8c700-7604-11eb-80f8-04bd417e5938.png">

### スレッド選択画面
<img width="1680" alt="スレッド選択画面" src="https://user-images.githubusercontent.com/62587652/108823956-792ef780-7604-11eb-9102-7a41142e9146.png">

### スレッド作成画面
<img width="1680" alt="スレッド作成画面" src="https://user-images.githubusercontent.com/62587652/108824066-9f549780-7604-11eb-968e-d2d21499950e.png">

### 掲示板画面
<img width="1680" alt="掲示板投稿一覧" src="https://user-images.githubusercontent.com/62587652/108824215-d0cd6300-7604-11eb-8f04-1f7f71617934.png">

### 掲示板投稿フォーム
<img width="1680" alt="掲示板投稿フォーム" src="https://user-images.githubusercontent.com/62587652/108824359-f5c1d600-7604-11eb-825d-7b2d22e912e5.png">

* テストアカウント
  * Email：sample@gmail.com
  * Password：sample11

## 注意点
1. 掲示板の利用は自己責任です。自身の投稿には責任を持ちましょう。
2. 読み手を意識した書き込みをお願いします。
3. トラブル防止のため、個人情報や誹謗中傷は控えてください。
4. この掲示板は匿名ではありません。問題のある投稿をした場合は管理人が投稿の削除、登録情報の削除またはその両方を実行します。

## 使用したライブラリ
* scssphp / scssphp
https://github.com/scssphp/scssphp
* vlucas / phpdotenv
https://github.com/vlucas/phpdotenv
* twbs / bootstrap:5.0.0-beta1
https://getbootstrap.jp/

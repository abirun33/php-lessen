<?php
// 関連ファイルのインポート
require_once ('./Message.php');
require_once ('./env.php');

if($_SERVER['REQUEST_METHOD']==='POST'){
// POSTリクエスト時の処理
// POSTされたデータを取得
$user_name = htmlspecialchars($_POST['user_name']);
$user_email = htmlspecialchars($_POST['user_email']);
$main = htmlspecialchars($_POST['main']);
// データのチェック（バリデーション）

// データベースに登録
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $sql = 'insert into messages(user_name, user_email, main, created_at) values(:user_name, :user_email, :main, now())';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);
  $stmt->bindValue(':user_email', $user_email, PDO::PARAM_STR);
  $stmt->bindValue(':main', $main, PDO::PARAM_STR);
  $stmt->execute();
}catch(PDOEXception $e){
  print("DBに接続できませんでした");
  die();
}
// リダイレクト
  header('Location:'.$_SERVER['SCRIPT_NAME']);
  exit();
}else{
// GETリクエスト時の処理
  // 一覧表示用の配列を宣言
  $message_list = array();
  try {
    // DBにアクセスして登録済データを投稿の新しい順に取得
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $mags = $pdo->query(
      "SELECT * FROM messages ORDER BY id DESC"
    );

    // Messageオブジェクトに格納、配列に追加
    foreach ($mags as $mag) {
      $message = new Message($mag['user_name'],$mag['user_email'],$mag['main'],$mag['created_at']);
      array_push($message_list,$message);
      echo "push done";
    }
  }catch(PDOEXception $e){
    print("DBに接続できませんでした");
    die();
  }
}

?>
<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>PHP伝言板(abiru)</title>
</head>
<body>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
          <h1 class="display-4">PHP Message Board</h1>
          <form method="POST">
            <div class="form-group">
              <label for="user_name">お名前</label>
              <input type="text" class="form-control" name="user_name" id="user_name">
              <small class="form-text text-muted">投稿者名を記入してください</small>
            </div>
            <div class="form-group">
                <label for="user_email">メールアドレス</label>
                <input type="email" class="form-control" name="user_email" id="user_email">
                <small class="form-text text-muted">投稿者のメールアドレスを記入してください</small>
              </div>
            <div class="form-group">
              <label for="main">メッセージ</label>
              <textarea name="main" class="form-control" id="main" rows="3"></textarea>
              <small class="form-text text-muted">メッセージ本文</small>
            </div>
            <button type="submit" class="btn btn-primary">投稿</button>
          </form>
        </div>
      </div>
     <!--表示部分-->  
    <div class="container">
      <?php
      echo count($message_list);
      foreach ($message_list as $message) { ?>
        <div class="alert alert-primary" role="alert">
           <p>投稿内容<?=$message->get_main() ?></p>
           <p class="text-right"><?=$message->get_user_name() ?> (<?=$message->get_created_at() ?> )投稿者</p>

        </div>
      <?php } ?>  
    </div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>

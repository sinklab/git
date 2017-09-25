<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  <title>ブログ記事の編集</title>
</head>
<body>
  <h1 id="midasi">ブログ記事の編集</h1>

  <div id="menu">
    <ul>
      <li><a href="index.php">トップページ</a></li>
      <li><a href="post.php">投稿</a></li>
      <li><a href="search.php">検索</a></li>
    </ul>
    <br>
    <br>
  </div>

  <?php
  try {

  	     //PDOクラスのオブジェクトの作成
   $dbh = new PDO('sqlite:blog.db','','');

   if (isset($_POST["id"]) && !isset($_POST["title"]) && !isset($_POST["contents"])) {

             //実行するSQL文を$sqlに格納
             //index.phpから転送されたidを元に対象記事を抽出する
     $sql ='select * from posts where id = ?';
             //prepareメソッドでSQL文の準備
     $sth = $dbh->prepare($sql);
             //prepareした$sthを実行　SQL文の？部に格納する変数を指定
     $sth->execute(array($_POST["id"]));

     if ($row = $sth->fetch()) {       
       $_POST["title"] = $row['title'];
       $_POST["contents"] = $row['contents'];
     }

   } elseif (isset($_POST["id"]) && isset($_POST["title"]) && isset($_POST["contents"])) {
     if (!isset($_POST["password"]) || $_POST["password"] != 'abc') {
       echo '<p>パスワードが違います</p>';
     }
     else {

               //実行するSQL文を$sqlに格納
       $sql='update posts set title = ?,contents = ? where id = ?';
               //prepareメソッドでSQL文の準備
       $sth = $dbh->prepare($sql);
               //prepareした$sthを実行　SQL文の？部に格納する変数を指定
       $sth->execute(array($_POST["title"],$_POST["contents"], $_POST["id"]));

       if ($sth) {
        echo "記事１件を更新しました";
      } else {
        echo "記事１件の更新に失敗しました";
      }

    }
  }

  $dbh = null;

} Catch (PDOException $e) {
 print "エラー!: " . $e->getMessage() . "<br/>";
 die();
}

?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
  <dl>
   <dt><h2>表題：</h2></dt>
   <dd><input type="text" name="title" value="<?php echo $_POST["title"] ?>" size="60" /></dd>
   <dt><h2>本文：</h2></dt>
   <dd><textarea name="contents" rows="10" cols="60"><?php echo $_POST["contents"] ?></textarea></dd>
   <dt><h2>パスワード：</h2></dt>
   <dd><input type="password" name="password" size="20" placeholder="abc"/></dd>
 </dl>
 <input type="hidden" name="id" value="<?php echo $_POST["id"] ?>" />
 <input type="reset" value="リセット" class="btn"/>
 <input type="submit" value="送信" class="btn"/>
</form>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
	<title></title> 
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
	<h1 id="midasi">コメント削除</h1>
	<div id="menu">
		<ul>
			<li><a href="index.php">トップページ</a></li>
			<li><a href="post.php">投稿</a></li>
			<li><a href="search.php">検索</a></li>
			<br>
			<br>
		</ul> 
	</div>
	<?php
	if (isset($_POST["id"])) {
		if(!isset($_POST["password"])||$_POST["password"]!='abc'){
			echo "<p>パスワードが違います</p>";
		}else{
			try{
				$dbh = new PDO('sqlite:blog.db','','');
				$sql = "delete from posts where id = ?";
				$sth = $dbh->prepare($sql);
				$sth->execute(array($_POST["id"]));
				if ($sth) {
					echo "記事１件を削除しました";
					?>
					<?php
				} else {
					echo "記事１件の削除に失敗しました";
				}

			}Catch(PDOException $e){
				echo "エラー";
			}
			$dbh = null;
		}
	}
	?>
</body>
</html>
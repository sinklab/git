<!DOCTYPE html>
<html>
<head>
	<title></title> 
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
	<h1 id="midasi5">コメント投稿</h1>

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
	try{
		ini_set("date.timezone", "Asia/Tokyo");
		$time = date("Y.m.d-H:i");
		$contents = $_POST["contents"];
		$pid = $_POST["pid"]; 
		$dbh = new PDO("sqlite:blog.db"," "," ");
		$sql = "insert into comments(pid,contents,date) values(?,?,?)";
		$sth = $dbh->prepare($sql);
		$sth->execute(array($pid,$contents,$time));
		if($sth){
			echo "コメント投稿に成功しました";
		}else{
			echo "コメント投稿に失敗しました";
		}
	}Catch(PDOException $e){
		echo "エラー";
	}
	?>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
	<h1 id="midasi">管理者ページ</h1>
	<div id="menu">
		<ul>
			<li><a href="index.php">トップページ</a></li>
			<li><a href="post.php">投稿</a></li>
			<li><a href="search.php">検索</a></li>
			<li><a href="kanri.php">管理者</a></li>
			<br>
			<br>
		</ul> 
	</div>
	<?php
	if (empty($_POST["user"]) && empty($_POST["password"])) {
		?>
		<form action="kanri.php" method="post">
			ユーザ名<input type="text" name="user" value="root"><br>
			パスワード<input type="password" name="password">
			<input type="submit" value="ログイン" class="btn">
			<?php
		}
		?>
	</form>
</body>
</html>
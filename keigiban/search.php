<!DOCTYPE html>
<html>
<head>
	<title>検索</title>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
	<h1 id="midasi">検索</h1>
	<div id="menu">
		<ul>
			<li><a href="index.php">トップページ</a></li>
			<li><a href="post.php">投稿</a></li>
			<li><a href="search.php">検索</a></li>
			<br>
			<br>
		</ul> 
	</div>
	<form action="search.php" method="post">
		<input type="text" name="search" placeholder="検索キーワード">
		<input type="submit" value="検索" class="btn">
	</form>
</body>
</html>
<?php
if (!empty($_POST["search"])) {
	try{
		$dbh = new PDO('sqlite:blog.db','','');
		$q = "%".$_POST["search"]."%";
		$sql = "select * from posts where contents like ? or title like ?";
		$sth = $dbh->prepare($sql);
		$sth->execute(array($q,$q));

		while ($row = $sth->fetch()) {
			?>
			<div class="block">
				<?php
				$time = preg_split("/[\s.:-]+/",$row['date']);
				?>
				<h3><?php echo $row['title'] ?></h3>
				<p>
					<?php echo nl2br($row['contents']) ?><br>
					(<?php echo $time[0]."年".$time[1]."月". $time[2]."日 ".$time[3].":".$time[4] ?>)
				</p>
				<form action="edit.php" method="post">
					<p>
						<input type="submit" value="編集">
						<input type="hidden" name="id" value="<?php echo $row["id"] ?>">
					</p>
				</form>
				<form action="delete.php" method="post">
					<p>
						<input type="submit" value="削除">
						パスワード<input type="password" name="password" size="20">
						<input type="hidden" name="id" value="<?php echo $row["id"]?>">
					</p>
				</form>
				コメント
				<input type="button" value="表示" onclick="hyoji(0,'<?php echo $row['id']?>')">
				<input type="button" value="非表示" onclick="hyoji(1,'<?php echo $row['id']?>')">
				<div style= "display: none" id = "<?php echo $row['id']?>">
					<br>
					<div class="note">
						<?php
						$sql = "select * from comments where pid = ?";
						$sth2 = $dbh->prepare($sql);
						$sth2->execute(array($row["id"]));
						while ($row2 = $sth2->fetch()) {
							$time2 = preg_split("/[\s.:-]+/",$row2['date']);
							?>
							<p id = "box">
								<?php echo $row2['contents'] ?><br>
								(<?php echo $time2[0]."年".$time2[1]."月". $time2[2]."日 ".$time2[3].":".$time2[4] ?>)
							</p>
							<?php
						}
						?>
						<form action="comment.php" method="post">
							<p>
								<textarea name="contents" rows="" cols="50"></textarea>
								<input type="hidden" name="pid" value="<?php echo $row['id'] ?>">
								<input type="submit" value="投稿">
							</p>
						</form>
					</div>
				</div>
			</div>
			<br>
			<?php
		}
	}Catch (PDOException $e) {
		print "エラー!: " . $e->getMessage() . "<br/>";
		die();
	}
}

?>
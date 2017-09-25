<?php
require_once __DIR__ . '/vendor/autoload.php';
//POST
$input = file_get_contents('php://input');
$json = json_decode($input);
$event = $json->events[0];
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '']);

//イベントタイプ判別
if ("message" == $event->type) {            //一般的なメッセージ(文字・イメージ・音声・位置情報・スタンプ含む)
    //テキストメッセージにはオウムで返す
	if ("text" == $event->message->type) {
		insert_user_message($event->source->userId,$event->message->text);
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder(text_message($event->source->userId));
	} else {
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("ごめん、わかんなーい(*´ω｀*)");
	}
} elseif ("follow" == $event->type) {        //お友達追加時
	$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("よろしくー");
} elseif ("join" == $event->type) {           //グループに入ったときのイベント
	$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('こんにちは よろしくー');
} else {
    //なにもしない
}
$response = $bot->replyMessage($event->replyToken, $textMessageBuilder);
syslog(LOG_EMERG, print_r($event->replyToken, true));
syslog(LOG_EMERG, print_r($response, true));
return;

function insert_user_message($user_id,$message){

	$dsn = "mysql:host=mysql540.db.sakura.ne.jp;dbname=sinnosuke_lineapidatabase;charset=utf8";
	$user = "sinnosuke";
	$password = "hiro0811";

	$date = new DateTime();
	$date->setTimezone(new DateTimeZone('Asia/Tokyo'));
	$timezone = $date->getTimezone(); 

	try{
		$dbh = new PDO($dsn, $user, $password);

		$sql = "insert into user(user_id,message,date) values (?,?,?)";
		$stmt = $dbh->prepare($sql);
		$stmt->execute(array($user_id,$message,$date->format('Y-m-d H:i:s')));
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
	$dbh = null;
}

function user_before_messages($user_id){

	$dsn = "mysql:host=mysql540.db.sakura.ne.jp;dbname=sinnosuke_lineapidatabase;charset=utf8";
	$user = "sinnosuke";
	$password = "hiro0811";
	$result = array();

	try{
		$dbh = new PDO($dsn, $user, $password);

		$sql = "select message from user where user_id = ? order by id DESC limit 2";
		$stmt = $dbh->prepare($sql);
		$stmt->execute(array($user_id));
		while ($row = $stmt->fetch()) {
			array_push($result,$row["message"]);
		}
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
	$dbh = null;
	return $result;
}

function text_message($user_id){
	$message = " ";
	$user_message = user_before_messages($user_id);

	if ($user_message[0] == "1") {
		$message = "京産検索する時刻は？";
		//$message = time_sql($$message[0]);
	}elseif($user_message[0] == "2") {
		$message ="上賀茂検索する時刻は？";
		//$message = time_sql($table,$message[0]);
	}elseif ($user_message[1] == "1" && ctype_digit($user_message[0])) {
		time_sql("kus_departure","0");
	}elseif ($user_message[1] == "2" && ctype_digit($user_message[0])) {
		//time_sql();
	}else{
		$message = "[ヘルプ]\nどちらを知りたいですか？？\n1京産大発\n2上賀茂発";
	}
	return $message;
}

function time_sql($table,$time){
	$dsn = "mysql:host=mysql540.db.sakura.ne.jp;dbname=sinnosuke_lineapidatabase;charset=utf8";
	$user = "sinnosuke";
	$password = "hiro0811";
	$time = "0904";
	//date('w');
	try{
		$dbh = new PDO($dsn, $user, $password);
		$sql = "select time from $table where day = ? and time > ? limit 1";
		$stmt = $dbh->prepare($sql);
		$stmt->execute(array("1",$time));
		$result = $stmt->fetch();
		$result = $result["time"];
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
	$dbh = null;
	return $result;
}






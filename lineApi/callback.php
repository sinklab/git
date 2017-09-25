<?php
require_once __DIR__ . '/vendor/autoload.php';
//POST
$input = file_get_contents('php://input');
$json = json_decode($input);
$event = $json->events[0];
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '']);
$message = message();
//イベントタイプ判別
if ("message" == $event->type) {            //一般的なメッセージ(文字・イメージ・音声・位置情報・スタンプ含む)
    //テキストメッセージにはオウムで返す
	if ("text" == $event->message->type) {
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
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

function message(){
	$dsn="mysql:host=mysql540.db.sakura.ne.jp;dbname=sinnosuke_lineapidatabase;charset=utf8";
	$user = "sinnosuke";
	$password = "hiro0811";
	$day = "Weekday";
	if(date("l")== "Wednesday"){
		$day = "Wednesday";
	}elseif(date("l")== "Saturday"){
		$day = "Saturday";
	}
	try{
		$dbh = new PDO($dsn, $user, $password);

		$sql = "select * from to_kamigamo where day = ? and time >= ? limit 1";
		$stmt=$dbh->prepare($sql);
		$stmt->execute(array($day,date("Gi")));
		$res = $stmt->fetch();
		$result = $res["time"];
		$hour = substr($result, 0, 2); 
		$minute = substr($result, 2, 2);
		if($result==null){
			$result="今日はもうバスがないよ。";
		}else{
			$result = "次のバスの時刻は".$hour."時".$minute."分です。";
		}
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
	$dbh = null;
	return $result;
}
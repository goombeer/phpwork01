<?php

$accessToken = 'O4ZC4kWwhIoEj/P/sMzBz6/l/t2HxCeVnopGEWjffC79fjZezdUk22U6wu1ZOmAUAgs02BBUKBm02ZFCkneVNS0tOlunyn+Ec7kJyUHbqLCAvxXnMKZ9h0sgOWmFuewkG4Xm0SceE/0yFgf8J4Ha0gdB04t89/1O/w1cDnyilFU=';

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);

//取得データ
$replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
$userID = $json_object->{"events"}[0]->{"source"}->{"userId"};
$message_type = $json_object->{"events"}[0]->{"message"}->{"type"};    //メッセージタイプ
$message_text = $json_object->{"events"}[0]->{"message"}->{"text"};    //メッセージ内容


//メッセージタイプが「text」以外のときは何も返さず終了
if($message_type != "text") exit;

//返信メッセージ
//返信メッセージ(ここはifでランダム)
$rand = rand(0,8);
if ($rand == 0) {
  $return_message_text = "ふーん";
} elseif ($rand ==1) {
  $return_message_text = "それで？";
} elseif ($rand == 2) {
  $return_message_text = "ほうほう";
} elseif ($rand == 3 ) {
  $return_message_text = "wwwwwwww";
} elseif ($rand == 4) {
  $return_message_text = "......";
} elseif ($rand == 5) {
  $return_message_text = "いいね！！";
} elseif ($rand == 6) {
  $return_message_text = "こいつ..さては...";
} elseif ($rand == 7) {
  $return_message_text = "なんて優秀な学生なんだ！！";
} else {
  $return_message_text = "採用！！！！！！！！！";
}

//返信実行
write_text($message_text);
sending_messages($accessToken, $replyToken, $message_type, $return_message_text);
push_messages($accessToken, $message_type, $return_message_text,$userID)
?>
<?php
//メッセージの送信
function sending_messages($accessToken, $replyToken, $message_type, $return_message_text){
    //レスポンスフォーマット
    $response_format_text = [
        "type" => $message_type,
        "text" => $return_message_text
    ];

    //ポストデータ
    $post_data = [
        "replyToken" => $replyToken,
        "messages" => [$response_format_text]
    ];

    //curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
?>
<?php
//メッセージの送信
function push_messages($accessToken, $message_type, $return_message_text,$userID){


  $pushMessage = [
    "年齢はいくつですか？",
    "今まで苦労したことは？",
    "今まで楽しかったことは？",
    "最後に自己PRをお願いいたします",
  ];

    //レスポンスフォーマット
    $response_format_text = [
        "type" => $message_type,
        "text" => $pushMessage[0]
    ];
    //ポストデータ
    $post_data = [
        "to" => $userID,
        "messages" => [$response_format_text]
    ];

    //curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
?>
<?php
function write_text($message_text){
  $file = fopen('data/data.txt','a');
  flock($file,LOCK_EX);
  fwrite($file,$message_text."\n");
  flock($file,LOCK_UN);
  fclose($file);
}


 ?>

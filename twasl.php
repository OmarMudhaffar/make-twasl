<?php 


// By @Omar_Real 
// Visit My Channel @Set_Web
// My Website www.ireal-corp.com

#----MAKE TG FOLDER----#

$get_toke = file_get_contents('info.txt');

$get_token = explode("\n", $get_toke);


$url_info = file_get_contents("https://api.telegram.org/bot$get_token[0]/getMe");

$json_info = json_decode($url_info);

$user = $json_info->result->username;

$bot_id = $json_info->result->id;

$admin = $get_token[1];

ob_start();

$API_KEY = $get_token[0];
define('API_KEY',$API_KEY);
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$from_id = $message->from->id;
$chat_id = $message->chat->id;
$text = $message->text;
$chat_id2 = $update->callback_query->message->chat->id;
$message_id = $update->callback_query->message->message_id;
$data = $update->callback_query->data;
$admin = $get_token[1];
$get_welc = file_get_contents('setwelc.txt');
$ex_welc = explode("\n", $get_welc);
$mid = $message->message_id;
$welc = file_get_contents('welcome.txt');
$get_ids = file_get_contents('ids.txt');
$ids = explode("\n", $get_ids);
$get_bc = file_get_contents('bc.txt');
$bc = explode("\n", $get_bc);
$count = count($ids);
$chat = file_get_contents('chat.txt');
$ex_chat = explode("\n", $chat);
$get_ban = file_get_contents('ban.txt');
$ban = explode("\n", $get_ban);
$reply = $message->reply_to_message->forward_from->id;
$count_ban = count($ban);
$get_fwd = file_get_contents('fwd.txt');
$ffwd = explode("\n", $get_fwd);
$fwd = $message->forward_from_chat->id;


if($text == '/start' and $chat_id == $ex_chat[0]){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'اهلا ✨ بك في خدمة بوت التواصل 💭 ',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'اضافت ترحيب 🗒','callback_data'=>'welc']],
[['text'=>'عمل اذاعة 📢','callback_data'=>'bc']],
[['text'=>'عمل توجيه لمنشور 🔄','callback_data'=>'fwd']],
[['text'=>'عدد المشتركين ♦️','callback_data'=>'count']],
[['text'=>'عدد المحضورين 🔇','callback_data'=>'ban']],
[['text'=>'اوامر اخرى 📋','callback_data'=>'commands']]
]    
])
]);
}

if($text == '/start' and !in_array($from_id,$ban) and $message->chat->type == 'private' and $chat_id != $get_token[1] and $chat_id != $ex_chat[0]){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>$welc,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'اصنع 🔨 بوتك الان ✅','url'=>'https://telegram.me/MakeTwasl_Bot']]    
]    
])
]);
}

if($data == 'welc'){
bot('editMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$message_id,
'text'=>'ارسل الترحيب الان 🗒',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الغاء ❌','callback_data'=>'cancle']]    
]    
])
]);


file_put_contents('setwelc.txt', $chat_id2);    
}

if($text and in_array($from_id, $ex_welc)){
for($i = $mid - 3; $i < $mid; $i++){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$i
]);
}

bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'تم ✅ اضافت الترحيب 💎✨',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الصفحة الرئيسية 🏚','callback_data'=>'home']]    
]
])
]);

file_put_contents('welcome.txt', $text);
file_put_contents('setwelc.txt', '');

}

if($text == '/start' and !in_array($from_id, $ids) and $message->chat->type == 'private' and $from_id != $get_token[1]){
file_put_contents('ids.txt', $from_id . "\n". FILE_APPEND);
}

if($data == 'bc' and $chat_id2 == $get_token[1]){
bot('editMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$message_id,
'text'=>'ارسل 📫 النص الان ✅',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الغاء ❌','callback_data'=>'cancle']]    
]    
])
]);

file_put_contents('bc.txt', $chat_id2);

}

if($data == 'bc' and $chat_id2 != $get_token[1]){
bot('answerCallbackQuery',[
'callback_query_id'=>$update->callback_query->id,
'message_id'=>$message_id,
'text'=>'هاذا الامر❗️لصاحب البوت فقط 🕴',
 'show_alert'=>true
 ]);      
}

if($text and in_array($from_id, $bc)){

for($i = $mid - 3; $i < $mid; $i++){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$i
]);
}

bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'تم ✅ ارسال رسالتك للجميع 👥',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'انهاء 💎','callback_data'=>'cancle']]    
]    
])
]);    
    
for($i = 0; $i < count($ids); $i++){
bot('sendMessage',[
'chat_id'=>$ids[$i],
'text'=>$text
]);
}

}

if($data == 'cancle'){
bot('editMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$message_id,
'text'=>'اهلا ✨ بك في خدمة بوت التواصل 💭 ',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'اضافت ترحيب 🗒','callback_data'=>'welc']],
[['text'=>'عمل اذاعة 📢','callback_data'=>'bc']],
[['text'=>'عمل توجيه لمنشور 🔄','callback_data'=>'fwd']],
[['text'=>'عدد المشتركين ♦️','callback_data'=>'count']],
[['text'=>'عدد المحضورين 🔇','callback_data'=>'ban']],
[['text'=>'اوامر اخرى 📋','callback_data'=>'commands']]
]    
])
]);

file_put_contents('bc.txt', '');
file_put_contents('setwelc.txt', '');
file_put_contents('fwd.txt', '');

}

if($data == 'count'){

bot('answerCallbackQuery',[
'callback_query_id'=>$update->callback_query->id,
'message_id'=>$message_id,
'text'=>'عدد ⚙️ مشتركين البوت 🚹 : ' . $count,
 'show_alert'=>true
 ]);      
}

if($data == 'commands'){
bot('editMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$message_id,
'text'=>"
اهلا بك 💎 في قائمة الاوامر الاضافية ⚙️

/ban - لحضر عضو
/uban - لالغاء الحضر
/addchat - لتفعيل البوت في مجموعة
/addpv - لتفعيل البوت في خاص
/welcome - لعرض الترحيب
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الصفحة الرئيسية 🏚','callback_data'=>'home']]    
]    
])
]);
}

if($text != '/start' and $message->chat->type == 'private' and $from_id != $get_token[1] and !in_array($from_id, $ban)){
bot('forwardMessage',[
'chat_id'=>$ex_chat[0],
'from_chat_id'=>$chat_id,
'message_id'=>$mid
]);

bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'تم ✅ ارسال رسالتك 📩',
'reply_to_message_id'=>$mid
]);
}


if($message->reply_to_message->forward_from->id and $text != '/ban' and $text != '/uban' and $chat_id == $ex_chat[0]){
bot('sendMessage',[
'chat_id'=>$message->reply_to_message->forward_from->id,
'text'=>$text,    
]);
}

if($message->reply_to_message->forward_from->id and $text == '/ban' and $chat_id == $ex_chat[0]){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'تم ✅ حضر العضو 🚹',
'reply_to_message_id'=>$mid
]);

bot('sendMessage',[
'chat_id'=>$message->reply_to_message->forward_from->id,
'text'=>'تم ✅ حضرك من البوت ❌',
]);

file_put_contents('ban.txt', $message->reply_to_message->forward_from->id . "\n", FILE_APPEND);

}

if($message->reply_to_message->forward_from->id and $text == '/uban' and $chat_id == $ex_chat[0]){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'تم ✅ الغاء حضر العضو ❌',
'reply_to_message_id'=>$mid
]);

bot('sendMessage',[
'chat_id'=>$message->reply_to_message->forward_from->id,
'text'=>'تم ✅ الغاء حضرك ❌'
]);


$str = str_replace($reply . "\n", '' ,$get_ban);

file_put_contents('ban.txt', $str);

}

if($data == 'ban'){
bot('answerCallbackQuery',[
'callback_query_id'=>$update->callback_query->id,
'message_id'=>$message_id,
'text'=>'عدد 💎 المحضورين ❌ : ' . $count_ban,
 'show_alert'=>true
 ]);      
}

if($data == 'home'){
bot('editMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$message_id,
'text'=>'اهلا ✨ بك في خدمة بوت التواصل 💭 ',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'اضافت ترحيب 🗒','callback_data'=>'welc']],
[['text'=>'عمل اذاعة 📢','callback_data'=>'bc']],
[['text'=>'عمل توجيه لمنشور 🔄','callback_data'=>'fwd']],
[['text'=>'عدد المشتركين ♦️','callback_data'=>'count']],
[['text'=>'عدد المحضورين 🔇','callback_data'=>'ban']],
[['text'=>'اوامر اخرى 📋','callback_data'=>'commands']]
]    
])
]);
}


if($text == '/addchat' and $from_id == $get_token[1] and $message->chat->type == 'supergroup'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'تم ✅ تفعيل هاذه المجموعة ✨ لاستقبال الرسائل 💎',
'reply_to_message_id'=>$mid
]);

file_put_contents('chat.txt', $chat_id . "\n");

}

if($text == '/addchat' and $from_id == $get_token[1] and $message->chat->type == 'private'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'هاذا الامر 💎 في المجموعات فقط 👥',
'reply_to_message_id'=>$mid
]);
}


if($text == '/addchat' and $from_id != $get_token[1] and $message->chat->type == 'supergroup'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'هاذا الامر 💎 لصاحب البوت فقط 🕴🏻',
'reply_to_message_id'=>$mid
]);
}

if($text == '/addpv' and $from_id == $get_token[1]){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'تم ✅ تفعيل استقبال الرسائل الى الخاص 💎✨',
'reply_to_message_id'=>$mid
]);    

file_put_contents('chat.txt', $from_id . "\n");

}

if($text == '/addpv' and $from_id != $get_token[1]){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'هاذا الامر 💎 لصاحب البوت فقط 🕴🏻',
'reply_to_message_id'=>$mid
]);
}

if($text == '/welcome'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>$welc,
'reply_to_message_id'=>$mid
]);
}



if($data == 'fwd' and $chat_id2 == $get_token[1]){
file_put_contents('fwd.txt', $chat_id2 . "\n");
bot('editMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$message_id,
'text'=>"قم بعمل توجيه 🔄 للمنشور الذي تريد نشره 🌫✅",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'خروج ❌','callback_data'=>'cancle']]    
]    
])
]);
}

if($data == 'fwd' and $chat_id2 != $get_token[1]){
bot('answerCallbackQuery',[
'callback_query_id'=>$update->callback_query->id,
'message_id'=>$message_id,
'text'=>'هاذا الامر❗️لصاحب البوت فقط 🕴',
 'show_alert'=>true
 ]);      
}

if($fwd and in_array($from_id, $ffwd) and $from_id == $get_token[1]){
for($i = $message->message_id - 3; $i < $message->message_id; $i++){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$i
]);
}

bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'تم ✅ ارسال توجيه لمنشورك 💭 اللى الجميع 🚹',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'خروج ❌','callback_data'=>'cancle']]    
]    
])
]);



for($i=0;$i<count($ffwd);$i++){
bot('forwardMessage',[
'chat_id'=>$ids[$i],
'from_chat_id'=>$chat_id,
'message_id'=>$message->message_id
]);
}

}


if($message->sticker){
bot('sendSticker',[
'chat_id'=>$reply,
'sticker'=>$message->sticker->file_id
]);
}

if($message->voice){
bot('sendVoice',[
'chat_id'=>$reply,
'voice'=>$message->voice->file_id
]);
}

if($message->video){
bot('sendVideo',[
'chat_id'=>$reply,
'video'=>$message->video->file_id
]);
}

if($message->document){
bot('sendDocument',[
'chat_id'=>$reply,
'document'=>$message->document->file_id
]);
}

if($message->video){
bot('sendVideo',[
'chat_id'=>$reply,
'video'=>$message->video->file_id
]);
}

if($message->photo){
    
$photo = $message->photo;
$file = $photo[count($photo)-1]->file_id;
      $get = bot('getfile',['file_id'=>$file]);
      $patch = $get->result->file_path;
      file_put_contents('photo.png',file_get_contents('https://api.telegram.org/file/bot'.$API_KEY.'/'.$patch));
       
    
bot('sendPhoto',[
'chat_id'=>$reply,
'photo'=>new CURLFILE('photo.png')
]);
}
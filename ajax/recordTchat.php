<?php
session_start();
require_once '../config/config.php';
require_once '../model/Registration.php';
require_once '../model/GetInfos.php';
$register = new Registration();
$getInfo = new GetInfos();
$friend = '';

// Enregistrement du message

$friend_id = htmlspecialchars(trim($_POST['friend_id']));
$data = ['user_id'=>htmlspecialchars(trim($_SESSION['user']['id'])), 
        'friend_id'=>htmlspecialchars(trim($_POST['friend_id'])), 
        'content'=>strip_tags(trim($_POST['content']]));
$register->setTchat($data);

// Restitution des 20 deniers messages
$data = ['user_id'=>htmlspecialchars(trim($_SESSION['user']['id'])), 
        'friend_id'=>htmlspecialchars(trim($_POST['friend_id']]));
        
$result = $getInfo->getTchat($data);
$result = array_reverse ($result);
echo json_encode($result);

<?php
require_once 'model/GetInfos.php';
$getinfo = new GetInfos();
require_once 'model/Registration.php';
$register = new Registration();
$id = $_SESSION['user']['id'];
$info_user = $getinfo->getInfoUser($id);
$inf_d = $getinfo->getUserDefunctList($id);
$info_def = $inf_d->fetchAll();
$nbr = count($info_def);

if (isset($_POST['submit'])){
   $data['user_id'] = $id;
   $data['pseudo'] = isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : $_POST['pseudo'];
   $data['number_road'] = isset($_POST['number_road']) ? htmlspecialchars($_POST['number_road']) : $_POST['number_road'];
   $data['address'] = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : $_POST['address'];
   $data['postal_code'] = isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : $_POST['postal_code'];
   $data['city'] = isset($_POST['city']) ? htmlspecialchars($_POST['city']) : $_POST['city'];
   $register->updateUser($data);
}
require 'view/profil.php';
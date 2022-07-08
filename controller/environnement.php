<?php
require_once 'model/Registration.php';
$register = new Registration();
require_once 'model/GetInfos.php';
$getInfo = new GetInfos();
require_once 'model/GlobalClass.php';
$globalClas = new GlobalClass();

// déclaration des variables
$nbPhotos = '';
$nbComments = '';
$admin_def ='';
$message ='';
$messFile ='';
$friendOk = '';
$tab = array();
$tabFriend = [];

//id du user à l'origine de la fiche du défunt et ami potentiel
$friend_add = $_GET['friend_add']??null;
//id du defunt dans l'environnement
$id_def = $_GET['id']??0;
//id du defunt suite à une recherche
if(!$id_def) {
    $id_def = $_GET['id_def']?? 0;
}
//id d'un commentaire
$idCom = $_GET['idCom']??null;
//id d'une photo
$idPhoto = $_GET['idPhoto']??null;

////////////// Si user connecté et créateur d'une fiche

if(isset($_SESSION['user']['id'])) {

        ////////supprimer une photo de l'environnement utilisateur//////
        if ($idPhoto) {
            $register->deletePhoto($idPhoto);
            $photoFile = 'public/pictures/photos/'.$_SESSION['user']['id'].'/'.$_SESSION['user']['id'].'-'.$idPhoto.'.jpg';
            if (file_exists($photoFile)){
                unlink($photoFile);
            }
        
    /////////supprimer les commentaires associés dans la BBD/////////
            $register->deleteCommentsPhoto($idPhoto);
        }

    ///////////////supprimer un commentaire/////////////////////////
    if ($idCom) {
        $register->deleteComment($idCom);
    }

    ///////////enregistrement d'une photo télécharger //////////////////////
    if (isset($_FILES['file_env']) && $_FILES['file_env']['type']=='image/jpeg' && !empty($_FILES['file_env'])){
        
        $destination = 'public/pictures/photos/'.$_SESSION['user']['id'];
    
        // test dossier existe ou création
        if (!file_exists($destination) && !is_dir($destination)){ 
            mkdir($destination , 0755);
        } 
        
        // test taille fichier
        if ($_FILES['file_env']['size'] > 1024000){
            $_messFile =  '<p class="message">Attention, la limitation de la taille du fichier est de 2Mo</p>';
        }
        
        //enregistre la photo dans la BBD
        $photo_id = $register->setPhoto(['user_id' => $_SESSION['user']['id'],'defunct_id'=> $id_def,'name'=>'']);
        $photo_name = $id_def.'-'.$photo_id.'.jpg';
        move_uploaded_file($_FILES['file_env']['tmp_name'],$destination.'/'.$photo_name);
        $register->updatePhoto(['id' => $photo_id, 'name'=>$photo_name]);
    }
}

///////////Récupération des infos et des photos associé au défunt///////////////
if ($id_def) {
    $defunct_infos = $getInfo->getInfoDefunct(intval($id_def))->fetch();
    $defunct_photos = $getInfo->photoListDefunct(intval($id_def))->fetchAll();
    $user_admin = $getInfo->getUserAdminInfo(intval($id_def));
    $com_list = [];

    /////////récupération des commentaires selon la photo du defunt///////////////
    if(count($defunct_photos)) {
        foreach($defunct_photos as $r) {
            $com_list[$r['id']] = $getInfo->getListComment(intval($r['id']));
        }
    }
        
} else {
    echo 'Cette fiche n\'existe pas';
}
////////////nombre de commentaires et photos ajoutées depuis la dernière connexion
if(isset($_SESSION['user']['id']) && $defunct_infos['user_id'] == $_SESSION['user']['id']){
    $recentComment = $getInfo->getRecentComments($id_def, $_SESSION['user']['last_log'], $_SESSION['user']['id'])->rowCount();
    $recentPhoto = $getInfo->getRecentPhotos($id_def, $_SESSION['user']['last_log'], $_SESSION['user']['id'])->rowCount();
}

//////////////////////ajouter un contact///////////////////////////////////
// liste des amis déjà existant
$friendList = $getInfo->getFriendsList($_SESSION['user']['id']);
foreach ($friendList as $key =>$f){
    // affichage de l'icone Ajouter un contact si non dans la liste
    if ($defunct_infos['user_id'] == $f['friend_id']){
        $friendOk = true;
    }
}
//$_GET['friend_add']<-enregistrement du contact
if ($friend_add){ 
    $register->setFriends(['user_id'=>$_SESSION['user']['id'], 'friend_id'=>intval($friend_add)]);
    $message = 'Nouveau contact enregistré. En attente de confirmation...';  
}

require 'view/environnement.php';
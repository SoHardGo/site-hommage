<?php
$content='';

if (isset($id_def)){
    ob_start(); 
?>
<!--Dossier de téléchargement des photos du defunt sélectionné/-->
    <div class="env_defunct">
        <h3 class="env_name_def" ><?=ucfirst($defunct_infos['firstname']).' '.ucfirst($defunct_infos['lastname']) ?></h3>
        <hr>
        <a class="folder_link" href="" title="Dossier de stockage des photos">
            <div class="folder">
                <img class="img" src="public/pictures/site/folder.png" alt="Dossier de stockage photos">
            </div>
            <div>
                <p>Cliquez sur le Dossier pour telecharger les photos de <?=ucfirst($defunct_infos['firstname']).' '.ucfirst($defunct_infos['lastname']) ?></p>
            </div>
        </a>
        <div  class="photos_list hidden">
            <?php if($defunct_photos) :?>
                 <?php foreach($defunct_photos as $r): ?>
                <div class="min_photo">
                    <img class="img" src="public/pictures/photos/<?=$r['user_id']?>/<?=$r['name'] ?>" alt="<?=$r['name'] ?>">
                    <a download="image_<?=$r['id']?>.jpg" href="public/pictures/photos/<?=$r['user_id'].'/'.$r['name'] ?>"><i class="fas fa-download" title="Telecharger"></i></a>
                </div>
                 <?php endforeach ?>
            <?php else :?>
                <div class="min_nophoto">
                    <p><i class="fas fa-ban"></i>&nbsp;Aucune photos de <?=ucfirst($defunct_infos['firstname']).' '.ucfirst($defunct_infos['lastname']) ?>&nbsp;<i class="fas fa-ban"></i></p>
                </div> 
            <?php endif ?>
        </div>
        <hr>
<!--Nombre de commentaires et photos depuis la dernière connexion-->
        <?php if(isset($_SESSION['user']['id']) && $defunct_infos['user_id'] == $_SESSION['user']['id']) { ?>
        <div class="env_listing">
            <p>Depuis votre dernière connexion :</p>
            <p class="new_photos">Photos ajoutées: <span><?=$recentPhoto?></span></p>
            <p class="new_comments">Commentaires ajoutés: <span><?=$recentComment?></span></p>
        </div>
        <hr>
        <?php } ?>
<!--Ajouter une photo dans le fil de l'environnement utilisateur-->        
        <?php if(isset($_SESSION['user']['id'])) : ?>
        <form method="POST" action="index.php?page=environnement&id=<?=$id_def?>" enctype="multipart/form-data" id="form_env">
                <label for="file_env"></label>
                <input type="file" name="file_env" id="file_env" accept=".jpg, .jpeg, .png">
                <div class="icon_env">
                    <label>Ajouter une photo&emsp;</label>
                    <i class="fas fa-camera camera_env"></i>
                </div>
        </form>
        <?php endif ?>
<!--Liste des nouvelles photos depuis la dernière connexion-->
        <div class="container_environnement">
            <?php foreach($defunct_photos as $r): ?>
                <div class="div_photo">
                    <?php if(isset($_SESSION['user']['last_log']) && isset($r['date_crea']) && $_SESSION['user']['last_log'] < $r['date_crea']): ?>
                        <div class="container_lastP hidden" >
                            <div class="last_photos">
                                <a href="#<?=$r['id']?>">
                                    <img class="img" src="public/pictures/photos/<?=$r['user_id']?>/<?=$r['name']?>" alt="<?=$r['name']?>">
                                </a>
                            </div>
                        </div>
                    <?php endif ?>
                    
<!--Supprimer une photo dont on est l'auteur-->                  
                    <?php if(isset($_SESSION['user']['id']) && isset($r['user_id']) && $_SESSION['user']['id'] == $r['user_id']): ?>
                    <a class="delete_photo" href="index.php?page=environnement&idPhoto=<?=$r['id']?>&id=<?=$id_def?>" title="Supprimer"><b>X</b></a>
                    <?php endif ?>
<!--Affichage des photos-->
                    <div id="<?=$r['id']?>">
                        <img class="img" src="public/pictures/photos/<?=$r['user_id'].'/'.$r['name']?>" alt="<?=$r['name']?>">
                    </div>
<!--Liste des commentaires de la photo + photo de profil miniature des auteurs  du commentaire-->
                    <div class="com_div">
                        <?php foreach($com_list[$r['id']] as $comment): ?>
                         <div class="comment_post">
                            <div class="container_com_user">
                                <div class="profil">
                                    <a class ="env_user_name" title="<?=$comment['user_id']?>">
                                    <?php if(file_exists('public/pictures/users/'.$comment['user_id'].'/'.$comment['profil_user'])) : ?>
                                    <img class="img" src="public/pictures/users/<?=$comment['user_id'].'/'.$comment['profil_user'].'?'.date('s')?>" alt="photo de profil">
                                    <?php else : ?>
                                    <img class="img" src="public/pictures/site/noone.jpg"<?=date('s')?> alt="photo de profil">
                                    <?php endif ?>
                                    </a>
                                </div>
<!--Supprimer un commentaire dont on est à l'origine-->
                                &emsp;<?=$comment['comment']?>
                                <?php if(isset($_SESSION['user']['id']) && $_SESSION['user']['id']== $comment['user_id']): ?>
                                <div class="icon_delete">
                                    <a class ="env_user_name" href="index.php?page=environnement&id=<?=$id_def?>&idCom=<?=$comment['id']?>" title="Supprimer"><i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                                <?php endif ?>
                                
                            </div>
<!--Affichage d'un "New" rouge pour les nouveaux commentaires-->
                            <?php if((isset($_SESSION['user']['last_log']) && isset($comment['date_crea']) && $_SESSION['user']['last_log'] < $comment['date_crea']) && (isset($_SESSION['user']['id']) && isset($comment['user_id']) && $_SESSION['user']['id'] !== $comment['user_id'])): ?>
                            <div class="new_comment">New</div>
                            <?php endif ?>
                        </div>
                        <?php endforeach ?>
                    </div>
<!--Formulaire ajout de commentaire -->
                <?php if(isset($_SESSION['user']['id'])) : ?>
                <form class="comment_env">
                    <input type="text" name="comment" class="comment">
                    <label for="comment">Commenter</label>
                    <input type="hidden" name="id_def" class="id_def" value="<?=$id_def?>">
                    <input type="hidden" name="photo_id" class="photo_id" value="<?=$r['id']?>">
                    <input type="hidden" name="user_id" class="user_id" value="<?=$_SESSION['user']['id']?>">
                    
                </form>
                <?php endif ?>
            </div>
            <?php endforeach ?>
        </div>
    </div>
<?php
    $content = ob_get_clean();
}

require 'template.php';

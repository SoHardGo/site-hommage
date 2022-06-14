<?php
$content='';

if (isset($_SESSION['user']['defunct'])){
    ob_start(); 
    ?>
    <div class="env_defunct">
        <h3 class="env_name_def" ><?=ucfirst($defunct_infos['firstname']).' '.ucfirst($defunct_infos['lastname']) ?></h3>
        <div class="container_environnement">
            <!-- boucle pour récupérer chaque commentaire liés à sa photo-->
            <?php foreach($defunct_photos as $r): ?>
                <div class="div_photo">
                    <img class="img" src="public/pictures/photos/<?=$_SESSION['user']['id'].'/'.$r['name']?>" alt="">
                    <!-- liste des commentaires de la photo -->
                    <div class="com_div">
                        <?php foreach($div_env[$r['id']] as $comment): ?>
                         <div class="comment_post">
                            <div class="container_com_user">
                                <div class="profil"><a class ="env_user_name" title="<?=$_SESSION['user']['lastname'].' '.$_SESSION['user']['firstname']?>">
                                <img class="img" src="public/pictures/users/<?=$_SESSION['user']['id'].'/'.$comment['profil_user'].'?'.date('s')?>" alt="photo de profil"></a></div>
                                &emsp;<?=$comment['comment']?>
                                <div class="icon_delete">
                                    <a class ="env_user_name" href="" title="Supprimer"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                        <?php endforeach ?>
                </div>
                    <!-- ajouter un commentaire à cette photo -->
                    <!-- associer l'utilisateur à ce commentaire -->
                <form class="comment_env">
                    <label for="comment">Commenter</label>
                    <input type="text" name="comment" class="comment">
                    <input type="hidden" name="id_def" class="id_def" value="<?=$id_def?>">
                    <input type="hidden" name="photo_id" class="photo_id" value="<?=$r['id']?>">
                    <input type="hidden" name="user_id" class="user_id" value="<?=$_SESSION['user']['id']?>">
                    <input type="hidden" name="lastname" class="lastname" value="<?=$_SESSION['user']['lastname']?>">
                    <input type="hidden" name="firstname" class="firstname" value="<?=$_SESSION['user']['firstname']?>">

                    <div class="icon_env">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </form>
            </div>
            <?php endforeach ?>
            <!-- fin de la boucle -->
    
            <form method="POST" action="index.php?page=environnement&id=<?=$id_def?>" enctype="multipart/form-data" id="form_env">
                <label for="file_env"></label>
                <input type="file" name="file_env" id="file_env" accept=".jpg, .jpeg, .png">
                <div class="icon_env">
                    <i class="fas fa-camera camera_env"></i>
                </div>
            </form>
        </div>
        
    </div>
    
    <?php
    $content = ob_get_clean();
}

require 'template.php';
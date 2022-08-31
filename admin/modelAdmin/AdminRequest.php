<?php
require_once 'Manage.php';

class AdminRequest extends Manage {
    // Vérification du compte d'administration
    public function verifyAdminAccount(string $admin_user, $admin_pwd) :?array{
        $data = ['admin_id'=>$admin_user];
        $query = "SELECT id, admin_id, admin_pass FROM adminlog WHERE admin_id=:admin_id";
        $result = $this->getQuery($query,$data);
        if ($result->rowCount()) {
            $data = $result->fetch();
            if (password_verify($admin_pwd, $data['admin_pass'])) {
                return $data;
            }
        }
        return null;
    }
    // Ajout d'un nouvel administrateur
    public function setNewAdminAccount(array $data) :void{
        $query ="INSERT INTO  adminlog SET admin_id=:admin_id, admin_pass=:admin_pass";
        $this->getQuery($query,$data);
    }
    
/////////////////////////////////USERS////////////////////////////////////////// 

    // Informations sur tous les utilisateurs
    public function getInfoAllUsers() :array{
        $query = "SELECT id, lastname, firstname, pseudo, email, number_road, address, city, postal_code, password, last_log, online FROM users ORDER BY lastname";
        return $this->getQuery($query)->fetchAll();
    }
    // Informations d'un utilisateur
    public function getInfoOneUser(int $id) :array{
        $data = ['id'=>$id];
        $query = "SELECT id, lastname, firstname, pseudo, email, number_road, address, city, postal_code, password, last_log, online FROM users WHERE id=:id";
        return $this->getQuery($query,$data)->fetch();
    }
    // Mise à jour des informations d'un utilisateur
    public function updateInfoOneUser(array $data) :void{
        $query = "UPDATE users SET lastname=:lastname, firstname=:firstname, pseudo=:pseudo, email=:email, number_road=:number_road, address=:address, city=:city, postal_code=:postal_code WHERE id=:id";
        $this->getQuery($query,$data);
    }
    // Suppression d'un utilisateur
    public function deleteOneUser(int $id) :void{
        $data = ['id'=>$id];
        $query = "DELETE FROM users WHERE id=:id";
        $this->getQuery($query,$data);
    }

    // Supprimer tous les dossiers concernant un utilisateur
     public function supprFolder(int $user_id, string $folder) :void{
        $folderSupp = $folder.$user_id;
        if (is_dir($folderSupp)) {
             $files = scandir($folderSupp);
             foreach ($files as $f) {
               if ($f != '.' && $f != '..') {
                 unlink($folderSupp.'/'.$f);
               }
             }
             rmdir($folderSupp);
        }
    }
    
/////////////////////////////////PHOTOS/////////////////////////////////////////

    // Informations sur toutes les photos
    public function getInfoAllPhotos() :array{
        $query = "SELECT id, name, user_id, defunct_id, date_crea FROM photos ORDER BY name";
        return $this->getQuery($query)->fetchAll();
    }

/////////////////////////////////PRODUITS///////////////////////////////////////
    
    // Information sur tous les produits (cartes & bouquets)
    public function getInfoAllProducts() :array{
        $query = "SELECT id, categories, name, price, info FROM products";
        return $this->getQuery($query)->fetchAll();
    }

/////////////////////////////////CONTACTS///////////////////////////////////////
    
    // Liste des demandes de contacts (messages)
    public function getInfoAllContacts() :array{
        $query = "SELECT id, email, user_id, message, date_crea FROM contact ORDER BY date_crea DESC";
        return $this->getQuery($query)->fetchAll();
    }

/////////////////////////////////FRIENDS////////////////////////////////////////
   
    // Liste des informations sur les demandes d'amis
    public function getInfoAllFriends() : array{
        $query = "SELECT id, user_id, friend_id, date_crea, validate FROM friends ORDER BY user_id";
        return $this->getQuery($query)->fetchAll();
    }
    
////////////////////////////////COMMENTAIRES////////////////////////////////////
    
    // Liste de tous les commentaires
    public function getInfoAllComments() :array{
        $query = "SELECT id, comment, user_id, defunct_id, date_crea, photo_id, profil_user FROM comments ORDER BY date_crea DESC";
        return $this->getQuery($query)->fetchAll();
    }
    
    // Liste des contenus de Tchat
    public function getInfoAllTchats() :array{
        $query = "SELECT id, user_id, content, date_crea, friend_id FROM tchat ORDER BY date_crea DESC";
        return $this->getQuery($query)->fetchAll();
    }
    
/////////////////////////////////ORDERS/////////////////////////////////////////
    
    // Liste des commandes
    public function getInfoAllOrders() :array{
        $query = "SELECT id, lastname, firstname, date_crea, total, lastname_send, tel, email, user_send_id, cards_id, flowers_id FROM orders ORDER BY date_crea DESC";
        return $this->getQuery($query)->fetchAll();
    }

////////////////////////////////DEFUNCTS////////////////////////////////////////
    
    // Liste des défunts
    public function getInfoAllDefuncts() :array{
        $query = "SELECT id, user_id, lastname, firstname, birthdate, death_date, cemetery, city_birth, city_death, postal_code, photo, date_crea FROM defuncts ORDER by id";
        return $this->getQuery($query)->fetchAll();
    }
    // Information d'un défunt
    public function getInfoOneDefunct(int $id) :array {
        $data = ['id'=>$id];
        $query = "SELECT id, user_id, lastname, firstname, birthdate, death_date, cemetery, city_birth, date_crea, city_death, postal_code, photo FROM defuncts WHERE id=:id";
        return $this->getQuery($query, $data)->fetch();
    }
    // Mise à jour des informations d'un défunt
    public function updateInfoOneDefunct(array $data) :void{
        $query = "UPDATE defuncts SET user_id=:user_id, lastname=:lastname, firstname=:firstname, birthdate=:birthdate, death_date=:death_date, cemetery=:cemetery, city_birth=:city_birth, city_birth=:city_death, postal_code=:postal_code WHERE id=:id";
        $this->getQuery($query,$data);
    }
    // Suppression d'un défunt
    public function deleteOneDefunct(int $id) :void{
        $data =['id'=>$id];
        $query = "DELETE FROM defuncts WHERE id=:id";
        $this->getQuery($query,$data);
        $data =['defunct_id'=>$id];
        $query = "DELETE FROM user_admin WHERE defunct_id=:defunct_id";
        $this->getQuery($query,$data);
    }
    // Suppression des photos associées à un défunt
    public function deletePhotosDefunct(int $id) :void{
        $admin = $this->getInfoOneDefunct($id);
        unlink('../public/pictures/users/'.$admin['user_id'].'/photodef'.$id.'.jpg');
        $folder = scandir('../public/pictures/photos/');
        foreach ($folder as $f){
            if ($f != '.' && $f != '..'){
            $files = scandir('../public/pictures/photos/'.$f.'/');
                foreach($files as $fi){
                    if ($fi != '.' && $fi != '..' && substr($fi,0,1) == $id){
                        $folder = '../public/pictures/photos/'.$f.'/'.$fi;
                        unlink($folder);
                    }
                }
            }
        }
    }
    
/////////////////////////////DIVERS/////////////////////////////////////////////

    // Vérification du format des dates 
    public function verifyDateFormat(string $date) :bool{
        $format = DateTime::createFromFormat('Y-m-d', $date);
        if ($format){
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }
    
}
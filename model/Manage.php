<?php

class Manage {
    // Connexion à la base de donnée
    protected function db_connect():object {

            try {
                $db = new PDO('mysql:host='.SERVER.';port=3306;dbname='.BASE.';charset=utf8', LOGIN, PWD);
            }
            catch (PDOException $e) {
                echo '<h3>Site en maintenance...</h3>';
                echo $e->getMessage();
                exit; // ou die();
            }
            return $db;
    }
    // Envoi des requêtes à la base de donnée    
    protected function getQuery($query,$data=[]):object {
            $db = $this->db_connect();
            $stmt = $db->prepare($query);
            $stmt->execute($data);
            return $stmt;
    }
    
    // Récupération du dernier Id de l'enregistrement d'une inscription
    protected function setQueryLastId($query,$data=[]):int {
             $db = $this->db_connect();
             $stmt=$db->prepare($query);
             $stmt->execute($data);
             return $db->lastInsertId();
    }
    // Fonction de routage
    public function router(string $page):string {
        if( in_array ($page, array_keys(ROUTES))) {
            $controller = CONTROLLER_FOLDER.ROUTES[$page];
        } else{
            $controller = CONTROLLER_FOLDER.DEFAULT_ROUTE;
        }
        return realpath($controller);
    }
    // Initialisation d'un jeton pour vérifier la validité d'un formulaire
    public function setToken():string{
        $token = bin2hex(random_bytes(10)); 
        $_SESSION['token'] = $token;
        return $token;
    }
    // Fonction générique pour récupérer toute les informations d'une table selon un Id
    public function getOne(string $table, int $id ){
        $data = ['table' => $table, 'id' => $id];
        $query = "SELECT * FROM table=:table WHERE id=:id";
        $result = $this->getQuery($query,$data);
        return $result->fetch();
    }
    // Fonction générique pour récupérer les informations d'une colonne dans une table
    public function getAll(string $table, string $columns ) :array{
        $data = ['table' => $table, 'columns' => $columns];
        $query = "SELECT columns=:columns FROM table=:table";
        $result = $this->getQuery($query,$data);
        return $result->fetchAll();
    }
    
}

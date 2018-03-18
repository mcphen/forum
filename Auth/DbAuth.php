<?php

class DbAuth{

    /**
     * @var Database
     */
    private $db;
    public function __construct(Database $db){
        $this->db=$db;
    }

    

    /**
    * @param $username
     * @param $mdp
     * @return boolean
     * fonction permettant de s'authentifier
     */
    public function login($username, $password){
        $user=$this->db->prepare('SELECT * FROM users WHERE pseudo=?',[$username],null, true);

        if ($user){
           if ($user->mdp === sha1($password)){
               $_SESSION['authentification']=$user; //insere les donnees de l'utilisateur dans une session
               return true;
           }
        }
        return false;
    }

    /**
     * @return bool
     * retourne la session de l'utilsateur
     */
    public function logged(){
        return isset($_SESSION['authentification']);
    }

    /**
     * @return string
     * lien vers la page inscription
     */
    public function urlSign()
    {
        return 'index.php?p=inscription';
    }

    /**
     * @return string
     * Lien vers la page connexion
     */
    public function urlLogin()
    {
        return 'index.php?p=connexion';
    }

    /**
     * @return string
     * lien vers la page deconnexion
     */
    public function urlLogout()
    {
        return 'index.php?p=deconnexion';
    }

    public function forbidden(){
        header('HTTP/1.0 403 Forbidden');
        die('acces interdit');
    }

    public function notfound(){
        header('HTTP/1.0 404 Not Found');
        die('Page introuvable');
    }

    /**
     * @param $colone
     * @param $execution
     * @param bool $one
     * @return array|bool|int|mixed
     * verification des colonnes existantes
     */
    public function verification($colone, $execution, $one=false)
    {
        $req=$this->db->prepare("SELECT * FROM users WHERE $colone=?", [$execution] );
        if($one){
            return $req;
        }else{
            return count($req);
        }

    }

    public function queryAuth($one=true, $last=NULL){
        $req= $this->db->query("SELECT * FROM users");
        if ($last){
            return $this->db->query("SELECT * FROM users ORDER BY idusers DESC LIMIT 1 ", null, true);
        }else{
            if ($one){
                return $req;
            }else{
                return count($req);
            }
        }

    }

    public function viewAuth($idUser){
        return $this->db->prepare("SELECT * FROM users WHERE idusers=?", [$idUser], null, true);
    }


}
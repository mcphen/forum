<?php

/**
 * Class Database c'est une classe permettant de se connecter à la base de donnée et effectuer les requetes avec les fonctions query et prepare
 */
class Database
{
    private $_dbName;
    private	$_dbUser;
    private	$_dbPass;
    private	$_dbHost;
    private	$_pdo;

    /**
     * Database constructor.
     * @param $_dbName nom de la base de données
     * @param string $_dbUser
     * @param string $_dbPass
     * @param string $_dbHost
     */
    public function __construct($_dbName, $_dbUser= 'root', $_dbPass= '', $_dbHost='Localhost'){

        $this->_dbName=$_dbName;
        $this->_dbUser=$_dbUser;
        $this->_dbPass=$_dbPass;
        $this->_dbHost=$_dbHost;

    }

    /**
     * @return PDO
     */
    private function getPDO(){

        if($this->_pdo=== null){
            $_pdo= new PDO('mysql:dbname=forum;host=localhost;charset=utf8', 'root', '');
            $_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_pdo=$_pdo;
        }

        return $this->_pdo;

    }

    /**
     * @param $statement
     * @param null $class_name
     * @param bool $one
     * @return array|mixed|PDOStatement
     */

    public function query($statement, $class_name=null, $one=false){

        $req=$this->getPDO()->query($statement);
        if(
            strpos($statement, 'UPDATE')===0 ||
            strpos($statement, 'INSERT')===0 ||
            strpos($statement, 'DELETE')===0
        ){
            return $req;
        }
        if ($class_name===null){
            $req->setFetchMode(PDO::FETCH_OBJ) ;
        }else{

            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }





        if($one){
            $datas= $req->fetch();
        }else{
            $datas= $req->fetchAll();
        }
        return $datas;
    }

    /**
     * @param $statement
     * @param $attributes
     * @param null $class_name
     * @param bool $one
     * @return array|bool|mixed
     */
    public function prepare($statement, $attributes, $class_name=null ,$one=false){
        $req=$this->getPDO()->prepare($statement);
        $res= $req->execute($attributes);
        if(
            strpos($statement, 'UPDATE')===0 ||
            strpos($statement, 'INSERT')===0 ||
            strpos($statement, 'DELETE')===0
        ){
            return $res;
        }
        if ($class_name===null){
            $req->setFetchMode(PDO::FETCH_OBJ) ;
        }else{

            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }

        if($one){
            $datas= $req->fetch();
        }else{
            $datas= $req->fetchAll();
            $row=$req->rowCount();
        }

        return $datas;

    }

    /**
     * @return string
     * recupere la clé primaire
     */
    public function lastInsertId(){
        return $this->getPDO()->lastInsertId();
    }

}



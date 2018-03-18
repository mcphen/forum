<?php

/**
 * @param $length
 * @return mixed
 * cette fonction recupere la derniere valeur d'un tableau apres l'avoir separer par "/"
 */

function str_explode($length)
{
    $var= explode('/', $length);
    $keys = array_keys($var);
    $last_key = $keys[count($keys)-1];
    return $var[$last_key];
}

function logged_only()
{
    if(session_status()== PHP_SESSION_NONE){ //si on a pas de session active alors
        session_start();
    }

    if (!isset($_SESSION['authentification'])){
        header('Location: index.php?p=connexion');
        exit();
    }
}




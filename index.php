<?php

setlocale(LC_TIME, 'fr');
if(session_status()== PHP_SESSION_NONE){ //si on a pas de session active alors
    session_start();

}


require_once 'class/Database.php'; // on fait appel au fichier contenant la classe Database

require_once 'app/table/Table.php';
require_once 'app/table/Categories.php'; // on fait appel au fichier contenant la classe Categories

require_once 'app/table/Topics.php'; //on fait appel au fichier contenant la classe Topics

require_once 'Auth/DbAuth.php'; // on fait appel au fichier contenant la classe DbAuth

require_once 'app/table/Message.php';

require_once "JBBCode/Parser.php";

require_once "app/functions.php";
/**
 * @param $app instanciant de la class Database
 */
$app= new Database('forum');

/**
 * @param $cat instanciant de la class Categories
 */
$cat= new Categories($app);
/**
 * @param $topics instanciant de la class Topics
 */
$topics=new Topics($app);

$auth= new DbAuth($app);

$message= new Message($app);
$auth->logged();
if (isset($_GET['p'])){
    $page=$_GET['p'];
}else{
    $page='home';
}

ob_start();

if ($page==='home'){
    require'/pages/posts/home.php';
}elseif ($page==='posts.categories'){
    require '/pages/posts/categories.php';
}elseif($page==='posts.souscategories'){
    require '/pages/posts/souscategories.php';
}elseif ($page==='posts.categoriestopics') {
    require '/pages/posts/nouveau_topics.php';
}elseif ($page==='inscription'){
    require '/pages/user/sign.php';
}elseif($page==='connexion'){
    require '/pages/user/login.php';
}elseif ($page==='deconnexion'){
    require '/pages/user/logout.php';
}elseif ($page==='view.topics'){
    require '/pages/posts/view_topics.php';
}elseif ($page==="posts.deleteTopics"){
    require '/pages/posts/deleteTopics.php';
}elseif ($page==='posts.deleteMsg'){
    require '/pages/posts/deleteMessage.php';
}elseif ($page==='edit.profil'){
    require '/pages/user/editprofil.php';
}elseif ($page==='profil'){
    require '/pages/user/profil.php';
}elseif ($page==='envoie.msg'){
    require '/pages/user/envoieMsg.php';
}elseif ($page==='messagerie'){
    require '/pages/user/messagerie.php';
}elseif ($page==='view.message'){
    require '/pages/user/view.messagerie.php';
}elseif ($page==='notification'){
    require '/pages/user/notification.php';
}else{
    $auth->notfound();
}
$content=ob_get_clean();
require '/pages/templates/default.php';
<?php
session_start();


require_once 'class/Database.php'; // on fait appel au fichier contenant la classe Database
$app= new Database('forum');
require_once 'Auth/DbAuth.php'; // on fait appel au fichier contenant la classe DbAuth
$auth= new DbAuth($app);
require_once 'app/table/Categories.php'; // on fait appel au fichier contenant la classe Categories
$cat= new Categories($app);
require_once 'app/table/Table.php';


require_once 'app/table/Topics.php'; //on fait appel au fichier contenant la classe Topics

require_once 'Auth/DbAuth.php'; // on fait appel au fichier contenant la classe DbAuth

require_once 'app/table/Message.php';

require_once "JBBCode/Parser.php";
$topics=new Topics($app);



$message= new Message($app);
$auth->logged();

if (!isset($_SESSION['authentification'])){
    $auth->forbidden();
}else{

    if($_SESSION['authentification']->droitadmin==0){
        $auth->forbidden();
    }
}


if (isset($_GET['p'])){
    $page=$_GET['p'];
}else{
    $page='home';
}


ob_start();

if ($page==='home'){
    require '/pages/admin/posts/index.php';
}elseif ($page==='posts.cat'){
    require '/pages/admin/posts/cat.php';
}elseif ($page==='list.users'){
    require '/pages/admin/users/index.php';
}elseif ($page==='add.cat'){
    require '/pages/admin/posts/addCat.php';
}elseif ($page==='posts.delete'){
    require '/pages/admin/posts/deleteCat.php';
}elseif ($page==='add.user'){
    require '/pages/admin/users/adduser.php';
}elseif ($page==='posts.deleteSub'){
    require '/pages/admin/posts/deleteSubCat.php';
}elseif ($page==='statistique'){
    require '/pages/admin/statistique.php';
}else{
    $auth->notfound();
}
$content=ob_get_clean();
require '/pages/templates/default.php';
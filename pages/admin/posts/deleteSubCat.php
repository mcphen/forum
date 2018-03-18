<?php
$table=new Table($app, 'f_sous_categories');
if (!empty($_POST)){
    $result= $table->delete($_POST['id'],'idf_sous_categories');
    $_SESSION['flash']['success']="La sous catégorie a bien été supprimée";
    header("Location: ?p=posts.cat&categorie=".$_GET['categorie']);
    exit();



}

?>
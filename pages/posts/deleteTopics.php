<?php
$table=new Table($app, 'f_topics');
if (!empty($_POST)){
    $result= $table->delete($_POST['id'],'idf_topics');
    $_SESSION['flash']['success']="Le topics a bien été supprimé";
    header('Location: index.php');
    exit();



}

?>


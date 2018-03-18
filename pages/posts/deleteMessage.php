<?php
$table=new Table($app, 'f_messages');
if (!empty($_POST)){
    $result= $table->delete($_POST['id'],'idf_messages');
    $_SESSION['flash']['success']="Le commentaire a bien été supprimé";
    header('Location: index.php?p=view.topics&topic='.$_GET['topic']);
    exit();



}

?>
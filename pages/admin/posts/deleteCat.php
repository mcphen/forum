<?php
$table=new Table($app, 'f_categories');
if (!empty($_POST)){
    $result= $table->delete($_POST['id'],'idf_categories');
    $_SESSION['flash']['success']="La catégorie a bien été supprimée";
    header('Location: admin.php');
    exit();



}

?>


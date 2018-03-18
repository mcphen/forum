<?php
$insertTable=new Table($app,'users');
$insertTable->update($_SESSION['authentification']->idusers, 'idusers',[
   ' viewEtatUser'=>0,
]);
unset($_SESSION['authentification']);
$_SESSION['flash']['success']="Vous êtes maintenant déconnecté";
header('Location: index.php?p=connexion');

?>
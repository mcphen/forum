<?php
$q=$auth->viewAuth($_GET['auth']);
$errors=[];
$url=$_GET['auth'];
$insertTable=new Table($app,'f_messageries');
$id=$_SESSION['authentification']->idusers;
$datenow=date('Y-m-d H:i:s');
if (isset($_POST['envoie'])){
    if (!empty($_POST['contenu'])){
        $contenu=htmlspecialchars_decode($_POST['contenu']);
        $msg=nl2br($contenu);
        $vartext=str_replace('\n', '<br>', $msg);
        $objet=htmlspecialchars_decode($_POST['objet']);
        $insertTable->create([
            'id_expediteur' =>$id,
            'id_destinataire'=>$_POST['destinataire'],
            'contenuf_messageries'=>$vartext,
            'objetf_messageries'=>$objet,
            'datef_messageries'=>$datenow,
            'etatf'=>0
        ]);
        $_SESSION['flash']['success']="Le message a bien été envoyé";
        header("Location: index.php?p=profil&auth=$url");
        exit();
    }else{
        $errors['noenvoie']="Veuillez saisir un message à envoyer";
    }
}
?>

<!--
        FIL D'ARIANE une aide à la navigation sous forme de signalisation de la localisation du lecteur dans un document
        (très souvent, une page d’un site web). Leur but est de donner aux utilisateurs un moyen de garder une trace de leur emplacement
        à l'intérieur de programmes, documents ou pages Web.
-->

<section>
    Vous êtes ici : <a href="index.php"> FORUM</a>   <img src="img/icon/next.png" width="13px" alt="next"> <strong>Envoie message</strong>
    <hr>
</section>
<form class="form-horizontal" method="post">
    <div class="form-group">
        <label class="control-label col-sm-2" for="objet">Objet:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="objet" id="objet" placeholder="Enter votre objet">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="destinataire">Destinataire:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="destinataire" value="<?= $q->pseudo ?>" disabled >
            <input type="hidden" name="destinataire" class="form-control" id="destinataire" value="<?= $q->idusers ?>" >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd">Message:</label>
        <div class="col-sm-10">
            <textarea name="contenu"></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default" name="envoie">Envoyer</button>
        </div>
    </div>
</form>
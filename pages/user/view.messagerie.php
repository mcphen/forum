<?php
$req=$app->prepare("SELECT * FROM f_messageries INNER JOIN users ON f_messageries.id_expediteur=users.idusers WHERE idf_messageries=?", [$_GET['idmsg']], null, true);
$insertTable=new Table($app,'f_messageries');
$insertTable->update($req->idf_messageries, 'idf_messageries',[
    'etatf'=>1
]);
$id=$_SESSION['authentification']->idusers;
$datenow=date('Y-m-d H:i:s');
if (isset($_POST['envoie'])){
    if (!empty($_POST['contenu'])){
        $contenu=htmlspecialchars_decode($_POST['contenu']);
        $objet="Re : ".$req->objetf_messageries;

        $insertTable->create([
            'id_expediteur' =>$id,
            'id_destinataire'=>$req->id_expediteur,
            'contenuf_messageries'=>$contenu,
            'objetf_messageries'=>$objet,
            'datef_messageries'=>$datenow,
            'etatf'=>0
        ]);
        $_SESSION['flash']['success']="Le message a bien été envoyé";
        header("Location: index.php?p=profil&auth=$id");
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
    Vous êtes ici : <a href="index.php"> FORUM</a> <img src="img/icon/next.png" width="13px" alt="next"> <a href="index.php?p=messagerie&auth=<?= $req->id_destinataire ?>"> Boite de repection</a>   <img src="img/icon/next.png" width="13px" alt="next"> <strong>Messagerie</strong>
    <hr>
</section>
<h2><?= $req->objetf_messageries ?></h2>
<hr style="border: 0.5px solid gray">
<section class="profil">
    <section class="profil_auteur">
        <div id="avatar" style="padding: 15px">
            <img src="membre/avatar/<?= $req->avatar ?>" alt="avatar" width="50px" height="50px">
        </div>
    </section>
    <section class="commentaires_propos" style="width:100%; ">
        <h1><?= $req->pseudo ?></h1>
        <p>
            <?= $req->contenuf_messageries ?>
        </p>
        <hr style="border: 0.5px solid gray">
        <form action="" method="post">
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Repondre:</label>
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

    </section>
</section>

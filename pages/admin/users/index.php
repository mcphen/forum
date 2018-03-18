<?php $title="Liste des utilisateur"; ?>

<section>
    Vous êtes ici : <a href="admin.php"> Administrateur</a> / <strong>Liste des utilisateurs</strong>
    <hr>
</section>
<a href="?p=add.user" class="btn btn-success">Ajouter un utilisateur</a>
<table id="table">
    <thead>
    <tr>
        <td>ID</td>
        <td>Pseudo</td>
        <td>Sujets crées</td>
        <td>Messages postés</td>
        <td>Privilège</td>
        <td>Action</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($auth->queryAuth() as $v):?>
        <tr>
            <td><?= $v->idusers; ?></td>
            <td><?= $v->pseudo; ?></td>
            <td><?= $topics->counttopics($v->idusers)->nbretopics; ?></td>
            <td><?= $message->countmsg($v->idusers)->nbremsg; ?> </td>
            <td><?php if($v->droitadmin==0){
                echo "simple utilisateur";
                }elseif($v->droitadmin==1){
                echo "administrateur";
                }else{
                    echo "modérateur";
                }

                ?></td>
            <td>
                <a href="#"><img src="img/icon/edit.ico" width="20px"> Modifier ses privilèges </a>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="?p=statistique"> les stats</a>
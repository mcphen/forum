<?php
logged_only();
$q=$topics->messagerie($_GET['auth']);

?>
<!--
        FIL D'ARIANE une aide à la navigation sous forme de signalisation de la localisation du lecteur dans un document
        (très souvent, une page d’un site web). Leur but est de donner aux utilisateurs un moyen de garder une trace de leur emplacement
        à l'intérieur de programmes, documents ou pages Web.
-->

<section>
    Vous êtes ici : <a href="index.php"> FORUM</a> <img src="img/icon/next.png" width="13px" alt="next"> <strong>Boite de repection  </strong>
    <hr>
</section>
<table id="table">
    <thead>
        <td>Expediteur</td>
        <td>Contenu</td>
        <td>Date</td>
    </thead>
    <tbody>
        <?php foreach ($q as $v): ?>
    <tr <?= ($v->etatf==0)?'style="color: #337ab7"':''  ?> >
        <td><a href="?p=view.message&idmsg=<?= $v->idf_messageries ?>" <?= ($v->etatf==1)?'style="color: black"':''  ?>  ><?= $v->pseudo ?></a></td>
        <td><?php $valeur=$v->objetf_messageries."-".$v->contenuf_messageries; echo substr($valeur, 0, 200); ?></td>
        <td>
            <?= strftime("%A %d %B %Y",strtotime($v->datef_messageries )); ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php
logged_only();
$req=$app->prepare("SELECT * FROM f_notifications 
                    INNER JOIN f_messages 
                    ON f_messages.idf_messages=f_notifications.idf_messages
                    INNER JOIN users
                    ON f_messages.id_posteur=users.idusers
                    INNER JOIN f_topics 
                    ON f_topics.idf_topics=f_messages.idf_topics
                    WHERE f_notifications.idusers=? ORDER BY idf_notifications DESC",[$_GET['auth']]);

?>
<!--
        FIL D'ARIANE une aide à la navigation sous forme de signalisation de la localisation du lecteur dans un document
        (très souvent, une page d’un site web). Leur but est de donner aux utilisateurs un moyen de garder une trace de leur emplacement
        à l'intérieur de programmes, documents ou pages Web.
-->

<section>
    Vous êtes ici : <a href="index.php"> FORUM</a>  <img src="img/icon/next.png" width="13px" alt="next"> <strong>Notifications</strong>
    <hr>
</section>
<?php foreach ($req as $value): ?>
    <?php if($_SESSION['authentification']->idusers!=$value->idusers):?>
        <section <?= ($value->lu==0)?' style="background-color: lightgray"':'' ?> id="notif" >
            <p style="margin-left: 20px; padding: 10px;">
                <a href="?p=view.topics&notif=<?=$value->idf_notifications ?>&topic=<?= $value->idf_topics ?>#<?= $value->idf_messages ?>" style="color: black"><img src="membre/avatar/<?= $value->avatar ?>" alt="avatar" width="50px">
                    <strong><?= $value->pseudo ?></strong> a commenté le sujet <strong><?= $value->sujet ?></strong></a></p>
        </section>
        <?php endif; ?>
<?php endforeach; ?>

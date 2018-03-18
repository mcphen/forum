<?php
$url=intval($_GET['auth']);
$q=$auth->viewAuth($url);

if($q==false){
    $_SESSION['flash']['danger']="Cet utilisateur n'existe pas";
    header("Location: index.php");
    exit();
}
?>

<!--
        FIL D'ARIANE une aide à la navigation sous forme de signalisation de la localisation du lecteur dans un document
        (très souvent, une page d’un site web). Leur but est de donner aux utilisateurs un moyen de garder une trace de leur emplacement
        à l'intérieur de programmes, documents ou pages Web.
-->

<section>
    Vous êtes ici : <a href="index.php"> FORUM</a>  <img src="img/icon/next.png" width="13px" alt="next"> <strong><?= $q->pseudo ?></strong>
    <hr>
</section>
<?php if (isset($_SESSION['authentification'])):?>
<?php if ($_SESSION['authentification']->idusers==$q->idusers): ?>
    <section style="float: right">
        <a href="?p=edit.profil&auth=<?= $_GET['auth'] ?>" class="btn btn-primary">Modifier</a>
    </section>
<?php endif; ?>
<?php endif;?>
<section class="profil">
    <section class="profil_auteur">
        <div id="avatar" style="padding: 15px">
            <img src="membre/avatar/<?= $q->avatar ?>" alt="avatar" width="100px" height="100px">
        </div>
        <!-- Envoie du message à un autre utilisateur -->
        <?php if (isset($_SESSION['authentification'])):?>
            <?php if ($_SESSION['authentification']->idusers!=$q->idusers): ?>
                <div>
                    <a style="color: black" href="?p=envoie.msg&auth=<?= $_GET['auth'] ?>"> <img src="img/icon/mail1.png" width="30px" alt="">Envoyer un message</a>
                </div>

            <?php endif; ?>
        <?php endif;?>
        <!-- Information site web -->
        <?php if (!empty($q->siteweb)): ?>
            <div style="margin-bottom: 1.5px">
                <a href="<?=  $q->siteweb ?>"  target="_blank" style="color: black"><img src="img/icon/www.png" width="30px" alt="" ><?=  $q->siteweb ?></a>
            </div>
        <?php endif; ?>
        <!-- Information facebook -->
        <?php if (!empty($q->facebook)): ?>
            <div  style="margin-bottom: 1.5px">
                <a  href="<?=  $q->facebook ?>" target="_blank" style="color: black" ><img src="img/icon/fbook.png" width="30px" alt="">
                    <?php
                    $re=str_explode($q->facebook);
                    echo substr($re,0,15);
                    if (strlen($re)>15){
                        echo '...';
                    }
                    ?></a>
            </div>
        <?php endif; ?>
        <!-- Information linkedln -->
        <?php if (!empty($q->linkedln )): ?>
            <div style="margin-bottom: 1.5px">
                <a href="<?=  $q->linkedln  ?>" style="color: black" target="_blank"><img src="img/icon/linkedin.png" width="30px" alt="">
                    <?php
                    $re=str_explode($q->linkedln);
                    echo substr($re,0,15);
                    if (strlen($re)>15){
                        echo '...';
                    }
                    ?>
                </a>
            </div>
        <?php endif; ?>
        <!-- Information twitter -->
        <?php if (!empty($q->twitter)): ?>
            <div style="margin-bottom: 1.5px" id="lienext">
                <a href="<?=  $q->twitter ?>" style="color: black" target="_blank"> <img src="img/icon/tweet.png" width="30px" alt="">
                    <?php
                    $re=str_explode($q->twitter);
                    echo substr($re,0,15);
                    if (strlen($re)>15){
                        echo '...';
                    }
                    ?>
                </a>
            </div>
        <?php endif; ?>
    </section>
    <section class="auteur_propos" style="width:100%; margin-left: 15px">
        <section style="display: flex">
            <?php if($q->viewEtatUser==1):?>
                <div id="cercle"></div>
            <?php endif; ?>
            <div><h1><?= $q->pseudo ?>  </h1></div>
        </section>

        <section>
            <?php if (!empty($q->biographie)): ?>
                <div>
                    <div style="background:#ccc; opacity: 0.8; ">
                        <h2>Biographie</h2>
                    </div>

                    <p><?=  $q->biographie ?></p>
                </div>
            <?php endif; ?>
        </section>
        <section style="background:#ccc; opacity: 0.8; padding:5px">
            <h2>Statistique</h2>
            Date d'inscription : <?= utf8_encode(strftime("%A %d %B %Y",strtotime($q->dateInscription ))); ?><br>
            Nombre de commentaires postés : <?= $message->countmsg($url)->nbremsg; ?><br>
            Nombre de sujet crée : <?= $topics->counttopics($url)->nbretopics; ?><br>
        </section>
    </section>
</section>



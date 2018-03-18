<?php
$datenow=date('Y-m-d H:i:s');
$tmsg=new Table($app, "f_messages");
$tnotif=new Table($app, "f_notifications");
$g=1;
$url=intval($_GET['topic']);
$t=$app->prepare("SELECT * FROM f_topics WHERE idf_topics=?",[$url]);
$cc= count($t);
if($cc==0){ //si le topic n'existe pas dans la base de données
    $_SESSION['flash']['danger']="Ce topic n'existe pas";
    header("Location: index.php");
    exit();
}

if (isset($_GET['notif'])){
    $app->prepare("UPDATE f_notifications SET lu=? WHERE idf_notifications=?",[$g, $_GET['notif']]);
}
/** @var  $parser
 *Parser le code
 */
$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
$parser->addBBCode("quote", '<blockquote>{param}</blockquote>');
$parser->addBBCode("code", '<code>{param}</code>', false, false, 1);

$n=$querySuivi=$app->prepare("SELECT * FROM f_suivis INNER JOIN users ON users.idusers=f_suivis.id_users WHERE idf_topics=?", [$_GET['topic']]); //requete pour afficher les utilisateurs qui suivent ce topics

if(isset($_POST['reponse'])){
    if (!empty($_POST['message']))
    {
        $message=htmlspecialchars($_POST['message']);
        $msg=nl2br($message);
        $vartext=str_replace('\n', '<br>', $msg);



        $rqst= $tmsg->create([
            'idf_topics'     => $_GET['topic'],
            'id_posteur'    => $_SESSION['authentification']->idusers,
            'dateposte'     => $datenow,
            'dateEdition'   => NULL,

            'contenuf_messages'=>$vartext
        ]);
        $idrqst= $app->lastInsertId();
        foreach ($n as $c=>$val){
            if ($_SESSION['authentification']->idusers==$val->idusers){
                $tnotif->create([
                    'idusers' =>$val->idusers,
                    'idf_messages'=>$idrqst,
                    'lu'=>1
                ]);
            }else{
                $tnotif->create([
                    'idusers' =>$val->idusers,
                    'idf_messages'=>$idrqst,
                    'lu'=>0
                ]);
            }


        }
        header('Location: index.php?p=view.topics&topic='.$_GET['topic'].'#'.$idrqst);

    }
}
$tresolu=new Table($app, 'f_topics');
if (isset($_POST['resolu'])){
    $res=1;
    $tresolu->update($_GET['topic'],'idf_topics',[
           'resolu'     =>$res

    ]);
    header('Location: index.php?p=view.topics&topic='.$_GET['topic']);
}

if (isset($_POST['bloque'])){
    $res=1;
    $tresolu->update($_GET['topic'],'idf_topics',[
        'bloque'     =>$res

    ]);
    header('Location: index.php?p=view.topics&topic='.$_GET['topic']);
}

/*
$i= count($n);

while($i!=-1){
    var_dump($n[$i]->idusers) ;
    $i--;
}*/

if(isset($_SESSION['authentification'])){
    $utilisateur=$_SESSION['authentification']->idusers;


    $tfollow=new Table($app, 'f_suivis');
    if (isset($_POST['follow'])){
        $tfollow->create([
                'id_users'=>$utilisateur,
                'idf_topics'=>$_POST['idf_topics']
        ]);
        header('Location: index.php?p=view.topics&topic='.$_GET['topic']);

    }
    $url=$_GET['topic'];
    if (isset($_POST['unfollow'])){
        $app->prepare("DELETE FROM f_suivis WHERE id_users=? AND idf_topics=?", [$utilisateur, $url]);
        header('Location: index.php?p=view.topics&topic='.$_GET['topic']);
    }
    $vSuivi=$message->f_suivis($utilisateur, $_GET['topic']);
}
$querySubCat=$topics->joinCats($_GET['topic']);
foreach ($querySubCat as $q){}
$title=$q->sujet.' par '.$q->pseudo;
?>

<!--
        FIL D'ARIANE une aide à la navigation sous forme de signalisation de la localisation du lecteur dans un document
        (très souvent, une page d’un site web). Leur but est de donner aux utilisateurs un moyen de garder une trace de leur emplacement
        à l'intérieur de programmes, documents ou pages Web.
-->

<section>
    Vous êtes ici : <a href="index.php"> FORUM</a>  <img src="img/icon/next.png" width="13px" alt="next"> <a href="<?= $cat->UrlCat($q->idf_categories) ?>"><?= strtoupper($q->nomf_categories) ?></a>  <img src="img/icon/next.png" width="13px" alt="next"> <a href="<?= $cat->UrlSubCat($q->idf_sous_categories) ?>"><?= $q->nomf_sous_categories ?></a> <img src="img/icon/next.png" width="13px" alt="next"> <strong><?= $q->sujet ?></strong>
    <hr>
</section>


<section class="profil"> <!-- formulaire pour suivre le sujet -->
    <section class="profil_auteur" style="width:60px"><img src="img/icon/follow.png" width="50px" alt=""></section>
    <section class="commentaires_propos">
        <?php if(isset($_SESSION['authentification'])):?>
        <?php if($vSuivi==0): ?> <!-- Si le sujet n'est pas suivi par l'utilisateur connecté -->
            <section>
                <form action="" method="post">

                    <input type="hidden" value="<?= $q->idf_topics; ?>" name="idf_topics" >
                    <button class="label label-primary" name="follow" style="margin-bottom: 7px" >Suivre ce sujet</button>


                </form>
            </section>
        <?php else: ?> <!-- sinon -->
            <section><!-- formulaire pour ne plus suivre le sujet -->
                <form action="" method="post">

                    <input type="hidden" value="<?= $q->idf_topics; ?>" name="idf_topics" >
                    <button class="label label-primary" name="unfollow" style="margin-bottom: 7px">Ne plus suivre ce sujet</button>

                </form>
            </section>
        <?php endif; ?>
        <?php endif; ?>
        <section>Ce topic est suivi par : <?php
            $souscategories='';
            if (!empty($n)){
                foreach ($n as $vec){
                    $souscategories.= '<a href="index.php?p=profil&auth='.$vec->idusers.'">'.$vec->pseudo.'</a> , ';
                }
                echo $souscategories=substr($souscategories, 0,-3);
            }else{
                echo "Personne";
            }



            ?></section>
    </section>


</section>
<?php if (isset($_SESSION['authentification'])): ?> <!-- Si l'utilisateur est connecté -->
        <?php if($_SESSION['authentification']->idusers==$q->idusers AND $q->resolu==0): ?><!-- Si l'ulisateur connecté est celui qui a crée le sujet et ce sujet est non résolu alors: -->
            <form method="post">
                <button class="btn btn-default" name="resolu">Marquer ce sujet comme résolu</button>
            </form>
        <?php endif;?>
    <?php if($_SESSION['authentification']->droitadmin!=0  AND $q->bloque==0): ?><!--Si l'utilisateur connecté n'est pas un simple utilisateur et que le sujet n'est pas fermé alors -->
        <form method="post">
            <button class="btn btn-default" name="bloque" style="margin-bottom: 5px">Fermer ce sujet</button>
        </form>
    <?php endif;?>
<?php endif; ?>
    <?php if($q->bloque==1): ?>
        <section class="ferme"><img src="img/icon/cadena.png" width="25px" alt="sujet_ferme"> Ce sujet a été fermé</section> <!-- message lorsque le sujet est fermé -->
    <?php else: ?>
        <?php if($q->resolu==1): ?>
            <section class="resolu">Ce topic a été résolu</section><!-- message lorsque le sujet est résolu -->
        <?php endif;?>
    <?php endif;?>
<!-- titre du sujet -->
<h1> <?= $q->sujet ; ?></h1>
<section id="commentaires">
    <section id="commentaires_auteur">
        <?php if($q->viewEtatUser==1):?>
            <div id="cercle1"></div>
        <?php endif; ?>
        <strong> <a href="index.php?p=profil&auth=<?=$q->idusers ?>"><?= $q->pseudo; ?></a></strong>
        <div></div>
        <img src="membre/avatar/<?= $q->avatar ?>" alt="avatar" width="100px" height="100px">
    </section>
    <section id="commentaires_propos" style="width:100%">
        <em style="display: block; width: 100%; border-bottom: 1px solid #4e4e4e">
            Sujet: <?=$q->sujet.'  '?>
            <img src="img/icon/horloge.png" width="12px" alt="horloge">
            <?= utf8_encode(strftime("%A %d %B %Y:%H:%M:%S ",strtotime($q->datecreation ))); ?>

        </em>
        <p class="message markdown-body"><?php
            $parser->parse($q->contenuf_topics);

            echo $parser->getAsHTML();

            ?></p>
    </section>
</section>

<!-- Affiche tous les commentaires relatifs au sujet -->
<?php   foreach ($message->allMessage($_GET['topic'], true) as $msg):  ?>
    <hr style="border: 1.5px solid #4e4e4e">
    <section id="<?= $msg->msg  ?>">
        <section class="commentaires">

            <section class="commentaires_auteur"><!-- information sur le posteur de commentaire -->
                <?php if($msg->etatconnect==1):?>
                    <div id="cercle1"></div>
                <?php endif; ?>
                <strong> <a href="index.php?p=profil&auth=<?=$msg->codePerso ?>"><?= $msg->login; ?></a></strong>
                <div></div>
                <img src="membre/avatar/<?= $msg->photo ?>" alt="avatar" width="100px" height="100px">
            </section>
            <section class="commentaires_propos" style="width:100%">

                <em style="display: block; width: 100%; border-bottom: 1px solid #4e4e4e"> <!-- information sur la date du posteur -->
                    Sujet: Re: <?=$q->sujet.'  '?><img src="img/icon/horloge.png" width="12px" alt="horloge">
                    <?= utf8_encode(strftime("%A %d %B %Y:%H:%M:%S ",strtotime($msg->posteddate ))); ?>
                    <?php if (isset($_SESSION['authentification'])): ?> <!-- si l'utilisateur est connecté -->

                        <?php if ($_SESSION['authentification']->droitadmin!=0): ?><!-- si l'utilisateur connecté est l'admin ou le moderateur alors il peut supprimer le post -->
                            <span style="float: right; width=100%">
                                 <form action="?p=posts.deleteMsg&topic=<?=$_GET['topic'] ?>" method="post" style="display: inline">
                                <input type="hidden" name="id" value="<?=  $msg->msg; ?>">
                                <button class="btn btn-default"  type="submit" onclick="return confirm('En supprimant ce message, vous supprimerez toute les informations liée à cet dernier \n Etes vous sûr de valider?');" ><img src="img/icon/delete1.ico" width="20px"> supprimer</button>

                            </form>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </em>

                <p  style="padding: 10px">
                    <?php
                    $parser->parse($msg->msgcontent);
                    echo $parser->getAsHTML();
                    ?>
                </p>
            </section>
        </section>
    </section>


<?php endforeach; ?>
<?php if (isset($_SESSION['authentification']) ): ?><!-- Si l'utilisateur est connecté -->
    <?php if($q->bloque==0) : ?><!-- Si  le sujet n'est pas bloqué -->
    <section style="margin-top: 20px; margin-bottom: 10px">

        <h5>Laisser un commentaire</h5>
        <form method="post">
            <textarea id="wysibb" name="message" style="width: 100%"></textarea>
            <button name="reponse" type="submit">Poster ma réponse</button>
        </form>
    </section>
    <?php endif; ?>
<?php else: ?>
    <section class="alert alert-danger" style="margin-top: 20px; margin-bottom: 10px">
        <a href="<?= $auth->urlLogin() ?>">Veuillez vous connecter pour poster un commentaire</a>
    </section>
<?php endif; ?>

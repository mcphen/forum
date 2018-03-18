<?php
$url=intval($_GET['cat']);
$rt=count($cat->prepareCategories($url));
if($rt==0){ //si le topic n'existe pas dans la base de données
    $_SESSION['flash']['danger']="Cette catégorie n'existe pas";
    header("Location: index.php");
    exit();
}
$queryCat=$cat->prepareCategories($_GET['cat']);
foreach ($queryCat as $q){

};
$title=$q->nomf_categories;
?>

<!--
        FIL D'ARIANE une aide à la navigation sous forme de signalisation de la localisation du lecteur dans un document
        (très souvent, une page d’un site web). Leur but est de donner aux utilisateurs un moyen de garder une trace de leur emplacement
        à l'intérieur de programmes, documents ou pages Web.
-->

<section>
    Vous êtes ici : <a href="index.php"> FORUM</a> <img src="img/icon/next.png" width="13px" alt="next">  <strong><?= $q->nomf_categories ?></strong>
    <hr>
</section>
<?php if (isset($_SESSION['authentification'])): ?>
    <a class="btn btn-default" href="<?= $topics->urlCatTopic($_GET['cat']); ?>" ><span class="glyphicon glyphicon-pencil"></span> Créer un nouveau topic</a>
<?php endif; ?>

<?php if ($topics->countSubTopics($_GET['cat'], false)!=0 ): ?>
<table id="table">
    <thead>
        <tr>
            <td>Sujet</td>

            <td>Réponses</td>
            <td>Dernier message</td>
            <?php if (isset($_SESSION['authentification'])): ?>
                <?php if ($_SESSION['authentification']->droitadmin!=0): ?>
                    <td>Action</td>
                <?php endif; ?>
            <?php endif; ?>

        </tr>
    </thead>
    <tbody id="tbody">
        <?php foreach($topics->countSubTopics($_GET['cat'], true) as $v ):?>
            <tr <?= ($v->bloque==1)?'style="background-color:#F2F4F4;"':'' ?>>
                <td id="droite"> <!-- lien de redirection vers le sujet posé -->

                    <a <?= ($v->bloque==1)?'style="color:gray;"':'' ?> href="<?= $topics->urlViewTopic($v->idf_topics) ?>">
                        <h4> <?php
                            if($v->resolu==0 AND $v->bloque==0){
                                echo '';
                            }else{
                                if ($v->bloque==1 AND $v->resolu==1 ){
                                    echo '<img src="img/icon/cadena.png" width="25px" alt="resolu">';
                                }elseif ($v->resolu==1 AND $v->bloque==0){
                                    echo '<img src="img/icon/true.png" width="25px" alt="resolu">';
                                }elseif ($v->resolu==0 AND $v->bloque==1){
                                    echo '<img src="img/icon/cadena.png" width="25px" alt="resolu">';
                                }
                            }
                            ?><?= $v->sujet ?></h4>
                        <p >Par <?= $v->pseudo ?> le <?= utf8_encode(strftime('%A %d %B %Y',strtotime($v->datecreation ))); ?></p>
                    </a>

                </td>
                </td>
                <td <?= ($v->bloque==1)?'style="color:gray;"':'' ?> ><?=$message->allMessage($v->idf_topics)  ?></td> <!-- nombre de reponses obtenues -->
                <td>
                    <?php $a=2; $infos=$message->allMessage($v->idf_topics, true, $a);   ?>
                    <a <?= ($v->bloque==1)?'style="color:gray;"':'' ?>  href="<?= ($infos->msg==null)? $topics->urlViewTopic($v->idf_topics): $topics->urlViewTopic($v->idf_topics).'#'.$infos->msg;  ?>"> <span>Dernier message par <?= ($infos->msg==null)? $infos->pseudo: $infos->login;  ?> </span> <br> <span> le <?= ($infos->msg==null)?  strftime("%A %d %B %Y à %H:%M:%S ",strtotime($infos->dateactu )) :  strftime("%A %d %B %Y à %H:%M:%S ",strtotime($infos->posteddate)) ?> </span></a> <!-- redirection vers le dernier commentaire exemple : liste.php?id=1#125 -->

                </td>
                    <?php if (isset($_SESSION['authentification'])): ?>
                        <?php if ($_SESSION['authentification']->droitadmin!=0 AND $v->droitadmin!=1): ?>
                            <td>
                                <a class="btn btn-default" href="?p=posts.categoriestopics&cat=<?=$_GET['cat']; ?>&edition=<?=$v->idf_topics  ?>"><img src="img/icon/edit.ico" width="20px"> Editer </a>
                                    <form action="?p=posts.deleteTopics" method="post" style="display: inline">
                                        <input type="hidden" name="id" value="<?=  $v->idf_topics; ?>">
                                        <button class="btn btn-default"  type="submit" onclick="return confirm('En supprimant ce topic, vous supprimerez toute les informations liée à ce dernier \n Etes vous sûr de valider?');" ><img src="img/icon/delete1.ico" width="20px"> supprimer</button>

                                     </form>
                            </td>
                    <?php endif; ?>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <section id="msgempty">
        <i>Aucun sujet posté pour cette sous-catégorie</i>
    </section>
<?php endif; ?>

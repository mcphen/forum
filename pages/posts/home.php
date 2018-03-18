<?php
$title="Accueil";
$nbre= $app->query("SELECT count(*) persconnect FROM users WHERE viewEtatUser=1", null, true);
$categories=$cat->queryCategories(); //affiche toutes les données de la table categories
$a=1;
?>
<section>
    <table id="table">
        <thead>
            <td>Categories</td>
            <td>Topics</td>
            <td>Derniers messages </td>
        </thead>
            <?php foreach ($categories as $key):
                    $subcat=$cat->querySubcategories($key->idf_categories);
                    $souscategories='';$idtr=$key->idf_categories;
                ?>
                <tr <?= 'id="'.$idtr.'"' ?> <?= ($idtr%2==0)?'style="background-color:#C3DDF4"':'style="background-color:#F2F4F4"' ?> >
                    <td>
                        <h4><a href="<?= $cat->UrlCat($key->idf_categories) ?>"> <?= $key->nomf_categories; ?> </a></h4>
                        <p style="color: #4e4e4e; line-height: 1px"><?=  $key->descriptionf_categories;  ?></p>
                        <p>
                            <?php
                                foreach ($subcat as $sub){
                                    $souscategories.= '<a href="'.$cat->UrlSubCat($sub->idf_sous_categories).'">'.$sub->nomf_sous_categories.'</a> | ';
                                }
                                echo $souscategories=substr($souscategories, 0,-3);


                            ?>
                        </p>
                    </td>
                    <td>
                        <?= $topics->countSubTopics($key->idf_categories)  ?>
                    </td>
                    <td>

                            <?php $inform=$message->lastMessageCat($key->idf_categories); ?>
                            <?php if ($inform==true): ?>
                                <a href="<?= ($inform->codeComment==null)? $topics->urlViewTopic($inform->codeSujet): $topics->urlViewTopic($inform->codeSujet).'#'.$inform->codeComment;  ?>"> <span>Dernier message par <?= ($inform->codeComment==null)? $inform->createurSujet: $inform->nameComment;  ?> </span> <br> <span>
                                        le <?= ($inform->codeComment==null)?  utf8_encode(strftime("%A %d %B %Y: %H:%M:%S ",strtotime($inform->dateSujetCreated )))  :  strftime("%A %d %B %Y à %H:%M:%S ",strtotime($inform->dateCommentPosted)) ?> </span></a> <!-- redirection vers le dernier commentaire exemple : liste.php?id=1#125 -->
                                <?php else: ?>
                                    <span>Aucun message posté dans cette catégorie</span>
                            <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <tbody>

        </tbody>
    </table>
    <section id="statistique">
        <section id="stats">
            Statistiques du Forum
        </section>
        <section id="stats_site">
            <p>Total sujets:  <b><?= $topics->counttopics()->nbretopics; ?></b></p>
            <p>Total messages:  <b><?= $message->countmsg()->nbremsg; ?></b> </p>
            <p>Total des abonnées:  <b><?= $auth->queryAuth(false); ?></b></p>
            <p>Nombre de personne connecté:  <b><?= $nbre->persconnect ?></b></p>
            <p>Plus récent abonné:  <b><a href="?p=profil&auth=<?= $auth->queryAuth(false, $a)->idusers; ?>"><?= $auth->queryAuth(false, $a)->pseudo; ?></a></b></p>

        </section>
    </section>
</section>
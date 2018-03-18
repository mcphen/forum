<?php
$tab='f_topics';
$tab1='f_topics_categories';
$table= new Table($app, $tab);
$tableCatTopics= new Table($app, $tab1);
$errors=[];
$iduser=$_SESSION['authentification']->idusers;
(isset($_POST['notif']))? $notif=1: $notif=0;
$res=0;
$datenow=date('Y-m-d H:i:s');
if (isset($_GET['edition'])){
    $url=$_GET['edition'];
    $req=$app->prepare("SELECT * FROM f_topics NATURAL JOIN f_topics_categories NATURAL JOIN f_categories NATURAL JOIN f_sous_categories WHERE idf_topics=?", [$url], null, true);

    if (isset($_POST['valider'])){
        $titre=trim(htmlspecialchars($_POST['sujet']));
        $souscat=htmlspecialchars($_POST['souscat']);
        $categorie=htmlspecialchars($_POST['cat']);
        $message=htmlspecialchars($_POST['message']);
        $msg=nl2br($message);
        $vartext=str_replace('\n', '<br>', $msg);

        $table->update($url, 'idf_topics',[
            'sujet'                 => $titre,
            'contenuf_topics'       =>$vartext,

        ]);
        $tableCatTopics->update($url,'idf_topics_categories',[
            'idf_topics'             => $url,
            'idf_categories'        => $categorie,
            'idf_sous_categories'   => $souscat
        ]);
        $_SESSION['flash']['success']="Sujet modifié avec success";
        header("Location: index.php");
        exit();
    }

}else{
    if (isset($_POST['valider']))
    {
        $titre=trim(htmlspecialchars($_POST['sujet']));
        $souscat=htmlspecialchars($_POST['souscat']);
        $categorie=htmlspecialchars($_POST['cat']);
        $message=htmlspecialchars($_POST['message']);
        $msg=nl2br($message);
        $vartext=str_replace('\n', '<br>', $msg);

        if (!empty($titre) AND !empty($souscat) AND !empty($cat) AND !empty($message))
        {
            if (strlen($titre)<=255)
            {

                $table->create([
                    'sujet'                 => $titre,
                    'contenuf_topics'       =>$vartext,
                    'resolu'                =>$res,

                    'id_createur'            =>$iduser,
                    'bloque'                =>0,
                    'datecreation'          =>$datenow
                ]);
                $id=$app->lastInsertId();
                $tableCatTopics->create([
                    'idf_topics'             => $id,
                    'idf_categories'        => $categorie,
                    'idf_sous_categories'   => $souscat
                ]);
                if (isset($_GET['cat'])){
                    header("Location: ".$cat->UrlCat($_GET['cat']));
                }elseif (isset($_GET['sub']))
                {
                    header("Location: ". $cat->UrlSubCat($_GET['sub']));
                }

            }else
            {
                $errors['strlen']="Le titre ne doit pas depasser les 255 caracteres";
            }
        }else
        {
            $errors['empty']="Veuillez remplir tous les champs";
        }

    }
}


?>

<?php if (isset($_GET['cat'])):
    $queryCat=$cat->prepareCategories($_GET['cat']);
    foreach ($queryCat as $q)
    ?>
    <section>
        Vous êtes ici : <a href="index.php"> FORUM</a> / <strong><?= strtoupper(utf8_encode($q->nomf_categories)) ?></strong>
        <hr>
    </section>
<?php elseif(isset($_GET['sub'])):
    $querySubCat=$cat->joinCat($_GET['sub']);
    foreach ($querySubCat as $q)
    ?>
    <section>
        Vous êtes ici : <a href="index.php"> FORUM</a>  / <a href="<?= $cat->UrlCat($q->idf_categories) ?>"><?= $q->nomf_categories ?></a>  / <strong><?= $q->nomf_sous_categories ?></strong>
        <hr>
    </section>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <p> Vous n'avez pas rempli correctement votre formulaire</p>
        <ul>
            <?php  foreach ($errors as $error): ?>
                <li><?= $error;?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<table id="table">
    <thead>
        <tr>
            <td colspan="2">NOUVEAU TOPIC</td>
        </tr>
    </thead>
    <form action="" method="post">
        <tbody>
        <tr>
            <td id="gauche"><label for="">Titre</label></td>
            <td id="droite"><input type="text" name="sujet" <?= isset($url)?'value="'.$req->sujet.'"':''; ?>> </td>
        </tr>
        <?php if (isset($_GET['cat'])): ?>
            <tr>
                <td id="gauche"><label for="">Sous-categorie</label></td>
                <td id="droite">
                    <select name="souscat" id="">
                        <option <?= isset($url)?'value="'.$req->idf_sous_categories.'"':''; ?>><?= isset($url)?$req->nomf_sous_categories:''; ?></option>
                        <?php foreach ($cat->querySubcategories($_GET['cat']) as $op): ?>
                            <option value="<?= $op->idf_sous_categories ?>"><?=$op->nomf_sous_categories ?></option>

                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            
            <input type="hidden" name="cat" value="<?= $_GET['cat']; ?>">
        <?php elseif(isset($_GET['sub'])): ?>
            <?php foreach ($cat->prepareSub($_GET['sub']) as $opt): ?>
                <input name="cat" type="hidden" value="<?= $opt->idf_categories ?>" >
                <input type="hidden" name="souscat" value="<?= utf8_encode($opt->idf_sous_categories )?>">
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td id="gauche"><label for="">Message</label></td>
            <td id="droite"  ><textarea  name="message" ><?= isset($url)?$req->contenuf_topics:''; ?></textarea></td>
        </tr>

        <tr>
            <td></td>
            <td id="droite"><button name="valider" type="submit"><?= isset($url)?'Editer le topic':'Poster le topic'; ?></button></td>
        </tr>
        </tbody>
    </form>

</table>


<?php

$table=new Table($app, 'f_categories');
if (isset($_GET['edit'])){
    $url=$_GET['edit'];
    $title="Adminstrateur | Editer catégorie";
   foreach ($cat->prepareCategories($_GET['edit']) as $p){

   }
    if (isset($_POST['enregistrer'])){
        $titre=htmlspecialchars($_POST['titre']);
        $descript=htmlspecialchars($_POST['description']);
        if (!empty($_POST['titre']))
        {
            if (strlen($_POST['description']) <= 255){
                $table->update($url,'idf_categories',[
                    'nomf_categories'       => $titre,
                    'descriptionf_categories'=>$descript
                ]);
                $_SESSION['flash']['success']="La catégorie a bien été modifiée";
                header('Location: admin.php');
                exit();
            }
        }
    }

}else{
    $title="Adminstrateur | Ajouter catégorie";
    if (isset($_POST['enregistrer'])){
        $titre=htmlspecialchars($_POST['titre']);
        $descript=htmlspecialchars($_POST['description']);
        if (!empty($_POST['titre']))
        {
            if (strlen($_POST['description']) <= 255){
                $table->create([
                    'nomf_categories'       => $titre,
                    'descriptionf_categories'=>$descript
                ]);
                $_SESSION['flash']['success']="La catégorie a bien été ajoutée";
                header('Location: admin.php');
                exit();
            }
        }
    }
}
?>

<section>
    Vous êtes ici : <a href="admin.php"> Administrateur</a> / <strong><?= isset($url)? 'Editer une categorie':'Ajouter une categorie'; ?></strong>
    <hr>
</section>

<form action="" method="post">
    <table id="table">
        <tr>
            <td id="gauche"><label for="titre">Titre</label></td>
            <td id="droite"><input type="text" id="titre" name="titre" <?= isset($url)? 'value="'.$p->nomf_categories.'"':''; ?>></td>
        </tr>
        <tr>
            <td id="gauche"><label for="description">Description</label></td>
            <td id="droite"><textarea name="description" id="description" ><?= isset($url)? $p->descriptionf_categories:''; ?></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td id="droite"><input type="submit" value="<?= isset($url)? 'Valider':'Enregistrer'; ?>" name="enregistrer"></td>
        </tr>
    </table>
</form>

<section class="alert alert-warning" style="margin-top: 15px">
    La description ne doit contenir que 255 caractères.
</section>

<?php
$urlcat=$_GET['categorie'];
$addcat= new Table($app,'f_sous_categories');
$title="Liste des sous-catégories";
if (isset($_GET['edit'])){
    $url=intval($_GET['edit']);
    $req=$app->prepare("SELECT * FROM f_sous_categories WHERE idf_sous_categories=?",[$url], null, true );

    if (!empty($_POST)){
        $login=htmlspecialchars($_POST['login']);
        if (!empty($login)){
            $addcat->update($url,'idf_sous_categories',[
               'nomf_sous_categories' =>$login
            ]);
            $_SESSION['flash']['success']="La sous catégorie a bien été modifié";
            header("Location: ?p=posts.cat&categorie=".$_GET['categorie']);
            exit();
        }
    }
}else{
    if (!empty($_POST)){
        $login=htmlspecialchars($_POST['login']);
        if (!empty($login)){
            $addcat->create([
                'nomf_sous_categories' =>$login,
                'idf_categories'       =>$urlcat
            ]);
            $_SESSION['flash']['success']="La sous catégorie a bien été ajouté";
            header("Location: ?p=posts.cat&categorie=".$_GET['categorie']);
            exit();
        }
    }
}
?>

<section>
    Vous êtes ici : <a href="admin.php"> Administrateur</a> / <strong>Liste des sous-categories</strong>
    <hr>
</section>

<section class="row" >
    <section  class="col-lg-5 col-md-5 col-sm-5">

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
        <form method="post">
            <table>
                <tr id="colonne">
                    <td id="gauche"> <label>Nom de la sous-catégorie : </label></td>
                    <td id="droite"> <input type="text" name="login" <?= isset($url)?'value="'.$req->nomf_sous_categories.'"':'';?> ></td>
                </tr>
                <tr id="colonne">
                    <td id="gauche"></td>
                    <td id="droite"> <button id="submit" type="submit" name="valider"> <?= isset($url)?'Valider':'Ajouter';?> </button></td>
                </tr>
            </table>
        </form>
    </section>
    <section  class="col-lg-7 col-md-7 col-sm-7">
        <table id="table">
            <thead>
            <tr>
                <td>ID</td>
                <td>Titre</td>
                <td>Action</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cat->querySubcategories($_GET['categorie']) as $v):?>
                <tr>
                    <td><?= $v->idf_sous_categories; ?></td>
                    <td><?= $v->nomf_sous_categories; ?></td>
                    <td>
                        <a class="btn btn-default" href="?p=posts.cat&categorie=<?=$_GET['categorie']?>&edit=<?=$v->idf_sous_categories; ?>"><img src="img/icon/edit.ico" width="20px"> Editer </a>
                        <form action="?p=posts.deleteSub&categorie=<?=$_GET['categorie'] ?>" method="post" style="display: inline">
                            <input type="hidden" name="id" value="<?=   $v->idf_sous_categories; ?>">
                            <button class="btn btn-default"  type="submit" onclick="return confirm('En supprimant cette sous-catégorie, vous supprimerez toute les informations liée à cette derniere \n Etes vous sûr de valider?');" ><img src="img/icon/delete1.ico" width="20px"> supprimer</button>

                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</section>


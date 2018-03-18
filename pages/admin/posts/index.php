<?php $title="Administrateur"; ?>
<h1>Administrateur</h1>
<hr>

<a href="?p=add.cat" class="btn btn-success">Ajouter catégorie</a>
<a href="?p=list.users" class="btn btn-success">Liste des utilisateurs</a>

<table id="table">
    <thead>
    <tr>
        <td>ID</td>
        <td>Titre</td>
        <td>Description</td>
        <td>Actions</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($cat->queryCategories()as $key): ?>
        <tr>
            <td><?= $key->idf_categories ?></td>
            <td><a href="?p=posts.cat&categorie=<?=  $key->idf_categories ?>"><?= $key->nomf_categories ?></a></td>
            <td><?= $key->descriptionf_categories ?></td>
            <td>
                <a class="btn btn-default" href="?p=add.cat&edit=<?= $key->idf_categories ?>"><img src="img/icon/edit.ico" width="20px"> Editer </a>
                <form action="?p=posts.delete" method="post" style="display: inline">
                    <input type="hidden" name="id" value="<?=  $key->idf_categories; ?>">
                    <button class="btn btn-default"  type="submit" onclick="return confirm('En supprimant cette catégorie, vous supprimerez toute les informations liée à cette derniere \n Etes vous sûr de valider?');" ><img src="img/icon/delete1.ico" width="20px"> supprimer</button>

                </form>

            </td>

        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
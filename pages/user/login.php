<?php
if (!empty($_POST)){

    if($auth->login($_POST['pseudo'], $_POST['mdp'])){

        $insertTable=new Table($app,'users');
        $insertTable->update($_SESSION['authentification']->idusers, 'idusers',[
            ' viewEtatUser'=>1,
        ]);
        header('Location: index.php');
    }else{
        ?>
        <div class="alert alert-danger"> identifiant incorrect</div>
        <?php
    }

}
?>


<section>
    Vous êtes ici : <a href="index.php"> FORUM</a> / <strong>Connexion</strong>
    <hr>
</section>
<!--
*************                Message d'erreur lors de l'insertion des données       *************************
-->
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

<!--
***************                Formulaire d'inscription **************************
-->
<section id="inscription" >
    <h4><strong>CONNEXION</strong><span> <a href="<?= $auth->urlSign(); ?>">Pas encore de compte? Inscrivez vous ici !</a></span></h4>
    <form action="" method="post">
        <table style="width: 50%">
            <tr>
                <td id="gauche"><label for="">Pseudo</label></td>
                <td id="droite"><input type="text" name="pseudo"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Mot de passe</label></td>
                <td id="droite"><input type="password" name="mdp"></td>
            </tr>
            <tr>
                <td></td>
                <td id="droite"><button name="envoyer" type="submit"> Connexion</button></td>
            </tr>
        </table>
    </form>

</section>
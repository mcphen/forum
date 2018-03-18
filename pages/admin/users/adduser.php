<?php
$errors=[];
$avatar="default.jpg";
$insertTable=new Table($app,'users');
$title="Enregistrer l'utilisateur";
if (isset ($_POST['envoyer']))
{
// htmlspecialchars : verifie si les variables données sont bien des characteres
    $pseudo=htmlspecialchars($_POST['pseudo']);
    $email=htmlspecialchars($_POST['mail']);
    $email2=htmlspecialchars($_POST['mail2']);
    $mdp= sha1($_POST['mdp']);
    $mdp2= sha1($_POST['mdp2']);
    $droit=$_POST['droit'];
    $nbrpseudo=strlen($pseudo);
// verifier si les champs sont bien remplis
    if (!empty($pseudo)  AND !empty($email) AND !empty($email2)  AND !empty($mdp) AND !empty($mdp2))
    {
        if ($nbrpseudo<=255)
        {
            if($email == $email2)

            {
                //filtrer le mail
                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                {

                    $mailuser=$auth->verification('email', $email, false);
                    $pseudoUser=$auth->verification('pseudo', $pseudo, false);
                    if ($mailuser==0 && $pseudoUser==0)
                    {
                        $insertTable->create([
                            'pseudo'  =>$pseudo,
                            'email'  =>$email,
                            'mdp'    =>$mdp,
                            'avatar' =>$avatar,
                            'droitadmin'=>$droit
                        ]);
                        $_SESSION['flash']['success']="L'utilisateur a bien été ajouté";
                        header("Location: admin.php?p=list.users");
                        exit();
                    }else
                    {
                        $errors['exist']="L'utilisateur existe déjà";
                    }
                }else
                {
                    $errors['avalable']="Votre adresse mail n'est pas valide";
                }
            }
        }else
        {
            $errors['depasser']="Le nombre de caractere doit etre inferieur à 255 caracteres";
        }
    }else{
        $errors['empty']="Veuillez remplir tous les champs";
    }

}
?>

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


<section>
    Vous êtes ici : <a href="admin.php"> Administrateur</a> / <a href="?p=list.users"> Liste des utilisateurs </a> / <strong>Enregistrer un utilisateur</strong>
    <hr>
</section>

<section id="inscription" >

    <form action="" method="post">
        <table style="width: 50%">
            <tr>
                <td id="gauche"><label for="">Pseudo</label></td>
                <td id="droite"><input type="text" name="pseudo"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Adresse mail</label></td>
                <td id="droite"><input type="email" name="mail"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Confirmation de l'adresse mail</label></td>
                <td id="droite"><input type="email" name="mail2"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Mot de passe</label></td>
                <td id="droite"><input type="password" name="mdp"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Confirmation du mot de passe</label></td>
                <td id="droite"><input type="password" name="mdp2"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Privilege</label></td>
                <td id="droite">
                    <select name="droit" id="">
                        <option ></option>
                        <option value="1">Administrateur</option>
                        <option value="2">Modérateur</option>
                        <option value="0">Simple utilisateur</option>

                    </select>
                </td>
            </tr>
        
            <tr>
                <td></td>
                <td id="droite"><button name="envoyer" type="submit"> Enregistrer</button></td>
            </tr>
        </table>
    </form>

</section>
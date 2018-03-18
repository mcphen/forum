<?php
$errors=[];
$avatar="default.jpg";
$insertTable=new Table($app,'users');
$droit=0;
$datenow=date('Y-m-d H:i:s');
if (isset ($_POST['envoyer']))
{
// htmlspecialchars : verifie si les variables données sont bien des characteres
$pseudo=htmlspecialchars($_POST['pseudo']);
$email=htmlspecialchars($_POST['mail']);
$email2=htmlspecialchars($_POST['mail2']);
$mdp= sha1($_POST['mdp']);
$mdp2= sha1($_POST['mdp2']);
$nbrpseudo=strlen($pseudo);
// verifier si les champs sont bien remplis
    if (!empty($pseudo)  AND !empty($email) AND !empty($email2)  AND !empty($mdp) AND !empty($mdp2))
    {
        if ($nbrpseudo<=255)
        {
            if ($mdp2==$mdp) {
                if ($email == $email2) {
                    //filtrer le mail
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $reqst = $auth->queryAuth();

                        if (count($reqst) == 0) {
                            $droit = 1;
                        }
                        $mailuser = $auth->verification('email', $email, false);
                        $pseudoUser = $auth->verification('pseudo', $pseudo, false);
                        if ($mailuser == 0 && $pseudoUser == 0) {
                            $insertTable->create([
                                'pseudo' => $pseudo,
                                'email' => $email,
                                'mdp' => $mdp,
                                'avatar' => $avatar,
                                'droitadmin' => $droit,
                                'dateInscription' => $datenow,
                                'viewEtatUser'=>0,
                                'biographie'=>null,
                                'facebook'=>null,
                                'twitter'=>null,
                                'siteweb'=>null,
                                'linkedln'=>null,
                            ]);
                            header("Location: index.php?p=connexion");
                        } else {
                            $errors['exist'] = "L'utilisateur existe déjà";
                        }
                    } else {
                        $errors['avalable'] = "Votre adresse mail n'est pas valide";
                    }
                }else{
                    $errors['email']="Vos adresses mails ne sont pas identique";
                }
            }else{
                $errors['mdp']="Mot de passe incorecte";
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

<section>
    Vous êtes ici : <a href="index.php"> FORUM</a> / <strong>Inscription</strong>
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
    <h4><strong>INSCRIPTION</strong><span> <a href="<?= $auth->urlLogin(); ?>">Déjà inscrit? Connectez vous ici !</a></span></h4>
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
                <td></td>
                <td id="droite"><button name="envoyer" type="submit"> Inscription</button></td>
            </tr>
        </table>
    </form>

</section>
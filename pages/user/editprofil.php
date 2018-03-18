<?php

$q=$auth->viewAuth($_GET['auth']);
$errors=[];
$url=$_GET['auth'];
$insertTable=new Table($app,'users');
$datenow=date('Y-m-d H:i:s');
if (isset ($_POST['envoyer']))
{
// htmlspecialchars : verifie si les variables données sont bien des characteres
    $pseudo=htmlspecialchars($_POST['pseudo']);


    $bio=htmlspecialchars($_POST['biographie']);
    $fb=htmlspecialchars($_POST['facebook']);
    $tweet=htmlspecialchars($_POST['twitter']);
    $siteweb=htmlspecialchars($_POST['siteweb']);
    $lknd=htmlspecialchars($_POST['linkedln']);

    if (isset($_POST['envoyer'])){

        if (isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
        {
            $taillemax=2097152;
            $extensvalide=array('jpg','jpeg','gif', 'png');
            if ($_FILES['avatar']['size'] <= $taillemax)
            {
                $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
                if(in_array($extensionUpload, $extensvalide))
                {
                    $namefile=$url.".".$extensionUpload;
                    $chemin= "membre/avatar/".$namefile;
                    $resultat=move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                    if ($resultat)
                    {
                        $insertTable->update($url,'idusers',[
                            'avatar' =>$namefile
                        ]);

                    }
                    else
                    {
                        $errors['404']="404 ERROR ";
                    }
                }
                else
                {
                    $errors['4']="votre photo de profil doit etre au format jpg, jpeg, gif, png !";
                }
            }
            else
            {
                $errors['44']="votre photo de profil ne doit pas depasser les 2Mo";
            }
        }


        if (!empty($_POST['mdp']) AND !empty($_POST['mdp2'])){
            $mdp= sha1($_POST['mdp']);
            $mdp2= sha1($_POST['mdp2']);
            if ($mdp===$mdp2){
               
                $insertTable->update($url,'idusers',[
                    'mdp' =>$mdp
                ]);
            }else{
                $errors['mdp']="Mot de passe incorecte";
            }
        }
        $msg=nl2br($bio);
        $vartext=str_replace('\n', '<br>', $msg);

        $insertTable->update($url,'idusers',[
            'pseudo' =>$pseudo,
            'biographie'=>$vartext,
            'facebook'=>$fb,
            'twitter'=>$tweet,
            'siteweb'=>$siteweb,
            'linkedln'=>$lknd
        ]);
        $_SESSION['flash']['success']="Profil modifié avec success";
        header("Location: index.php?p=profil&auth=".$url);
        exit();

    }

}
?>

<section>
    Vous êtes ici : <a href="index.php"> FORUM</a> <img src="img/icon/next.png" width="13px" alt="next"> <strong>Modification Profil</strong>
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
    <h4><strong>Modification</strong><span></h4>
    <form action="" method="post" enctype="multipart/form-data">
        <table style="width: 50%">
            <tr>
                <td id="gauche"><label for="">Avatar</label></td>
                <td id="droite"><input type="file" name="avatar"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Pseudo</label></td>
                <td id="droite"><input type="text" name="pseudo" value="<?= $q->pseudo ?>"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Adresse mail</label></td>
                <td id="droite"><input type="email" name="mail" value="<?= $q->email ?>" disabled></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Site web</label></td>
                <td id="droite"><input type="text" name="siteweb" value="<?= $q->siteweb ?>"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Facebook</label></td>
                <td id="droite"><input type="text" name="facebook" value="<?= $q->facebook ?>"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Linkedln</label></td>
                <td id="droite"><input type="text" name="linkedln" value="<?= $q->linkedln ?>"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Twitter</label></td>
                <td id="droite"><input type="text" name="twitter" value="<?= $q->twitter ?>"></td>
            </tr>
            <tr>
                <td id="gauche"><label for="">Biographie</label></td> <?php $rqd=preg_replace(' <<br />>', '', $q->biographie );  ?>
                <td id="droite"><textarea name="biographie" id="" cols="30" rows="10"><?= $rqd ?></textarea>  </td>
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
                <td id="droite"><button name="envoyer" type="submit"> Enregistrer</button></td>
            </tr>
        </table>
    </form>

</section>
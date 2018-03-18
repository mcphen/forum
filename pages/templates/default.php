<?php
if(isset($_SESSION['authentification'])){
$notiF= $topics->messagerie($_SESSION['authentification']->idusers, 1) ;
$notiF1= $topics->notifications($_SESSION['authentification']->idusers, 1) ;
$total=$notiF1+$notiF;}
?>
<!DOCTYPE html>
<html lang="Fr">
<head>
    <meta charset="utf-8">
    <title>
        <?php
        if(isset($_SESSION['authentification'])){
            if ($notiF==0 AND $notiF1==0){
                echo '';
            }else{
                echo '('.$total.')';
            }
        }
        ?>
        Forum <?=(isset($title))? '| '.$title:'';?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta charset="utf-8">
    <script src="js/jquery/jquery310.js"></script>
    <script src="js/bootstrap.js"></script>
    <!-- Le styles -->
    <link href="css/bootstrap/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
    </style>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
    <!-- Load jQuery
    <script type="text/javascript" src="js/jquery/jquery-3.2.0.js"></script>-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wysibb.js"></script>
    <link rel="stylesheet" href="css/default/wbbtheme.css" />
    <script type="text/javascript" src="js/fr.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){
            var wbbOpt = {
                buttons: "bold,italic,underline,|,img,link,|,code,quote",
                lang:"fr",
                resize_maxheight:"500",
                width : "200px",

            }

            $("#wysibb").wysibb(wbbOpt);
        });
    </script>
    <!-- Load WysiBB JS and Theme


 -->


</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">

            <a class="navbar-brand" href="index.php">Forum</a>

        </div>
        <?php  if (isset($_SESSION['authentification'])): ?>


            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">

                    <a class="dropdown-toggle" data-toggle="dropdown"
                      href="#" >
                        Bonjour <?= $_SESSION['authentification']->pseudo; ?>
                        <span <?= ($notiF==0 AND $notiF1==0)?'':'class="label label-danger"' ?> > <?= ($notiF==0 AND $notiF1==0)?'':$notiF+$notiF1; ?></span>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php?p=profil&auth=<?= $_SESSION['authentification']->idusers ?>">Profil</a></li>
                        <li>

                            <a href="index.php?p=notification&auth=<?= $_SESSION['authentification']->idusers ?>">Notifications
                                <span <?= ($notiF1==0)?'':'class="label label-danger"' ?> > <?= ($notiF1==0)?'':$notiF1; ?></span>
                            </a>
                        </li>
                        <li>

                            <a href="index.php?p=messagerie&auth=<?= $_SESSION['authentification']->idusers ?>">Messagerie
                                <span <?= ($notiF==0)?'':'class="label label-danger"' ?> > <?= ($notiF==0)?'':$notiF; ?></span>
                            </a>
                        </li>
                        <?php  if ($_SESSION['authentification']->droitadmin==1): ?>
                            <li><a href="admin.php">Paramètre administrateur</a></li>
                        <?php endif; ?>
                        <li><a href="<?= $auth->urlLogout(); ?>"><span class="glyphicon glyphicon-log-out"></span> Se déconnecter</a></li>
                    </ul>
                </li>




            </ul>
        <?php else: ?>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?= $auth->urlSign(); ?>"> S'inscrire</a></li>
                <li><a href="<?= $auth->urlLogin(); ?>">Se connecter</a></li>
            </ul>
        <?php endif; ?>


    </div>
</div>

<div class="container">
    <div class="started-template" style="padding-top: 10px">
        <section id="corps">
            <?php if (isset($_SESSION['flash'])):?>
                <?php foreach($_SESSION['flash'] as $type => $message): ?>

                    <div class="alert alert-<?= $type; ?>" id="message">
                        <?= $message;?>
                    </div>

                <?php endforeach; ?>
                <?php unset($_SESSION['flash']); //destruction de l'index ?>
            <?php endif; ?>
    </div>

    <?= $content; ?>


    <footer>
        Copyright Enock MAMBOU | Novembre 2017
    </footer>
</div> <!-- /container -->







</body>
</html>

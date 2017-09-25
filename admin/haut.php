<?php 
require_once "../data.php";
?> 
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<!-- Design By: David Weitz - http://darkfate.info/ -->
<title><?php echo "Liste ". $namenewsletter; ?></title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<div class="white">
  <div class="header">
    <h1 class="title">Administration de la liste de diffusion <?php echo $namenewsletter; ?></h1>
	<?php $xajax->printJavascript(); /* Fonction qui va afficher le Javascript de la page */ ?>
  </div>
	<?php
if($_GET["page"] == "destroy")
{
	$_SESSION['$codeverife'] = "no";
}
if ($_SESSION['$codeverife'] != "ok")
{
	if ( basename($_SERVER['PHP_SELF']) != "index.php")
	{
		 header('Location: index.php');
	}
?>
	<div class="content">
	<div id="block"></div>
        <p>Veuillez entrer le mot de passe pour obtenir les codes d'accès au serveur central de la NASA :</p>
            <p>
            <input type="password" name="mot_de_passe" id="mot_de_passe" onkeypress="if (event.keyCode == 13) xajax_verifePass(document.getElementById('mot_de_passe').value);"/>
            <input type="button" value="Valider" onclick="xajax_verifePass(document.getElementById('mot_de_passe').value);" />
            </p>
        <p>Cette page est réservée au personnel de la NASA. Si vous ne travaillez pas à la NASA, inutile d'insister vous ne trouverez jamais le mot de passe ! ;-)</p>
<?php
}
else
{
?>
  <div class="nav">
    <ul>
      <li><a href="index.php">Accueil</a></li>
      <li><a href="envoi.php">Envoyer un mail</a></li>
	<li><a href="mails.php">Ajouter des adresse</a></li>
	<li><a href="admin.php">Admin</a></li>
	<li><a href="list.php">Liste</a></li>
	<li><a href="index.php?page=destroy">Déconnexion</a></li>
    </ul>
  </div>
<div class="content" id="content">
<div id="block"></div>
<?php

if ($_SESSION['$codeverife'] == "ok" AND basename($_SERVER['PHP_SELF']) == "index.php")
	{
		echo "<p>Bienvenue sur la page d'administration de votre liste</p>";
	}
}
?>

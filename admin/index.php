<?php
require_once("../data.php");

function verifePass($pass)
{
	require("../data.php");
	$reponse = new xajaxResponse();
	if ( md5($pass) != $passwordadmin)
	{
		$reponse->assign('block', 'innerHTML', "<p>Le mot de pass n'est pas correct.</p>");
	}
	else
	{
		$_SESSION['$codeverife'] = "ok";
		$reponse->script('document.location.href="index.php"');
	}
		
	return $reponse;
}

require_once('./xajax_core/xajax.inc.php');

session_start();

$xajax = new xajax(); // On initialise l'objet xajax.

$xajax->register(XAJAX_FUNCTION, 'verifePass'); // Déclaration de la fonction MaFonctionPHP.

$xajax->processRequest(); // Fonction qui va se charger de générer le Javascript à partir des données que l'on a fournies à xAjax.
               
include("haut.php");

include("bas.php");
?>

<?php
session_start();
require_once('./xajax_core/xajax.inc.php');

function InsertMail($textarea)
{
	require_once "../data.php";
	$reponse = new xajaxResponse();
	try
	{
		$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
	}
	catch (Exception $e)
	{

     	   die('Erreur : ' . $e->getMessage());

	}
	
	$i = 0;
	preg_match_all('`[a-zA-Z0-9_\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+`m', $textarea, $emails);
	foreach (array_unique($emails[0]) as $mail)
	{
		$stm = $bdd->prepare('SELECT * FROM mails WHERE email=? AND verife="ok"');
		$stm->execute(array($mail));
		if ($stm->rowCount() < 1)
		{
			$bdd->exec('DELETE FROM mails WHERE email=\''.$mail.'\' ');
			$bdd->exec('INSERT INTO mails(email, timestramp, code, verife) VALUES(\''.$mail.'\',\''. time() .'\', \''. uniqid() .'\', \'ok\')');
			$i ++;
		}
		
	}
	$reponse->assign('block', 'innerHTML', "<p>". $i ." email(s) on été ajouter à la liste.</p>");
	return $reponse;
}
$xajax = new xajax();

$xajax->register(XAJAX_FUNCTION, 'InsertMail');

$xajax->processRequest();

include("haut.php");

if ($_SESSION['$codeverife'] == "ok")
{
?>
<p>Vous pouvez inserer vos adresse emails ici:</p>
<textarea id="mails" rows="5" cols="100"></textarea><br><br>
<input name="envoie" type="button" value="Ajouter les Emails" onclick="xajax_InsertMail(document.getElementById('mails').value)"/>

<?php
}
include("bas.php");
?>

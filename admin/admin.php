<?php
require_once('./xajax_core/xajax.inc.php');

function Identite($namenewsletternew, $mailreplynew, $mymailnew)
{
	$reponse = new xajaxResponse();
	require_once("../data.php");
		$fichier = "../data.php";
		if ($text=fopen($fichier,'r'))
		{
			$contenu=file_get_contents($fichier);
			$contenuMod=str_replace('namenewsletter = "'.$namenewsletter.'";', 'namenewsletter = "'.$namenewsletternew.'";', $contenu);
			$contenuMod=str_replace('mailreply = "'.$mailreply.'";', 'mailreply = "'.$mailreplynew.'";', $contenuMod);
			$contenuMod=str_replace('mymail = "'.$mymail.'";', 'mymail = "'.$mymailnew.'";', $contenuMod);
			fclose($text);
			if ($text2=fopen($fichier,'w+'))
			{
				fwrite($text2,$contenuMod);
				fclose($text2);
				$reponse->assign('ID', 'innerHTML', "<p>La modification a été apliquer.</p>");
			}
			else
			{
				$reponse->assign('ID', 'innerHTML', "<p>Impossible de modifier les information. (Erreur d'écriture)</p>");
				$err = "true";
			}
		}
		else
		{
				$reponse->assign('ID', 'innerHTML', "<p>Impossible de modifier les information. (Erreur de lecture)</p>");
				$err = "true";
		}
		if (isset($err))
		{
			$reponse->assign('namenewsletter', 'value', $namenewsletter);
			$reponse->assign('mymail', 'value', $mymail);
			$reponse->assign('mailreply', 'value', $mailreply);
			
		}
	return $reponse;
}

function SMTP($mail_Smtpservernew, $mail_Usernamenew, $mail_Passwordnew, $mail_SMTPSecurenew, $mail_Portnew)
{
	$reponse = new xajaxResponse();
	require_once("../data.php");
		$fichier = "../data.php";
		if ($text=fopen($fichier,'r'))
		{
			$contenu=file_get_contents($fichier);
			$contenuMod=str_replace('mail_Smtpserver = "'.$mail_Smtpserver.'";', 'mail_Smtpserver = "'.$mail_Smtpservernew.'";', $contenu);
			$contenuMod=str_replace('mail_Username = "'.$mail_Username.'";', 'mail_Username = "'.$mail_Usernamenew.'";', $contenuMod);
			$contenuMod=str_replace('mail_Password = "'.$mail_Password.'";', 'mail_Password = "'.$mail_Passwordnew.'";', $contenuMod);
			$contenuMod=str_replace('mail_Port = "'.$mail_Port.'";', 'mail_Port = "'.$mail_Portnew.'";', $contenuMod);
			$contenuMod=str_replace('mail_SMTPSecure = "'.$mail_SMTPSecure.'";', 'mail_SMTPSecure = "'.$mail_SMTPSecurenew.'";', $contenuMod);
			fclose($text);
			if ($text2=fopen($fichier,'w+'))
			{
				fwrite($text2,$contenuMod);
				fclose($text2);
				$reponse->assign('SMTP', 'innerHTML', "<p>La modification a été apliquer.</p>");
			}
			else
			{
				$reponse->assign('SMTP', 'innerHTML', "<p>Impossible de modifier les information. (Erreur d'écriture)</p>");
				$err = "true";
			}
		}
		else
		{
				$reponse->assign('SMTP', 'innerHTML', "<p>Impossible de modifier les information. (Erreur de lecture)</p>");
				$err = "true";
		}
		if (isset($err))
		{
			$reponse->assign('mail_Smtpserver', 'value', $mail_Smtpserver);
			$reponse->assign('mail_Usernamenew', 'value', $mail_Usernamenew);
			$reponse->assign('mail_Password', 'value', $mail_Password);
			$reponse->assign('mail_Portnew', 'value', $mail_Portnew);
			$reponse->assign($mail_SMTPSecure, 'selected', 'true');
		}
	return $reponse;
}


function Purge()
{
	require_once "../data.php";
	try
	{
		$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	$nb = time() - (3 * 24 * 60 * 60);
	$bdd->exec('DELETE FROM \''.$table.'\' WHERE verife="no" AND timestramp<\''.$nb.'\'');
	$reponse = new xajaxResponse();

	$reponse->assign('Purge', 'innerHTML', "<p>La base de donnée à été purger</p>");
	return $reponse;
	
	
}
function Pass($pass, $pass2)
{
	$reponse = new xajaxResponse();

	if ("$pass" == "")
	{
		$reponse->assign('PASS', 'innerHTML', "<p>Le mot de passe est vide.</p>");
	}
	elseif ("$pass" == "$pass2")
	{
		require_once("../data.php");
		$fichier = "../data.php";
		if ($text=fopen($fichier,'r'))
		{
			$contenu=file_get_contents($fichier);
			$contenuMod=str_replace($passwordadmin, md5($pass), $contenu);
			fclose($text);
			if ($text2=fopen($fichier,'w+'))
			{
				fwrite($text2,$contenuMod);
				fclose($text2);
				$reponse->assign('PASS', 'innerHTML', "<p>Le mot de passe a été modifié.</p>");			
			}
			else
			{
				$reponse->assign('PASS', 'innerHTML', "<p>Impossible de modifier le mot de passe. (Erreur d'écriture)</p>");
			}
		}
		else
			{
				$reponse->assign('block', 'innerHTML', "<p>Impossible de modifier le mot de passe. (Erreur de lecture)</p>");
			}
	}
	else
	{
		$reponse->assign('PASS', 'innerHTML', "<p>Les mots de passe ne son pas identique.</p>");
	}
	return $reponse;
}

session_start();

$xajax = new xajax(); // On initialise l'objet xajax.

$xajax->register(XAJAX_FUNCTION, 'Purge'); // Déclaration de la fonction MaFonctionPHP.
$xajax->register(XAJAX_FUNCTION, 'Pass');
$xajax->register(XAJAX_FUNCTION, 'Identite');
$xajax->register(XAJAX_FUNCTION, 'SMTP');

$xajax->processRequest(); // Fonction qui va se charger de générer le Javascript à partir des données que l'on a fournies à xAjax.
               
include("haut.php");
require_once "../data.php";

if ($_SESSION['$codeverife'] == "ok")
{?>
	<fieldset> <legend align="left">Identité</legend>
	<div id="ID"></div>
	<label>Nom donné à la liste d'information: </label><input type="text" value="<?php echo $namenewsletter; ?>" id="namenewsletter" ><br><br>
	<label>Adresse Email ou répondre: </label><input type="text" value="<?php echo $mailreply; ?>" id="mailreply" ><br><br>
	<label>Votre adresse Email: </label><input type="text" value="<?php echo $mymail; ?>" id="mymail" ><br><br>
	<input type="button" value="Valider" onclick="xajax_Identite(document.getElementById('namenewsletter').value, document.getElementById('mailreply').value, document.getElementById('mymail').value);"/>
	</fieldset>
	<fieldset> <legend align="left">Serveur SMTP</legend>
	<div id="SMTP"></div>
	<label>Adresse du serveur SMTP: </label><input type="text" value="<?php echo $mail_Smtpserver; ?>" id="mail_Smtpserver" ><br><br>
	<label>Nom d'utilisateur: </label><input type="text" value="<?php echo $mail_Username; ?>" id="mail_Username" ><br><br>
	<label>Mot de passe: </label><input type="password" value="<?php echo $mail_Password; ?>" id="mail_Password" ><br><br>
	<label>SMTPSecure: </label>
	<select id="mail_SMTPSecure">
	<option value="TLS" id="TLS" <?php if ($mail_SMTPSecure == "TLS") { echo "selected";} ?>>TLS</option>
	<option value="ssl" id="ssl" <?php if ($mail_SMTPSecure == "ssl") { echo "selected";} ?>>ssl</option>
	</select><br><br>
	<label>Port SMTP: </label><input type="number" id="mail_Port" value="<?php echo $mail_Port; ?>"><br><br>
	<input type="button" value="Valider" onclick="xajax_SMTP(document.getElementById('mail_Smtpserver').value, document.getElementById('mail_Username').value, document.getElementById('mail_Password').value, document.getElementById('mail_SMTPSecure').value, document.getElementById('mail_Port').value);"/>
	</fieldset>
	<fieldset> <legend align="left">Modifier le mot de passe</legend>
	<div id="PASS"></div>
	<label>Nouveau mot de passe: </label><input type="password" id="password1" ><br><br>
	<label>Confirmer le nouveau mot de passe: </label><input type="password" id="password2"><br><br>
	<input type="button" value="Changer de mot de passe" onclick="xajax_Pass(document.getElementById('password1').value, document.getElementById('password2').value);"/>
	</fieldset>
	<fieldset> <legend align="left">Purge</legend>
	<div id="Purge"></div>
	<input type="button" value="Purger la base de donné" onclick="xajax_Purge();"/>
	</fieldset>
<?php
}

include("bas.php");
?>

<?php
require_once("data.php");

try
{
$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
}
catch (Exception $e)
{

        die('Erreur : ' . $e->getMessage());

}

require_once("mail.php");



if ($_GET['email'] AND $_GET['code'])
{
	$stm = $bdd->prepare('SELECT * FROM mails WHERE email=?');
	$stm->execute(array($_GET['email']));
	if ($stm->rowCount() == 1)
	{
		$donnees = $stm->fetch();
		if ($_GET['delete'] == 1 AND $donnees['code'] == $_GET['code'])
		{
			$bdd->exec('DELETE FROM mails WHERE email=\''.$donnees['email'].'\' ');
			echo "<p>Votre désinscription à bien été éfectué.</p>";
		}		
		elseif ( $donnees['code'] == $_GET['code'] AND time() - $donnees['timestramp'] <= 3 * 24 * 60 * 60 )
		{
			$bdd->exec('UPDATE mails SET verife=\'ok\' WHERE email=\''.$donnees['email'].'\' ');
			echo "<p>Félicitation, votre inscriptions à la lettre d'information ". $namenewsletter ." viens d'être valider.</ p>"; 
		}
		else
		{
			echo "<p>Il y' a une erreur dans le liens utilisé</p>";
		}
	}
	else
	{
		echo "<p>La page demander n'est pas valide.</p>";
	}
	$stm->closeCursor();

}


elseif ($_POST['email'])
{	
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{
		if($bdd->query('SELECT email FROM mails WHERE email=\''.$_POST['email'].'\'')->rowCount() >= 1)
		{
			echo "<p>Votre email a déja été enregistré.</p>";
		}
		else
		{
			$code = uniqid();
			$bdd->exec('INSERT INTO mails(email, timestramp, code, verife) VALUES(\''.$_POST['email'].'\',\''. time() .'\', \''. $code .'\', \'no\')');

			
			news_mail($_POST['email'], "Votre inscription à la lettre d'information ". $namenewsletter, "<p>Bonjour,<br/>vous venez de vous inscrire à la lettre d'information ". $namenewsletter .".<br/>Pour valider votre inscription il vous suffis de suivre ce lien: <a href=http://". $adresse_dossier ."index.php?email=". $_POST['email'] ."&code=". $code .">ICI</a></p>". sign_mail($table, $mailto, $bdd, $adresse_dossier));
			
			echo "<p>Un Email viens de vous être envoyé.</br>Dans cette Email vous trouverez un lien pour activer votre inscription. Attention ce liens n'est valide que trois jours.</p>";
		}
	}
	else
	{
		echo "<p>Vous n'avez pas mis une adresse e-mail correct.</p>";
	}	
}

if (!$_GET['email'] AND (!$_POST['email'] OR !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)))
{?>
<form action="index.php" method="post">
<p>
    <input type="text" name="email" />
    <input type="submit" value="Valider" />
</p>
</form>
<?php
}
?>

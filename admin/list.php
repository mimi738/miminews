<?php
require_once('./xajax_core/xajax.inc.php');

session_start();

function tableau($page)
{
	$reponse = new xajaxResponse();
	require_once("../data.php");
	$modif = "<div id='msg'></div><p>Action sur la séléction:
	<input type='button' value='Suprimer' onclick='xajax_actionlist(".$page.", \"del\");' />
	<input type='button' value='Valider' /></p>";
	$tableau = "<table><thead> <!-- En-tête du tableau --><tr><th>Email</th><th>Date</th><th>Valide</th><th>Tout selectionner<br><input type='checkbox' id='checkall' onchange='xajax_check_select(".$page.", document.getElementById(\"checkall\").checked);' /></th></tr></thead>";
	//en tête deu tableau
	try
		{
		$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
		}
		catch (Exception $e)
		{
			 die('Erreur : ' . $e->getMessage());
		}
	$offset = ($page - 1) * 20;
	$stm = $bdd->query('SELECT email, timestramp, verife FROM '.$table.' LIMIT '.$offset.', 20');
	while ($donnees = $stm->fetch())
	{
		$tableau = $tableau."<tr><td>".$donnees['email']."</td><td>".date("j F Y", $donnees['timestramp'])."</td><td>".$donnees['verife']."</td><td><input type='checkbox'  id='".$donnees['email']."' /></td></tr>";
	}
	$stm->closeCursor();	
	$tableau = $tableau."</table><br>";
	$reponse->assign('block', 'innerHTML', $modif.$tableau);
	return $reponse;
}

function check_select($page, $bool)
{
	$reponse = new xajaxResponse();
	
	require_once("../data.php");
	try
		{
		$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
		}
		catch (Exception $e)
		{
			 die('Erreur : ' . $e->getMessage());
		}
	$offset = ($page - 1) * 20;
	$stm = $bdd->query('SELECT email, timestramp, verife FROM '.$table.' LIMIT '.$offset.', 20');
	while ($donnees = $stm->fetch())
	{
		$reponse->assign($donnees['email'], 'checked', $bool);
	}
	$stm->closeCursor();
	return $reponse;
}

function actionlist($page, $action)
{
	$reponse=new xajaxResponse();
	require_once("../data.php");
	try
		{
		$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
		}
		catch (Exception $e)
		{
			 die('Erreur : ' . $e->getMessage());
		}
	$offset = ($page - 1) * 20;
	$stm = $bdd->query('SELECT email, timestramp, verife FROM '.$table.' LIMIT '.$offset.', 20');
	while ($donnees = $stm->fetch())
	{
		if ($action == "del")
		{
			//$bdd->query('DELETE FROM '.$table.' WHERE email='.$donnees['email'].'');
			$retour=$retour.$donnees['email']."<br>";
		}
	}
	$stm->closeCursor();
	$reponse->assign('msg', 'innerHTML', $retour);
	return $reponse;
}

$xajax = new xajax();
$xajax->register(XAJAX_FUNCTION, 'tableau');
$xajax->register(XAJAX_FUNCTION, 'check_select');
$xajax->register(XAJAX_FUNCTION, 'actionlist');
$xajax->processRequest();


include("haut.php");
if ($_SESSION['$codeverife'] == "ok")
{?>
<script type="text/javascript">xajax_tableau(1);</script>
<?php
require_once("../data.php");
	try
		{
		$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
		}
		catch (Exception $e)
		{
			 die('Erreur : ' . $e->getMessage());
		}
	$stm = $bdd->prepare('SELECT email FROM '.$table.'');
	$stm->execute();
	$nbmail = $stm->rowcount();
	$nbpage =  $nbmail / 20;
	$stm->closeCursor();
	echo "<p onabort='xajax_tableau(1);'>page  ";
	for ($i = 1; $i <= $nbpage + 1; $i++) 
	{
    		echo "<a href='#' onclick='xajax_tableau(".$i.");'>".$i."</a>  ";
	}
}
include("bas.php");
?>

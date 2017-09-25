<?php
require_once "../data.php";

require_once "../mail.php";

function VerifeForm($sujet, $text, $type)
{
	$reponse = new xajaxResponse();
	if ($sujet == "")
	{
		$text_retour = "Le sujet est vide !! <br>";
	}
	if ($text == "")
	{
		$text_retour = $text_retour."Le message est vide !! <br>";
	}
	if ( $sujet != "" AND $text != "" AND $type == "test")
	{
		$text_retour = "Un Email de test viens de vous être envoyé.";
	}
	if ( $sujet != "" AND $text != "" AND $type == "go")
	{
		$text_retour = "L'envoi à votre liste est en cour cette opération peut prendre du temps.";
	}
	$reponse->assign('block', 'innerHTML', "<p>".$text_retour."</p>");
	return $reponse;
}

function EnvoiMail($sujet, $text, $type)
{
	require "../data.php";
	if ( $sujet != "" AND $text != "" AND $type == "test")
	{
		news_mail($mymail, $sujet, $text);
	}
	if ( $sujet != "" AND $text != "" AND $type == "go")
	{
		try
		{
		$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
		}
		catch (Exception $e)
		{
			 die('Erreur : ' . $e->getMessage());
		}
		@ignore_user_abort(true);
		@set_time_limit(3600);
		news_maillist($bdd, $sujet, $text);
	}	

}

try
{
$bdd = new PDO('mysql:host='.$bddserver.';dbname='.$bddname.';charset=utf8', $bdduser, $bddpassword);
}
catch (Exception $e)
{

        die('Erreur : ' . $e->getMessage());

}
require_once('./xajax_core/xajax.inc.php');

session_start();

$xajax = new xajax();

$xajax->register(XAJAX_FUNCTION, 'VerifeForm');
$xajax->register(XAJAX_FUNCTION, 'EnvoiMail');

$xajax->processRequest();

include("haut.php");

if ($_SESSION['$codeverife'] == "ok")
{
?>
	<script src="../tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	tinyMCE.init({
	selector: 'textarea',
	language: 'fr_FR',
	body_id: 'n_text',
	menubar: false,
plugins: [
    'autolink lists link image charmap preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'],
	toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
toolbar2: 'preview | forecolor backcolor emoticons',
	});
</script>
	<div>
	        <label for="nom">Sujet :</label>
	        <input type="text" name="sujet" id="sujet"/>
	</div>
	<br>
	<div>
		<textarea id="n_text" name="n_text"></textarea>
	</div>
	<div>
		<center>
		<br>
		<input name="envoie" type="button" value="Envoyer a la liste" onclick="xajax_VerifeForm(document.getElementById('sujet').value, tinyMCE.get('n_text').getContent(), 'go');xajax_EnvoiMail(document.getElementById('sujet').value, tinyMCE.get('n_text').getContent(), 'go');"/>
		<input name="preview" type="button" value="Envoyer un mail de test" onclick="xajax_VerifeForm(document.getElementById('sujet').value, tinyMCE.get('n_text').getContent(), 'test'); xajax_EnvoiMail(document.getElementById('sujet').value, tinyMCE.get('n_text').getContent(), 'test');"/>
		</center>
	</div>

<?php
}
include("bas.php");
?>


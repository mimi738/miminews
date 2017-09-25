<?php
function sign_mail($table, $mailto, $bdd, $adresse_dossier)
{
	$stm = $bdd->prepare('SELECT code FROM '.$table.' WHERE email=?');
	$stm->execute(array($mailto));
	$donnees = $stm->fetch();
	$sign = "<p><br />---------------<br /> <br />Pour vous désinscrire à la lettre d'information il vous suffit de cliquer <a href=http://". $adresse_dossier ."index.php?email=". $mailto ."&code=". $donnees['code'] ."&delete=1>ICI</a>.</p>";
	$stm->closeCursor();
	return $sign;
}

function news_mail($mailto, $sujet, $message_txt)
{
        require "PHPMailer/PHPMailerAutoload.php";
	require "data.php";
        $mail = new PHPmailer();
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = $mail_Smtpserver;  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $mail_Username;                 // SMTP username
	$mail->Password = $mail_Password;                           // SMTP password
	$mail->SMTPSecure = $mail_SMTPSecure;                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = $mail_Port;                                    // TCP port to connect to

	$mail->setFrom($mailreply, $namenewsletter);
	$mail->addAddress($mailto);
	$mail->Subject = utf8_decode($sujet);
	$mail->Body    = utf8_decode($message_txt);
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	$mail->send();
        $mail->SmtpClose();
        unset($mail);
}

function news_maillist($bdd, $sujet, $message_txt)
{
	require "data.php";
	$stm = $bdd->query('SELECT email, code FROM \''.$table.'\' WHERE verife=\'ok\'');

	while ($donnees = $stm->fetch())
	{
		$sign = "<p><br />---------------<br /> <br />Pour vous désinscrire à la lettre d'information il vous suffit de cliquer <a href=http://". $adresse_dossier ."index.php?email=". $donnees['email'] ."&code=". $donnees['code'] ."&delete=1>ICI</a>.</p>";
		news_mail($donnees['email'], $sujet, $message_txt.$sign);
	}
	$stm->closeCursor();
}




<?php echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n"; ?>
<?php
header("Access-Control-Allow-Origin: *");  
error_reporting(~0);
ini_set('file_uploads','On');
ini_set('post_max_size','150M');
ini_set('upload_max_filesize','150M');
ini_set('memory_limit','132M');
ini_set('default_socket_timeout',560);

 if(!empty($_FILES)){
	$files=glob("*.pdf");
	foreach($files as $file){
		if(is_file($file))
		unlink($file);
	}
	$mailerData=json_decode($_POST['mailerData']); 
    $fname = 'Invoice_'.$mailerData->billId.'_'.str_replace('/','-',$mailerData->billDate).'.pdf';
	$moved=move_uploaded_file($_FILES['data']['tmp_name'],$fname);
	include 'class.phpmailer.php';
	include 'class.smtp.php';
	include 'invoice-template.php';
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPDebug = 1;
	$mail->SMTPAuth = true;
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465;
	$mail->SMTPSecure = 'ssl';
	$mail->Username = "pazhanamcorp@gmail.com";
	$mail->Password = "pazhanam@corp";
	$mail->setFrom('pazhanamcorp@gmail.com',$mailerData->companyName);
	$mail->addAddress($mailerData->accountEmail, $mailerData->accountName);
	$mail->Subject = 'Invoice #'.$mailerData->billId.' on '.str_replace('/','-',$mailerData->billDate);
	$mail->isHTML(true);	
	$mail->Body =$template;
	$mail->addAttachment($fname);
	if (!$mail->send()) {
		echo 0;
	} else {
		echo 1;
	}
	
} 
exit(0);
?>

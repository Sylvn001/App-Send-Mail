<?php
//PHPMailer Import
require "./libraries/PHPMailer/Exception.php";
require "./libraries/PHPMailer/OAuth.php";
require "./libraries/PHPMailer/PHPMailer.php";
require "./libraries/PHPMailer/POP3.php";
require "./libraries/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


    //print_r($_POST);
    class Message{
        private $to = null; 
        private $subjectMatter = null;
        private $message = null; 
        public  $status = array( 'code' => null, 'description' => '');


        public function __get($attr){
            return $this->$attr;
        }
        public function __set($attr, $value){
            $this->$attr = $value;
        }
        public function validMessage(){
            if(empty($this->to) || empty($this->subjectMatter) || empty($this->message))
                return false;
            else return true;
        }
    }
    $message = new Message();
    $message->__set('to' , $_POST['to']);
    $message->__set('subjectMatter' , $_POST['subjectMatter']);
    $message->__set('message' , $_POST['message']);
    //echo print_r($message);
     if(!$message->validMessage() ){
         echo 'Mensagem não é valida';
         header('Location: index.php');
           die();
     }
     $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = false;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = "teste464email@gmail.com";          // SMTP username
        //CREATE A TEST EMAIL   
        $mail->Password = 'Aabc1234';                         // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        //se vc deseja mudar o destinatario ou o remetente apenas basta alterar os emails 
        //if you want to change the destination or the sender just change the e-mails
        $mail->setFrom('teste464email@gmail.com', 'SendMail ');
        $mail->addAddress($message->__get('to'), 'Destinatario');     // Add a recipient
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $message->__get('subjectMatter');
        $mail->Body    = $message->__get('message');
        $mail->AltBody = 'É Necessario usar um cliente que suporte HTML para ter acesso ao contéudo dessa mensagem.';

        $mail->send();

        $message->status['code'] = 1; 
        $message->status['description'] = 'Email enviado com sucesso';
    } catch (Exception $e) {
        $message->status['code'] = 2; 
        $message->status['description'] = 'Não foi possivel enviar este E-mail. Por favor, tente novamente.'. 'Detalhes: ' . $mail->ErrorInfo;
    }

?>


<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>

	<body>

		<div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

			<div class="row">
				<div class="col-md-12">

					<?php if($message->status['code'] == 1) { ?>

						<div class="container">
							<h1 class="display-4 text-success">Sucesso</h1>
							<p><?= $message->status['description'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

					<?php if($message->status['code'] == 2) { ?>

						<div class="container">
							<h1 class="display-4 text-danger">Ops!</h1>
							<p><?= $message->status['description'] ?></p>
							<a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

				</div>
			</div>
		</div>

	</body>
</html>

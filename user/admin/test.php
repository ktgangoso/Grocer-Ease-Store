<?php
// session_start();

// use PHPMailer\PHPMailer\PHPMailer;


require '..\vendor\phpmailer\phpmailer\src\PHPMailer.php';

date_default_timezone_set("Asia/Manila");
$datenow = date("Y-m-d H:i:s");
$function = $_POST['function'];

// mailer

    if ($function == "zendmail")
    {
        $address=$_POST['address'];
        zendmail($address);
    }

//mailer

/////////////////////////////////////////////

// Mailer
    function zendmail($address)
    {
         require ("assets/mailer/src/Exception.php");
         require ("assets/mailer/src/PHPMailer.php");
         require ("assets/mailer/src/SMTP.php");

         include 'dbcon.php';
        
         $mail = new PHPMailer\PHPMailer\PHPMailer(true);


         try 
         {
             //Server settings
             $mail->SMTPDebug = 0; 
             $mail->isSMTP();
             $mail->Host       = 'www.glaciercentraldata.com'; 
             $mail->SMTPAuth   = true;
             $mail->Username   = 'wms_notifications@glaciercentraldata.com'; 
             $mail->Password   = 'P@55w0rd123';
             $mail->SMTPSecure = 'ssl';  
             $mail->Port       = 465;  

             $body = file_get_contents('assets/mailer/format/index.php');

             $body = str_replace("###BEFOREDATE####",date("l jS \of F Y h:i:s A"),$body);

             $mail->setFrom('wms_notifications@glaciercentraldata.com', 'WMS Mailer');
             $mail->addAddress($address);
             $mail->addReplyTo('noreply@glaciercentraldata.com', 'Mailer');
             $mail->isHTML(true); 
             $mail->Subject = 'WMS Mailer';
             $mail->Body    = $body;
             $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

             $mail->send();
             echo 'true';
         } 
         catch (Exception $e) 
         {
             echo $e;
         }
    }

 // Mailer
?>
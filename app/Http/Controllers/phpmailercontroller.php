<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class phpmailercontroller extends Controller
{
    public function sendEmail (Request $request) {
  
        require '../vendor/autoload.php'; // load Composer's autoloader

        $mail = new PHPMailer(true); // Passing `true` enables exceptions

        try {

            // Mail server settings

            $mail->SMTPDebug = 4; // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'yamanworkshahbandar@gmail.com'; // SMTP username
            $mail->Password = "\$_POST!'Yamahn'!"; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            $mail->setFrom('yamanworkshahbandar@gmail.com', 'Yaman Shahbandar');
            $mail->addAddress('yamanshahbandar4@gmail.com'); // Add a recipient, Name is optional
            //$mail->addReplyTo('yamanworkshahbandar@gmail.com', 'Yaman Shahbandar');

            $mail->isHTML(true); // Set email format to HTML

            $mail->Subject = 'Verification Code';
            $mail->Body    = 'Your verification code is: 1234';
            // $mail->AltBody = plain text version of your message;

            if( !$mail->send() ) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                return 'Message has been sent';
            }
        } catch (Exception $e) {
             return back()->with('error','Message could not be sent.');
        }

        
    }   
}

<?php
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST["subject"];
    $message = "Name: " . $_POST['name'] . "<br>" .
        "Email: " . $_POST['Email'] . "<br><br>" .
        $_POST["message"];

    $receiver = 'Cs@TexasSpecializedQuartz.com';
    $sender = 'inquiry@TexasSpecializedQuartz.com';
    $passwd = 'Cs@texasspecializedquartz#1';

    $mail = new PHPMailer(true);
    $response = [];

    try {
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = $sender;
        $mail->Password = $passwd;

        $mail->setFrom($sender, 'Contact Form');
        $mail->addAddress($receiver);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($mail->send()) {
            // حفظ الإيميل في الـ Sent
            if (save_mail($mail, $sender, $passwd)) {
                $response['success'] = true;
                $response['message'] = 'Email sent and saved successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Email sent, but failed to save in Sent folder.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Unable to send email.';
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

function save_mail($mail, $sender, $passwd)
{
    $username = $sender;
    $password = $passwd;
    $mailboxName = 'INBOX.Sent';
    $path = '{imap.hostinger.com:993/imap/ssl/novalidate-cert}INBOX.Sent';

    $mail_string = $mail->getSentMIMEMessage();
    $imapStream = imap_open($path, $username, $password);

    if ($imapStream === false) {
        return false;
    }

    $result = imap_append($imapStream, $path, $mail_string);
    imap_close($imapStream);
    return $result;
}
?>

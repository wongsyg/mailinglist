<?php

error_reporting(E_STRICT | E_ALL);

date_default_timezone_set('Etc/UTC');

require './PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
$mail->Port = 465;
$mail->Username = 'lithanm6@gmail.com';
$mail->Password = 'H245hyt12';
$mail->setFrom('list@example.com', 'List manager');
$mail->Subject = "PHPMailer Simple database mailing list test";
$mail->isHTML(TRUE);
$mail->Body = '<html>This is a great day for all of <strong>US</strong>.</html>';

//Connect to the database and select the recipients from your mailing list that have not yet been sent to
$mysql = mysqli_connect('localhost', 'root', '');
mysqli_select_db($mysql, 'mail');
$result = mysqli_query($mysql, 'SELECT * FROM mailinglist');

foreach ($result as $row) { //This iterator syntax only works in PHP 5.4+
    $mail->addAddress($row['email'], $row['name']);
    if (!$mail->send()) {
        echo "Mailer Error (" . str_replace("@", "&#64;", $row["email"]) . ') ' . $mail->ErrorInfo . '<br />';
        break; //Abandon sending
    } else {
        echo "Message sent to :" . $row['name'] . ' (' . str_replace("@", "&#64;", $row['email']) . ')<br />';
    }
    // Clear all addresses and attachments for next loop
    $mail->clearAddresses();
}

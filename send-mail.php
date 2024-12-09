<?php
require "./vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$email = $_POST["email"];
$name = $_POST["name"];
$message = $_POST["text"];

// echo je pro kontrolu funkcnosti souboru
// echo $email . ", " . $name . ", " . $message;

$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

$name = htmlspecialchars(trim($_POST["name"]), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars(trim($_POST["text"]), ENT_QUOTES, 'UTF-8');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header("Location: ./index.html");
  exit();
}

if (!preg_match("/^[a-zA-Zá-žÁ-Ž\s]+$/", $name)) {
  header("Location: ./index.html");
  exit();
}

if (strlen($message) < 10) {
  header("Location: ./index.html");
  exit();
}

try {
  $mail = new PHPMailer(true);
  // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->Host = "smtp.gmail.com";
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  $mail->Username = "sykoramarosh@gmail.com";
  $mail->Password = "sbgwioruewlvmvcv";
  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';
  $mail->setFrom($email, $name);
  $mail->addAddress("recipient@mail.com");
  $mail->Subject = "Email from your website";
  $mail->isHTML(true);
  $mail->Body = "<p>You have received a new message from your website
  contact form.
  </p>
  <p><strong>Name:</strong> $name</p>
  <p><strong>Email:</strong> $email</p>
  <p><strong>Message:</strong><br> $message</p>";
  $mail->AltBody = "You have received a new message from your website
  contact form.\n\n" .
    "Name: $name\n" .
    "Email: $email\n\n" .
    "Message:\n$message";
  $mail->send();
  // echo "Message has been sent";
  header("Location: ./index.html?status=success");
  exit();
} catch (Exception $e) {
  // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  header("Location: ./index.html?status=error");
  exit();
}

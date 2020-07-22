<?php
if (isset($_POST["problem"]) && isset($_POST["subject"]) && isset($_POST["form_message"]) && isset($_POST["btn_submit_form"])) {
  $type = htmlspecialchars($_POST["problem"], ENT_COMPAT, 'UTF-8');
  $subjectForm = htmlspecialchars($_POST["subject"], ENT_COMPAT, 'UTF-8');
  $messageForm = htmlspecialchars($_POST["form_message"], ENT_COMPAT, 'UTF-8');
  $email = htmlspecialchars($_POST["email"], ENT_COMPAT, 'UTF-8');
  $date = date("Y-m-d G:i:s");
  $token = bin2hex(random_bytes(15));

  $subject = "Ticket #".$token."";
  $message = "<h1>New ticket has been opened with ID: ".$token." , on ".$date."</h1>";
  $message .= "<span>Ticket has been sent by: ".$email.". Category: ".$type." and his issue briefly described as: ".$subjectForm."</span>";
  $message .= "<p>Here is his full message: <br> ".$messageForm."</p>";

  $header = "From: Ticket handler \r\n";
  $header .= "Content-type: text/html \r\n";

  mail("support.spacesabres@spacesabres.com", $subject, $message, $header);

  $subjectAuto = "Status of your ticket with id #".$token.".";
  $messageAuto = "<h2>THIS IS AN AUTOMATED MESSAGE, DO NOT REPLY TO THIS EMAIL!</h2>";
  $messageAuto .= "<p>Your ticket has been successfully sent and we will now try to process it as fast as we can! Maximum reply time is approx: 48 Hours</p>";
  $messageAuto .= "<p>Your Spacesabres team.</p>";

  $headerAuto = "From: Spacesabres <noreply@spacesabres.com> \r\n";
  $headerAuto .= "Content-type: text/html \r\n";

  mail($email, $subjectAuto, $messageAuto, $headerAuto);
  header("location: index.php?result=success");
} else {
  header("location: index.php");
}
 ?>

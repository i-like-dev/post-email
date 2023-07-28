<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $to = "info@walking-cat.com";
  $subject = $_POST["subject"];
  $content = $_POST["content"];
  $email = $_POST["email"];
  $file = $_FILES["attachment"];

  $headers = "From: $email";

  if ($file["error"] === 0) {
    $attachment_path = $file["tmp_name"];
    $attachment_name = $file["name"];
    $attachment_type = $file["type"];

    $file_data = file_get_contents($attachment_path);
    $file_data = chunk_split(base64_encode($file_data));

    $boundary = md5(time());

    $headers .= "\r\nMIME-Version: 1.0";
    $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"$boundary\"";
    $message = "--$boundary\r\n";
    $message .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= "$content\r\n";
    $message .= "--$boundary\r\n";
    $message .= "Content-Type: $attachment_type; name=\"$attachment_name\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n";
    $message .= "Content-Disposition: attachment; filename=\"$attachment_name\"\r\n\r\n";
    $message .= "$file_data\r\n";
    $message .= "--$boundary--";

    mail($to, $subject, $message, $headers);
  } else {
    $headers .= "\r\nContent-type: text/plain; charset=UTF-8";
    mail($to, $subject, $content, $headers);
  }
}
?>

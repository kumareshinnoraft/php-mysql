<?php

namespace App\Entity;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 *  Sending mail to proper email id with proper message is the responsible
 *  of this class.
 * 
 *  @method sendMail()
 *    This method is used for sending the mail.
 *  
 *  @author Kumaresh Baksi <kumaresh.baksi@innoraft.com>
 */
class ValidateEmail
{
  /**
   *  sendEmail function send a mail acording to the parameter provided.
   *
   *  @param  mixed $email
   *    This is where we have to send the mail.
   *  @param  mixed $link
   *    This contains the link which will be send in the body, it can be a
   *    OTP as well.
   *  @param  mixed $msg
   *    This will contain the message which will be included in the body.
   * 
   *  @return boolean
   *    If mail is send succesfully returns TRUE instead FALSE.
   * 
   */
  public function sendEmail(string $email, string $link, string $msg)
  {
    // Creating the object of the PHPMailer.
    $mail = new PHPMailer(TRUE);

    try {
      $mail->isSMTP();

      // Setting host
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = TRUE;

      // Setting username and password from GMAIL SMTP server
      $mail->Username = 'ajanatech0@gmail.com';
      $mail->Password = 'kixloqvyfjhijtoi';

      // This tls encrypt the whole SMTP process.
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      $mail->setFrom('ajanatech0@gmail.com', 'Innoraft');
      $mail->addAddress("$email", '');

      // isHTML function in PHPMailer allows you
      $mail->isHTML(TRUE);
      $mail->Subject = 'Innoraft';
      $mail->Body = "$msg . $link";

      // If send function returns TRUE, showing user a positive
      // response instead show the failed message.
      if ($mail->send()) {
        return TRUE;
      }
    } catch (Exception $e) {
      echo "Message could not be sent";
    }
    return FALSE;
  }
}
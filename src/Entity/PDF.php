<?php

namespace App\Entity;

use App\Controller\FormController;
use FPDF;

/**
 *   This class is used to create a PDF using PHP package FPDF.
 * 
 *   @method downloadPdf()
 *     It generate the pdf for the user.
 *  
 *   @author Kumaresh Baksi <kumaresh.baksi@innoraft.com>
 */
class PDF
{  
  /**
   *  pdf is the object of this class FPDF
   *
   *  @var object
   */
  private $pdf;
  /**
   *  Constructor is used to initilize the object FPDF
   *  FPDF is a package that creats a PDF using PHP.
   *
   *  @return void
   */
  public function __construct()
  {
    $this->pdf = new FPDF();
  }

  
  /**
   *  downloadPdf function creates a PDF file with these parameters.
   *
   *  @param  string $firstName
   *    firstname of the user.
   *  @param  string $lastName
   *    lastname of the user
   *  @param  string $phNum
   *    phone number of the user.
   *  @param  array $subjects
   *    all subjects insered by the user.
   *  @param  array $marks
   *    marks number insered by the user.
   *  @param  string $email
   *    email inserted by the user.
   *  @param  string $userName
   *    username which will help to find the image for this user.
   * 
   *  @return string
   *    this method simply returns a confirmation string.
   */
  public function downloadPdf(string $firstName, string $lastName, string $phNum, array $subjects, array $marks, string $email, string $userName)
  {
    // ADD a page
    $this->pdf->AddPage();

    // Set a font
    $this->pdf->SetFont('Arial', 'B', 16);  
    $imagePath = "http://" . $_SERVER["SERVER_NAME"] . FormController::IMAGE_PATH . $userName . ".jpg";

    // Setting the image
    $this->pdf->Image($imagePath, 10, 10, 100, 50, "", "");

    // Setting the full name
    $this->pdf->Text(10, 70, "Hey, I'm $firstName" . $lastName);
    $this->pdf->Text(10, 80, "My marks -- ");

    // Creating space from top
    $this->pdf->Cell(80, 90, "", 0, 1, 'C', FALSE, "");

    // Set subjects from subjects array in PDF
    for ($i = 0; $i < count($subjects); $i++) {
      if ($i == count($subjects) - 1) {
        $this->pdf->Cell(0, 10, $subjects[$i], 1, 1, 'C');
      } else {
        $this->pdf->Cell(50, 10, $subjects[$i], 1, 0, 'C');
      }
    }

    // Set marks from marks array in PDF
    for ($i = 0; $i < count($marks); $i++) {
      if ($i == count($marks) - 1) {
        $this->pdf->Cell(0, 10, $marks[$i], 1, 1, 'C');
      } else {
        $this->pdf->Cell(50, 10, $marks[$i], 1, 0, 'C');
      }
    }

    // Set text in PDF
    $this->pdf->Text(10, 140, "Contact details --");
    $this->pdf->Text(10, 150, "$phNum");
    $this->pdf->Text(10, 160, "$email");

    $fileName = FormController::IMAGE_PATH . $userName . ".pdf";

    // Send to browser and Download
    $this->pdf->Output("myportfolio.pdf", 'D');
    // Send to browser and Save to location
    $this->pdf->Output("$fileName", 'F');
    $this->pdf->Output("myportfolio.pdf", 'I');

    return "Downloaded";
  }
}
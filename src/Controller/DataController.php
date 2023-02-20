<?php

namespace App\Controller;

use App\Entity\Cookie;
use App\Entity\Cryptography;
use App\Entity\OTP;
use App\Entity\UserData;
use App\Entity\UserDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  DataController class manages routes from login to register.
 *  It provides methods to common features needed in controllers.
 *
 *  @package Doctrine
 *  @subpackage ORM
 * 
 *  @author Kumaresh Baksi <kumaresh.baksi@innoraft.com>
 */
class DataController extends AbstractController
{
  /**
   *  Entity Manager class object that manages the persistence and 
   *  retrieval of entity objects from the database.
   * 
   *  @var object
   */
  public $em;

  /**
   *  UserData entity class that stores user credentials like
   *  username, email and password.
   * 
   *  @var object
   */
  public $userData;

  /**
   *  This object is used to store and retrive cookie. 
   *
   *  @var object
   */
  private $cookie;

  /**
   *  Cryptography object encode and decode values before
   *  sending in link or storing password.
   *
   *  @var object
   */
  private $cryptography;

  /**
   *  Constructor is initilizing the objects.
   *
   *  @param object $em
   *    EntityManagerInterface is used to manage entity with database
   *    it hepls to alter databse easyly.
   *    
   *  @return void
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em           = $em;
    $this->userData     = new UserData();
    $this->cookie       = new Cookie();
    $this->cryptography = new Cryptography();
  }

  /**
   *  This controller manages all user details and show in the view.
   *   
   *  @Route("/home", name="user_home")
   *    This route goes to home page of the project
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    Reponse the view which contains user stored information
   *    
   */
  public function index(Request $request): Response
  {

    // Get the error message
    $error = $request->get('errorMsg');

    // Checks user status, if it is active then forward user to login screen
    if (!$this->cookie->isActive($request)) {
      return $this->redirectToRoute('login_form');
    }

    // Getting username from the cookie
    $userName = $this->cookie->getCookie("username", $request);

    // We need the user table because we have to send data to frontend as well
    $userRow = $this->em->getRepository(UserData::class)->findOneBy(['userName' => $userName]);

    // User details table
    $userRowDetails = $this->em->getRepository(UserDetails::class)->findOneBy(['userName' => $userName]);

    // If email is not verified, these message will be shown
    $msg = "Email_not_verified";

    // Get the mail from the user
    if ($userRow != NULL) {
      $msg = $userRow->getEmail();
    }

    $stringVersionofMarks    = "";
    $stringVersionofSubjects = "";

    // Extracting marks and subjects from 
    if ($userRowDetails != NULL) {
      $stringVersionofMarks    = implode(',', $userRowDetails->getMarks());
      $stringVersionofSubjects = implode(',', $userRowDetails->getSubjects());
    }

    $edit = $request->get('edit');

    // If edit value is 1, view mode will be shown.
    if ($edit == 1) {
      if ($userRowDetails != NULL) {
        return $this->render(
          'form/home.html.twig',
          [
            "msg"       => "Welcome $userName",
            "firstname" => $userRowDetails->getFirstName(),
            "lastname"  => $userRowDetails->getLastName(),
            "image"     => $userRowDetails->getImage(),
            "marks"     => $stringVersionofMarks,
            "subjects"  => $stringVersionofSubjects,
            "phone"     => $userRowDetails->getPhoneNumber(),
            "email"     => $msg,
            "viewMode"  => TRUE
          ]
        );
      }
    }
    // It shows the edit mode of the input.
    return $this->render(
      'form/home.html.twig',
      [
        "msg"      => "Welcome $userName",
        "errorMsg" => $error,
        "email"    => $msg,
      ]
    );
  }

  /**
   *  This controller is used to reset OTP data in table.
   *   
   *  @Route("/removeOtps", name="removeOtps")
   *    This route is made to reset the OTP values from the database.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    Confirmation message of resetting OTP to value 0;
   *    
   */
  public function removeOtp(Request $request): Response
  {
    $userRows = $this->em->getRepository(OTP::class)->findAll();
    foreach ($userRows as $data) {
      $data->setValue(0);
    }

    // Updating the datbase.
    $this->em->flush();

    return new Response("REST");
  }
}
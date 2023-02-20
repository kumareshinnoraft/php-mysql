<?php

namespace App\Controller;

use App\Entity\Cookie;
use App\Entity\Cryptography;
use App\Entity\OTP;
use App\Entity\PDF;
use App\Entity\UserData;
use App\Entity\UserDetails;
use App\Entity\ValidateEmail;
use App\Entity\ValidateForm;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  FormController class manages routes after login & register. Userdetails 
 *  form and view and edit mode is managed by this controller
 *  It provides methods to common features needed in controllers.
 *  
 *  @package Doctrine
 *  @subpackage ORM
 * 
 *  @author Kumaresh Baksi <kumaresh.baksi@innoraft.com>
 */
class FormController extends AbstractController
{
  private const RESET_PASSWORD_LINK = "/resetyourpassword?&id=";
  public const  IMAGE_PATH          = "/img/";
  public const  PDF_PATH            = "/pdf/";
  private const VERIFY_ACCOUNT      = "Your one time password is ";
  private const RESET_PASSWORD_MSG  = "Please click the link for reseting your password ";

  /**
   *  Entity Manager class object that manages the persistence and 
   *  retrieval of entity objects from the database.
   * 
   *  @var object
   */
  private $em;

  /**
   *  UserData entity class that stores user credentials like
   *  username, email and password.
   * 
   *  @var object
   */
  private $userData;

  /**
   *  UserDetails entity class that stores user informations like
   *  firstname, lastname, subjects, marks, phone number.
   * 
   *  @var object
   */
  private $userDetails;

  /**
   *  cryptography object encode and decode values before
   *  sending in link or storing password.
   *
   *  @var object
   */
  private $cryptography;

  /**
   *  Validate email is class object which send the mail
   *
   *  @var object
   */
  private $validateEmail;

  /**
   *  This object is used to store and retrive cookie. 
   *
   *  @var object
   */
  private $cookie;

  /**
   *  ValidateForm is used to filter user data, it returns array
   *  on successful filter result.
   *
   *  @var object
   */
  private $validateForm;

  /**
   *  OTP is the Entity which stores username, otp and otp created at time.
   *
   *  @var object
   */
  private $otp;

  /**
   *  PDF is the object of class PDF which creates a custom PDF.
   *
   *  @var object
   */
  private $pdf;

  /**
   *  Constructor is initilizing the objects.
   *
   *  @param  mixed $em
   *    EntityManagerInterface is used to manage entity with database
   *    it hepls to alter databse easyly.
   *    
   *  @return void
   *    Contructor does not return anything insead it is used to initilize
   *    the object.
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em            = $em;
    $this->userData      = new UserData();
    $this->userDetails   = new UserDetails();
    $this->validateEmail = new ValidateEmail();
    $this->cryptography  = new Cryptography();
    $this->cookie        = new Cookie();
    $this->validateForm  = new ValidateForm();
    $this->otp           = new OTP();
    $this->pdf           = new PDF();
  }

  /**
   *  This controller stores user registered information and send mail.
   *   
   *  @Route("/register", name="save_form")
   *    This route send user to register page.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    Response register pages according to the input and send the
   *    mail to user email.
   */
  public function register(Request $request): Response
  {
    // Checks if user status, if it's active redirect user to home.
    if ($this->cookie->isActive($request)) {
      return $this->redirectToRoute('user_home');
    }

    // Getting user inserted values inside variables
    $userName = $request->get('username');
    $email    = $request->get('email');
    $password = $request->get('password');

    // findOneBy method return a row from database accrding to the email and username.
    $checkUser     = $this->em->getRepository(UserData::class)->findOneBy(['email' => $email]);
    $checkUserName = $this->em->getRepository(UserData::class)->findOneBy(['userName' => $userName]);

    if ($userName == NULL) {

      // If username is not present, return the same page to the user
      return $this->render('form/register.html.twig');

    } elseif ($checkUser != NULL) {

      // If $checkUser row is not NULL, it means an account the same
      // email is present.
      return $this->render(
        'form/register.html.twig',
        [
          'msg' => "Already have a account with this mail."
        ]
      );

    } elseif ($checkUserName != NULL) {

      // If email is new but username is present returns this.
      return $this->render(
        'form/register.html.twig',
        [
          'msg' => "Username is already taken, try others."
        ]
      );
    }

    // Encrypting password before storing in database
    $encodedPassword = $this->cryptography->encode($password);

    // Set the values in the userData object.
    $this->userData->setUserName($userName);
    $this->userData->setPassword(($encodedPassword));

    // For the first time user email will be stored as NULL
    // After confirming mail email will be stored in the database.
    $this->userData->setEmail('');

    // Store all values in database
    $this->em->persist($this->userData);
    $this->em->flush();

    // Generating a random number with prebuild method rand function
    $randomOtp = rand(1000, 9999);

    if ($this->validateEmail->sendEmail($email, $randomOtp, FormController::VERIFY_ACCOUNT)) {
      // Send mail method return boolean if mail is sent succesfully
      // generate date and time of creating the OTP.
      $currentTime = DateTimeImmutable::createFromFormat(DateTime::RFC3339, (new DateTime())->format(DateTime::RFC3339));

      // Set the values of the username, otp and currentTime.
      $this->otp->setUserName($userName);
      $this->otp->setValue($randomOtp);
      $this->otp->setCreatedAt($currentTime);

      // Store all values in database
      $this->em->persist($this->otp);
      $this->em->flush();

      // Storing user values inside cookies, but user field is empty
      // as user if not activate yet, it will be activated when
      // correct OTP will be inserted by the user.
      $values = [
        "user"     => "",
        "email"    => $email,
        "username" => $userName
      ];
      $this->cookie->setCookie($values);

      // Flag 1 shows user OTP interface including message.
      return $this->render(
        'form/register.html.twig',
        [
          'msg'  => "OTP send to your mail",
          "flag" => 1
        ]
      );
    }
    // If no condition is satisfied returns this page.
    return $this->render('form/register.html.twig');
  }

  /**
   *  This controller checks the user entered credentials and database
   *  credentials.
   *   
   *  @Route("/login", name="login_form")
   *    Login screen will be shown to the user.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    Response login page according to the input.
   */
  public function login(Request $request): Response
  {
    // If user if already exits redirect user to home page.
    if ($this->cookie->isActive($request)) {
      return $this->redirectToRoute('user_home');
    }

    // Getting username and password value for login
    $userName = $request->get('username');
    $password = $request->get('password');

    // The exact row matching with username entered by user,
    $userRow = $this->em->getRepository(UserData::class)->findOneBy(['userName' => $userName]);

    if ($userName == NULL) {

      // If username is NULL return the same page.
      return $this->render('form/login.html.twig');

    } elseif ($userRow == NULL) {

      // If no data with the username is present in databas returns 
      // Username and password is invalid or not registered
      return $this->render(
        'form/login.html.twig',
        [
          'msg' => "Password or username is not valid"
        ]
      );
    }

    // Decoding the password present in database
    $decodedPassword = $this->cryptography->decode($userRow->getPassword());

    if (!strcmp($password, $decodedPassword)) {

      // If user entered password and database password matched stores
      // cookies as user is active.
      $values = [
        "user"     => "active",
        "email"    => $userRow->getEmail(),
        "username" => $userName
      ];
      $this->cookie->setCookie($values);

      // redirect user to home page.
      return $this->redirectToRoute(
        'user_home',
        [
          "edit" => "1"
        ]
      );
    } else {

      // If password not matched return with the proper message.
      return $this->render(
        'form/login.html.twig',
        [
          'msg' => "Password is not valid"
        ]
      );
    }
  }

  /**
   *  This controller get the OTP entered by the user and matchs the 
   *  otp sent by system.
   *   
   *  @Route("/confirmaccount", name="confirmaccount")
   *    In this page user OTP will be checked.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    Response returns to home page and show message
   *    if password not matched.
   */
  public function confirmAccount(Request $request): Response
  {
    // Concat the OTP entered by the user from different input.
    $otp = $request->get('1') . $request->get('2') . $request->get('3') . $request->get('4');

    // fetch the username from the cookie
    $userName = $this->cookie->getCookie("username", $request);
    $email = $this->cookie->getCookie("email", $request);

    // fetch the row of the otp table by the username
    $otpRow = $this->em->getRepository(OTP::class)->findOneBy(['userName' => $userName]);

    // Checks if both OTP matches
    if ($otpRow->getValue() == $otp) {

      // If both OTP matches, email can be saved to UserData table.
      $selectedRow = $this->em->getRepository(UserData::class)->findOneBy(['userName' => $userName]);

      // Setting the mail as it is confirmed now.
      $selectedRow->setEmail($email);

      // Update the UserData table with email.
      $this->em->persist($selectedRow);
      $this->em->flush();

      // save user status to active, email and username.
      $values = [
        "user"     => "active",
        "email"    => $selectedRow->getEmail(),
        "username" => $selectedRow->getUserName()
      ];
      $this->cookie->setCookie($values);

      // On a successfull OTP matches redirect user to home page.
      return $this->redirectToRoute(
        'user_home',
        [
          "edit" => "1"
        ]
      );
    } else {
      // If OTP does not match return with this message.
      return $this->render(
        'form/register.html.twig',
        [
          'msg'  => "OTP not Matched.",
          'flag' => "1"
        ]
      );
    }
  }
  /**
   *  This controller takes the mail from the user and send mail
   *  to the email id with a reset password link.
   *   
   *  @Route("/resetpassword", name="forget_password_form")
   *    This page is for reseting password.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    If entered mail is not found in the database it returns
   *    the same page with a message.
   */
  public function forgetPassword(Request $request): Response
  {

    // If user is present this page will be inactive
    if ($this->cookie->isActive($request)) {
      return $this->redirectToRoute('user_home');
    }

    $email = $request->get('email');
    // Get the row of user with email
    $userRow = $this->em->getRepository(UserData::class)->findOneBy(['email' => $email]);

    if ($email == NULL) {

      // If not email is entered return the same page
      return $this->render('form/forget-password.html.twig');

    } else if ($userRow == NULL) {

      // If userRow is not present, returns mail not found.
      return $this->render(
        'form/forget-password.html.twig',
        [
          'msg' => "Mail not found"
        ]
      );

    }

    // Encrypting user id before sending mail.
    $id = $this->cryptography->encode($userRow->getId());

    if ($this->validateEmail->sendEmail($email, "http://" . $_SERVER['SERVER_NAME'] . FormController::RESET_PASSWORD_LINK . $id, FormController::RESET_PASSWORD_MSG)) {
      // If mail is sent succesfully store the email inside cookie
      $values = [
        "user"     => "",
        "email"    => $email,
        "username" => ""
      ];
      $this->cookie->setCookie($values);
      return $this->render(
        'form/forget-password.html.twig',
        [
          'msg' => "Reset password link is sent to your mail"
        ]
      );

    }
    return $this->render('form/forget-password.html.twig');
  }

  /**
   *  This controller matches both password and new password then 
   *  is store in the datbase
   *   
   *  @Route("/resetyourpassword", name="resetyourpassword")
   *    Reset paddword page will be shown to the user.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    If password is matches and succesfully stored in the database
   *    message will be shown that password is changed.
   */
  public function resetyourpassword(Request $request): Response
  {
    // Getting both password and new password field
    $newpassword = $request->get('newpassword');
    $password    = $request->get('password');

    // Fetching the value of the email from cookie
    $email = $this->cookie->getCookie("email", $request);

    if ($newpassword == NULL) {

      // If password is NULL return the same page
      return $this->render('form/reset-your-password.html.twig');

    } elseif ($newpassword != $password) {
      // If both password does not match, show error
      return $this->render(
        'form/reset-your-password.html.twig',
        [
          'error' => "Passwords are not matching try again"
        ]
      );
    }

    // Get the row of the user from user_data tabel 
    $selectedRow = $this->em->getRepository(UserData::class)->findOneBy(['email' => $email]);

    // Encoding the new password before storing it in the database.
    $encodedPassword = $this->cryptography->encode($newpassword);

    // checks if the user exits
    if ($selectedRow != NULL) {

      // Update the encoded password in the databse
      $selectedRow->setPassword($encodedPassword);
      $this->em->persist($selectedRow);
      $this->em->flush();

      // Storing cookie as user is active with an email and password
      $values = [
        "user"     => "active",
        "email"    => $selectedRow->getEmail(),
        "username" => $selectedRow->getUserName()
      ];
      $this->cookie->setCookie($values);
    }

    // Returning message that user password is changed
    return $this->render(
      'form/reset-your-password.html.twig',
      [
        'msg' => "Your password changed"
      ]
    );
  }

  /**
   *  This controller deletes all cookies and destroy sessions.
   *   
   *  @Route("/logout", name="logout")
   *    No page will be shown instead user will be forwarded to login page 
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    It redirect user to login page
   */
  public function logout(Request $request): Response
  {
    // Destroying session while logout.
    $session = $request->getSession();
    $session->invalidate();

    // Deleting the cookie value.
    if (isset($_COOKIE["user-info"])) {
      $this->cookie->removeCookie($request);
    }
    // Return user to login page
    return $this->redirectToRoute('login_form');
  }

  /**
   *  Save or update the user details in the user_details table
   *   
   *  @Route("/save", name="save_form_data")
   *    Saves user inserted details to user_details table.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter.
   * 
   *  @return Response
   *    If data is saved it disable the edit text to non editable.
   */
  public function save(Request $request): Response
  {
    // Geting the logged user username.
    $userName = $this->cookie->getCookie("username", $request);

    // Getting user form data
    $firstName   = $request->get('firstname');
    $lastName    = $request->get('lastname');
    $subTextArea = $request->get('subTextArea');
    $phone       = $request->get('phone');

    // Saving image with username and saving it the img directory
    foreach ($request->files as $uploadedFile) {
      $name = "$userName.jpg";
      $uploadedFile->move('../public/img', $name);
    }

    $imagePath = "http://" . $_SERVER['SERVER_NAME'] . FormController::IMAGE_PATH . $userName . ".jpg";

    // This function is a mixed type with returning an array and as well as string
    $msg = $this->validateForm->filterUserData($firstName, $lastName, $subTextArea);

    $subjects = [];
    $marks    = [];

    // Getting the row the userDetails table for this username
    $userRowDetails = $this->em->getRepository(UserDetails::class)->findOneBy(['userName' => $userName]);

    // If the $msg is an array, it means all validation is successful.
    if (is_array($msg)) {

      // Extracting subjects and marks.
      $subjects = $msg[0];
      $marks    = $msg[1];

      if ($userRowDetails != NULL) {

        // If use is not new and having userRowDetails, this block update
        // user details according to the data inserted by the user.
        $userRowDetails->setUserDetails($firstName, $lastName, $imagePath, $marks, $subjects, $phone, $userName);

        $this->em->persist($userRowDetails);
        $this->em->flush();
        $msg = "Values updated";
      } else {

        // If username is not present in the database we would store
        // the data for the first time.
        $this->userDetails->setUserDetails($firstName, $lastName, $imagePath, $marks, $subjects, $phone, $userName);

        $this->em->persist($this->userDetails);
        $this->em->flush();
        $msg = "Values saved";
      }
    } else {
      // If filterUserData returns a string it means user entered value in
      // a wrong way and $msg is sent which contains the message.
      // edit value 0 means it will turn on the edit mode.
      return $this->redirectToRoute(
        'user_home',
        [
          "errorMsg" => $msg,
          "edit"     => "0"
        ]
      );
    }
    // If no conditions satified it will go with the $msg and edit mode will be
    // 1, which is not editable.
    return $this->redirectToRoute(
      'user_home',
      [
        "errorMsg" => $msg,
        "edit"     => "1"
      ]
    );
  }

  /**
   *  This controller is called for downloading the pdf
   *   
   *  @Route("/download", name="download_pdf")
   *    This page downloads the PDF for the user.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter and cookie.
   * 
   *  @return Response
   *    If data is saved it disable the edit text to non editable.
   */
  public function download(Request $request)
  {
    $userName = $this->cookie->getCookie("username", $request);
    $email    = $this->cookie->getCookie("email", $request);

    $userRowDetails = $this->em->getRepository(UserDetails::class)->findOneBy(['userName' => $userName]);

    // Getting an array of user details
    $userDetails = $userRowDetails->getUserDetails();

    // Getting all values from the table of the user and passing it for download.
    $msg = $this->pdf->downloadPdf($userDetails["firstname"], $userDetails["lastname"], $userDetails["number"], $userDetails["subjects"], $userDetails["marks"], $email, $userName);

    return $this->render(
      'form/view.html.twig',
      [
        "msg"      => "Welcome $userName",
        "errorMsg" => $msg
      ]
    );
  }

  /**
   *  This controller is used to resend the otp
   *   
   *  @Route("/resendotp", name="resend_otp")
   *    Resend OTP route resend the otp to the mail.
   * 
   *  @param object $request
   *    Request object handles parameter from query parameter and cookie.
   * 
   *  @return mixed
   *    It generate a pdf
   */
  public function resedOtp(Request $request)
  {
    // Generating four digit random number
    $randomOtp = rand(1000, 9999);

    // Getting the value from cookie.
    $email     = $this->cookie->getCookie("email", $request);
    $userName  = $this->cookie->getCookie("username", $request);

    if ($this->validateEmail->sendEmail($email, $randomOtp, FormController::VERIFY_ACCOUNT)) {

      // If mail is send succesfully creates the time with date.
      $currentTime = DateTimeImmutable::createFromFormat(DateTime::RFC3339, (new DateTime())->format(DateTime::RFC3339));
      $otpRow      = $this->em->getRepository(OTP::class)->findOneBy(['userName' => $userName]);

      $values = [
        "user"     => "",
        "email"    => $email,
        "username" => $userName
      ];
      $this->cookie->setCookie($values);
      if ($otpRow != NULL) {
        // Update uername, otp and current time.
        $otpRow->setValue($randomOtp);
        $otpRow->setCreatedAt($currentTime);

        // Update the OTP in the table
        $this->em->persist($otpRow);
        $this->em->flush();
      }

      return $this->render(
        'form/register.html.twig',
        [
          'msg'  => "OTP send to your mail again",
          "flag" => 1
        ]
      );
    }
  }
}
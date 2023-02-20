<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\Request;

/**
 *  This class stores cookie in the browser also checks if user is loggedin
 *  or not and deletes the cookies when required.
 * 
 *  @method setCookie()
 *    Handling the cookie in the project.
 *  @method getCookie()
 *    Returns the cookie value.
 *  @method isActive()
 *    This method returns if user is active.
 *  @method removeCookie()
 *    Delete the cookie from the browser.
 * 
 *  @property object $cryptography
 *    Cryptography object is used to encode and decode values.
 *  @property object $request
 *    Request object is used to identify the quires.
 * 
 *  @author Kumaresh Baksi <kumaresh.baksi@innoraft.com>
 */
class Cookie
{
  /**
   *  This object encode and decode string.
   * 
   *  @var object
   */
  private $cryptography;
  /**
   *  Request variable reads the different request from
   *  different pages.
   *   
   *  @var object
   */
  private $request;
  /**
   *  Constructor is used to initilize the objects
   *
   *  @return void
   */
  public function __construct()
  {

    $this->cryptography = new Cryptography();
    $this->request      = new Request();

  }
  /**
   *  setCookie fucntion se the cookie in a serilized form.
   *
   *  @param  array $value
   *    Array value contain three parameeter, user, email and username.
   * 
   *  @return void
   */    
  public function setCookie(array $value)
  {
    // Serilizing the values.
    $serilizedValues = serialize($value);

    // Encoding the values.
    $encodedValue = $this->cryptography->encode($serilizedValues);

    // Setting cookie data for one month. 
    setcookie("user-info", $encodedValue, time() + (86400 * 30), "/");

  }
  /**
   *  getCookie function is used to extract the value and returns the value.
   *
   *  @param  string $name
   *    $name parameter is the key of the value user is requesting for.
   *  @param  object $request
   *    an request object understands in which page value is required.
   * 
   *  @return mixed
   *    if value of the key is found retun string instead boolean.
   */
  public function getCookie(string $name, object $request)
  {

    $cookies = $request->cookies;
    $encodedValue = $cookies->get("user-info");

    if (isset($encodedValue)) {
      $decodedValue = $this->cryptography->decode($encodedValue);
    } else {
      return FALSE;
    }

    // Unserilizing the values
    $unserilizedValue = unserialize($decodedValue);
    return $unserilizedValue[$name];
  }

  /**
   *  isActive function returns if user active or not
   *
   *  @param  object $request
   *    request object is taken from the user to indentify which
   *    page is requesting isActive method.
   * 
   *  @return boolean
   *    if user status is active function returns TRUE instead FALSE.
   */
  public function isActive(object $request)
  {
    $cookies = $request->cookies;
    $encodedValue = $cookies->get("user-info");

    // First check if cookie is present.
    if (isset($encodedValue)) {
      $decodedValue = $this->cryptography->decode($encodedValue);
      $unserilizedValue = unserialize($decodedValue);
      if ($unserilizedValue["user"] == "active") {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   *  removeCookie function is used to remove user data from cookie.
   *
   *  @param  object $request
   *    request is needed to get the cookies of the pages.
   * 
   *  @return void
   *    this function does not retuns anything as it just remove the user.
   */
  public function removeCookie(object $request)
  {
    $cookies = $request->cookies;
    if ($cookies->has("user-info")) {
      // Setting the data of the key user-info to FALSE.
      setcookie("user-info", FALSE, time() - (86400 * 30), "/");
    }
  }
}
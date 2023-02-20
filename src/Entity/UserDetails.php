<?php

namespace App\Entity;

use App\Repository\UserDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserDetailsRepository::class)]
class UserDetails
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = NULL;

  #[ORM\Column(length: 255)]
  private ?string $firstName = NULL;

  #[ORM\Column(length: 255)]
  private ?string $lastName = NULL;

  #[ORM\Column(length: 255)]
  private ?string $image = NULL;

  #[ORM\Column(type: Types::ARRAY )]
  private array $marks = [];

  #[ORM\Column(type: Types::ARRAY )]
  private array $subjects = [];

  #[ORM\Column(length: 255)]
  private ?string $phoneNumber = NULL;

  #[ORM\Column(length: 255)]
  private ?string $userName = NULL;
  
  /**
   *  getId is the primary key of the database.
   *
   *  @return int
   */
  public function getId(): ?int
  {
    return $this->id;
  }
  
  /**
   *  getFirstName is used to return user firstname
   *
   *  @return string
   */
  public function getFirstName(): ?string
  {
    return $this->firstName;
  }
  
  /**
   *  setFirstName set the use firstname
   *
   *  @param  string $firstName
   *  @return self
   */
  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;

    return $this;
  }
  
  /**
   *  getLastName is used to get the lastname of the user
   *
   *  @return string
   */
  public function getLastName(): ?string
  {
    return $this->lastName;
  }
  
  /**
   *  setLastName is used to set the user lastname
   *
   *  @param  string $lastName
   *  @return self
   */
  public function setLastName(string $lastName): self
  {
    $this->lastName = $lastName;

    return $this;
  }
  
  /**
   *  getImage return the string which contains path of the image.
   *
   *  @return string
   */
  public function getImage(): ?string
  {
    return $this->image;
  }
  
  /**
   *  setImage stores the path of the image stored in the project.
   * 
   *  @param  string $image
   *  @return self
   */
  public function setImage(string $image): self
  {
    $this->image = $image;

    return $this;
  }
  
  /**
   *  getMarks return the array contains marks.
   *
   *  @return array
   */
  public function getMarks(): array
  {
    return $this->marks;
  }
  
  /**
   *  setMarks set the array of the marks in objects.
   *
   *  @param  array $marks
   * 
   *  @return self
   */
  public function setMarks(array $marks): self
  {
    $this->marks = $marks;

    return $this;
  }
  
  /**
   *  getSubjects returns the array object of subjects
   *
   *  @return array
   */
  public function getSubjects(): array
  {
    return $this->subjects;
  }
  
  /**
   *  setSubjects is an array which stores user entered subjects.
   *
   *  @param  array $subjects
   * 
   *  @return self
   */
  public function setSubjects(array $subjects): self
  {
    $this->subjects = $subjects;

    return $this;
  }
  
  /**
   *  getPhoneNumber return the phone number in the form of string
   *
   *  @return string
   */
  public function getPhoneNumber(): ?string
  {
    return $this->phoneNumber;
  }
  
  /**
   *  setPhoneNumber is used to set the phone number in the object.
   *
   *  @param  string $phoneNumber
   *  @return self
   */
  public function setPhoneNumber(string $phoneNumber): self
  {
    $this->phoneNumber = $phoneNumber;

    return $this;
  }
  
  /**
   *  getUserName method returns username from the table.
   *
   *  @return string
   */
  public function getUserName(): ?string
  {
    return $this->userName;
  }
  
  /**
   *  setUserName is act as unique id in the database
   *
   *  @param  string $userName
   *  @return self
   */
  public function setUserName(string $userName): self
  {
    $this->userName = $userName;

    return $this;
  }
  
  /**
   *  setUserDetails function is setting the values in the database.
   *  
   *
   *  @param  mixed $firstName
   *    Firstname vairable is entered by the user.
   *  @param  mixed $lastName
   *    Lastname is the user lastname.
   *  @param  mixed $imagePath
   *    Image path is the location link where user image is stored. 
   *  @param  mixed $marks
   *    Marks is an array of user marks.
   *  @param  mixed $subjects
   *    Subject is an array of user subjects.
   *  @param  mixed $number
   *    This variable is the phone number of the user.
   *  @param  mixed $userName
   *    This varible is unique in the table user_details.
   * 
   *  @return void
   *    This function does not return anything instead it set the values
   *    in the database.
   */
  public function setUserDetails(string $firstName, string $lastName, string $imagePath, array $marks, array $subjects, string $number, string $userName){

    $this->setFirstName($firstName);
    $this->setLastName($lastName);
    $this->setImage($imagePath);
    $this->setMarks($marks);
    $this->setSubjects($subjects);
    $this->setPhoneNumber($number);
    $this->setUserName($userName);
    
  }  
  /**
   *  getUserDetails is the function which return the array of user
   *  data.
   *
   *  @return array
   *    array of the user is containing firstname, lastname, image path,
   *    marks, subjects, number and username.
   */
  public function getUserDetails(){
    return [
      "firstname" => $this->getFirstName(),
      "lastname"  => $this->getImage(),
      "image"     => $this->getLastName(),
      "marks"     => $this->getMarks(),
      "subjects"  => $this->getSubjects(),
      "number"    => $this->getPhoneNumber(),
      "username"  => $this->getUserName()
    ];
  }
}
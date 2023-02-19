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
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $firstName = null;

  #[ORM\Column(length: 255)]
  private ?string $lastName = null;

  #[ORM\Column(length: 255)]
  private ?string $image = null;

  #[ORM\Column(type: Types::ARRAY )]
  private array $marks = [];

  #[ORM\Column(type: Types::ARRAY )]
  private array $subjects = [];

  #[ORM\Column(length: 255)]
  private ?string $phoneNumber = null;

  #[ORM\Column(length: 255)]
  private ?string $userName = null;
  
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
}
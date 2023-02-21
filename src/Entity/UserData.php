<?php

namespace App\Entity;

use App\Repository\UserDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserDataRepository::class)]
class UserData
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = NULL;

  #[ORM\Column(length: 255)]
  private ?string $userName = NULL;

  #[ORM\Column(length: 255)]
  private ?string $email = NULL;

  #[ORM\Column(length: 255)]
  private ?string $password = NULL;

  /**
   *  getId is the auto generated primary key in database
   *
   *  @return int
   */
  public function getId(): ?int
  {
    return $this->id;
  }
  
  /**
   * getUserName is the function which returns username
   *
   * @return string
   */
  public function getUserName(): ?string
  {
    return $this->userName;
  }
  
  /**
   * setUserName sets the username in the object
   *
   * @param  mixed $userName
   * 
   * @return self
   */
  public function setUserName(string $userName): self
  {
    $this->userName = $userName;

    return $this;
  }
  
  /**
   * getEmail function return an email in the form of a string
   *
   * @return string
   */
  public function getEmail(): ?string
  {
    return $this->email;
  }
    
  /**
   * setEmail method set user mail in the object
   *
   * @param  mixed $email
   * 
   * @return self
   */
  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }
  
  /**
   * getPassword return encoded password.
   *
   * @return string
   */
  public function getPassword(): ?string
  {
    return $this->password;
  }
  
  /**
   * setPassword set an encoded password to object.
   *
   * @param  mixed $password
   * @return self
   */
  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }
}
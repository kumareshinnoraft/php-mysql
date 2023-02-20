<?php

namespace App\Entity;

use App\Repository\OTPRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OTPRepository::class)]
class OTP
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = NULL;

    #[ORM\Column(length: 255)]
    private ?string $userName = NULL;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = NULL;

    #[ORM\Column]
    private ?int $value = NULL;
    
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
     *  getUserName returns the username of the user.
     *
     *  @return string
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }
    
    /**
     *  setUserName set the username in the object.
     *
     *  @param  mixed $userName
     *  @return self
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }
    
    /**
     *  getCreatedAt is the time of OTP when it is created.
     *
     *  @return string
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }
    
    /**
     *  setCreatedAt is setting the time when OTP is created.
     *
     *  @param  mixed $created_at
     *  @return self
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
    
    /**
     *  getValue is the OTP.
     *
     *  @return int
     */
    public function getValue(): ?int
    {
        return $this->value;
    }
    
    /**
     *  setValue is setting the OTP value.
     *
     *  @param  mixed $value
     *  @return self
     */
    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
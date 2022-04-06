<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Post implements \JsonSerializable
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;
    
    #[ORM\Column]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank]
    private string $description;
    
    #[ORM\Column(type: "datetime")]
    #[Assert\NotNull]
    public ?\DateTimeInterface $create_date = null;
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * @return mixed
     */
    public function getCreateDate(): ?\DateTime
    {
        return $this->create_date;
    }
    
    /**
     * @param \DateTime $create_date
     * @return Post
     */
    public function setCreateDate(\DateTime $create_date): self
    {
        $this->create_date = $create_date;
        return $this;
    }
    
    /**
     * @throws \Exception
     * @ORM\PrePersist()
     */
    public function beforeSave(){
        
        $this->create_date = new \DateTime('now', new \DateTimeZone('Africa/Casablanca'));
    }
    
    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            "name" => $this->getName(),
            "description" => $this->getDescription()
        ];
    }
}
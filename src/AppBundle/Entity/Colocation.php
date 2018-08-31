<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Colocation
 *
 * @ORM\Table(name="colocation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ColocationRepository")
 */
class Colocation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="picture1", type="text", nullable=true)
     */
    private $picture1;

    /**
     * @var string
     *
     * @ORM\Column(name="picture2", type="text", nullable=true)
     */
    private $picture2;

    /**
     * @var string
     *
     * @ORM\Column(name="picture3", type="text", nullable=true)
     */
    private $picture3;

    /**
     * @var string
     *
     * @ORM\Column(name="picture4", type="text", nullable=true)
     */
    private $picture4;

    /**
     * @var string
     *
     * @ORM\Column(name="picture5", type="text", nullable=true)
     */
    private $picture5;

    /**
     * @var string
     *
     * @ORM\Column(name="picture6", type="text", nullable=true)
     */
    private $picture6;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added", type="datetime")
     */
    private $added;

    /**
     * @var float
     *
     * @ORM\Column(name="cost", type="float")
     */
    private $cost;

    /**
     * @var bool
     *
     * @ORM\Column(name="available",options={"default" : true}, type="boolean",nullable=true)
     */
    private $available;

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->available;
    }

    /**
     * @param bool $available
     */
    public function setAvailable(bool $available): void
    {
        $this->available = $available;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="number_of_individuals", type="integer")
     */
    private $nbpersonnes;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id",onDelete="CASCADE")
     */
    private $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getPicture1(): ?string
    {
        return $this->picture1;
    }

    /**
     * @param string $picture1
     */
    public function setPicture1(string $picture1): void
    {
        $this->picture1 = $picture1;
    }

    /**
     * @return string|null
     */
    public function getPicture2(): ?string
    {
        return $this->picture2;
    }

    /**
     * @param string $picture2
     */
    public function setPicture2(string $picture2): void
    {
        $this->picture2 = $picture2;
    }

    /**
     * @return string|null
     */
    public function getPicture3(): ?string
    {
        return $this->picture3;
    }

    /**
     * @param string $picture3
     */
    public function setPicture3(string $picture3): void
    {
        $this->picture3 = $picture3;
    }

    /**
     * @return string|null
     */
    public function getPicture4(): ?string
    {
        return $this->picture4;
    }

    /**
     * @param string $picture4
     */
    public function setPicture4(string $picture4): void
    {
        $this->picture4 = $picture4;
    }

    /**
     * @return string|null
     */
    public function getPicture5(): ?string
    {
        return $this->picture5;
    }

    /**
     * @param string $picture5
     */
    public function setPicture5(string $picture5): void
    {
        $this->picture5 = $picture5;
    }

    /**
     * @return string|null
     */
    public function getPicture6(): ?string
    {
        return $this->picture6;
    }

    /**
     * @param string $picture6
     */
    public function setPicture6(string $picture6): void
    {
        $this->picture6 = $picture6;
    }

    /**
     * @return \DateTime
     */
    public function getAdded(): \DateTime
    {
        return $this->added;
    }

    /**
     * @param \DateTime $added
     */
    public function setAdded(\DateTime $added): void
    {
        $this->added = $added;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getNbpersonnes(): int
    {
        return $this->nbpersonnes;
    }

    /**
     * @param int $nbpersonnes
     */
    public function setNbpersonnes(int $nbpersonnes): void
    {
        $this->nbpersonnes = $nbpersonnes;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}

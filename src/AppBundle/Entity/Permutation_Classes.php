<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permutation_Classes
 *
 * @ORM\Table(name="permutation__classes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Permutation_ClassesRepository")
 */
class Permutation_Classes
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
     * @var int
     *
     * @ORM\Column(name="niveau", type="integer")
     */
    private $niveau;

    /**
     * @var string|null
     *
     * @ORM\Column(name="specialite", type="string", length=255, nullable=true)
     */
    private $specialite;

    /**
     * @var int
     *
     * @ORM\Column(name="classe", type="integer")
     */
    private $classe;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $state;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set niveau.
     *
     * @param int $niveau
     *
     * @return Permutation_Classes
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau.
     *
     * @return int
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set specialite.
     *
     * @param string|null $specialite
     *
     * @return Permutation_Classes
     */
    public function setSpecialite($specialite = null)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite.
     *
     * @return string|null
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set classe.
     *
     * @param int $classe
     *
     * @return Permutation_Classes
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe.
     *
     * @return int
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set user.
     *
     * @param string $user
     *
     * @return Permutation_Classes
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return Permutation
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}

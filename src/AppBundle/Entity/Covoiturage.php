<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Covoiturage
 *
 * @ORM\Table(name="covoiturage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CovoiturageRepository")
 */
class Covoiturage
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
     * @ORM\Column(name="depart", type="string", length=255)
     */
    private $depart;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=255)
     */
    private $destination;

    /**
     * @var float
     *
     * @ORM\Column(name="cout", type="float")
     */
    private $cout;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="integer")
     */
    private $etat;

    /**
     * @var int
     *
     * @ORM\Column(name="fumeur", type="integer")
     */
    private $fumeur;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="string", length=255,nullable=true))
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="date")
     */
    private $datePublication;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


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
     * Set depart.
     *
     * @param string $depart
     *
     * @return Covoiturage
     */
    public function setDepart($depart)
    {
        $this->depart = $depart;

        return $this;
    }

    /**
     * Get depart.
     *
     * @return string
     */
    public function getDepart()
    {
        return $this->depart;
    }

    /**
     * Set destination.
     *
     * @param string $destination
     *
     * @return Covoiturage
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination.
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set cout.
     *
     * @param float $cout
     *
     * @return Covoiturage
     */
    public function setCout($cout)
    {
        $this->cout = $cout;

        return $this;
    }

    /**
     * Get cout.
     *
     * @return float
     */
    public function getCout()
    {
        return $this->cout;
    }

    /**
     * Set user.
     *
     * @param string $user
     *
     * @return Covoiturage
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
     * Set etat.
     *
     * @param int $etat
     *
     * @return Covoiturage
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat.
     *
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set fumeur.
     *
     * @param int $fumeur
     *
     * @return Covoiturage
     */
    public function setFumeur($fumeur)
    {
        $this->fumeur = $fumeur;

        return $this;
    }

    /**
     * Get fumeur.
     *
     * @return int
     */
    public function getFumeur()
    {
        return $this->fumeur;
    }

    /**
     * Set date.
     *
     * @param string $date
     *
     * @return Covoiturage
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set datePublication.
     *
     * @param \DateTime $datePublication
     *
     * @return Covoiturage
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication.
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }
    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Covoiturage
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

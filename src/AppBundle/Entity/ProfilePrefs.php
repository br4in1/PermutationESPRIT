<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ProfilePrefs")
 */
class ProfilePrefs
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id",onDelete="CASCADE")
     */

    private $user;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */

    private $phonenumbervisible;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */

    private $facebooklinkvisible;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function isPhonenumbervisible(): bool
    {
        return $this->phonenumbervisible;
    }

    /**
     * @param bool $phonenumbervisible
     */
    public function setPhonenumbervisible(bool $phonenumbervisible): void
    {
        $this->phonenumbervisible = $phonenumbervisible;
    }

    /**
     * @return bool
     */
    public function isFacebooklinkvisible(): bool
    {
        return $this->facebooklinkvisible;
    }

    /**
     * @param bool $facebooklinkvisible
     */
    public function setFacebooklinkvisible(bool $facebooklinkvisible): void
    {
        $this->facebooklinkvisible = $facebooklinkvisible;
    }
}

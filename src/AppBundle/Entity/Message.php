<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Messages")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="from", referencedColumnName="id",onDelete="CASCADE")
     */

    private $from;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="to", referencedColumnName="id",onDelete="CASCADE")
     */

    private $to;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private $content;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $sendingtime;

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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * @param int $from
     */
    public function setFrom(int $from): void
    {
        $this->from = $from;
    }

    /**
     * @return int
     */
    public function getTo(): int
    {
        return $this->to;
    }

    /**
     * @param int $to
     */
    public function setTo(int $to): void
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getSendingtime(): \DateTime
    {
        return $this->sendingtime;
    }

    /**
     * @param \DateTime $sendingtime
     */
    public function setSendingtime(\DateTime $sendingtime): void
    {
        $this->sendingtime = $sendingtime;
    }
}

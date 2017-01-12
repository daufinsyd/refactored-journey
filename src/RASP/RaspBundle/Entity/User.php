<?php
/*
 * Created by sydney_manjaro the 07/01/17
 */

namespace RASP\RaspBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="log_user")
 * @ORM\Entity(repositoryClass="RASP\RaspBundle\Repository\UserRepository")
 */

class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    protected $status;

    /**
     * @ORM\Column(name="rank", type="smallint", nullable=true)
     */
    protected $rank;

    /**
     * @ORM\ManyToOne(targetEntity="RASP\RaspBundle\Entity\Ufr")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $ufr;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUfr()
    {
        return $this->ufr;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $passwd
     */
    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;
    }

    /**
     * @param mixed $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $ufr
     */
    public function setUfr($ufr)
    {
        $this->ufr = $ufr;
    }
}

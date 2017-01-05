<?php
/**
 * Created by PhpStorm.
 * User: sydney_manjaro
 * Date: 04/01/17
 * Time: 22:35
 */

namespace LOG\LoginBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="log_user")
 * @ORM\Entity(repositoryClass="LOG\LoginBundle\Entity\UserRepository")
 */

class Login extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="email", type"string", length=255)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $email;

    /**
     * @ORM\Column(name="passwd", type="string", length=255)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $passwd;

    /**
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(name="rank", type="smallint")
     */
    protected $rank;

    /**
     * @ORM\ManyToOne(targetEntity="RASP\RaspBundle\Entity\Ufr")
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPasswd()
    {
        return $this->passwd;
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
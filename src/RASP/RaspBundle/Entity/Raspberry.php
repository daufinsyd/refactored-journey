<?php
/**
 * Created by PhpStorm.
 * User: sydney_manjaro
 * Date: 04/01/17
 * Time: 21:07
 */

namespace RASP\RaspBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rasp_raspberry")
 * @ORM\Entity(repositoryClass="RASP\RaspBundle\Entity\RaspberryRepository")
 */

class Raspberry
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="status", type="smallint")
     */
    protected $status;

    /**
     * @ORM\Column(name="place", type="string", length=255)
     */
    protected $place;

    /**
     * @ORM\Column(name="info", type="string", length=255)
     */
    protected $info;

    /**
     * @ORM\Column(name="maxVol", type="smallint")
     */
    protected $maxVol;

    /**
     * @ORM\Column(name="scheduleFile", type="text")
     */
    protected $scheduleFile;

    /**
     * @ORM\Column(name="shortLog", type="text")
     */
    protected $shortLog;

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
    public function getMaxVol()
    {
        return $this->maxVol;
    }

    /**
     * @return mixed
     */
    public function getScheduleFile()
    {
        return $this->scheduleFile;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @return mixed
     */
    public function getShortLog()
    {
        return $this->shortLog;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getUfr()
    {
        return $this->ufr;
    }


    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @param mixed $maxVol
     */
    public function setMaxVol($maxVol)
    {
        $this->maxVol = $maxVol;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @param mixed $scheduleFile
     */
    public function setScheduleFile($scheduleFile)
    {
        $this->scheduleFile = $scheduleFile;
    }

    /**
     * @param mixed $shortLog
     */
    public function setShortLog($shortLog)
    {
        $this->shortLog = $shortLog;
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

<?php
/*
 * Created by sydney_manjaro the 04/01/17
 */

namespace RASP\RaspBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Table(name="rasp_raspberry")
 * @ORM\Entity(repositoryClass="RASP\RaspBundle\Entity\RaspberryRepository")
 * @UniqueEntity("uuid")
 */

// the @UniqueEntity prevent duplicate from reaching the database, unique=true ensures that if it does they are refused by data layer

class Raspberry
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="UUID", type="string", length=255, unique=true, nullable=true)
     */
    protected $uuid;

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
    public function getUuid()
    {
        return $this->uuid;
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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
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

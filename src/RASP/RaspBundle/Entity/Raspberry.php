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
     * @ORM\GeneratedValue(startegy="AUTO")
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
}
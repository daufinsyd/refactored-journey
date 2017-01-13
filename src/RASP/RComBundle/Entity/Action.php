<?php
/*
 * Created by sydney_manjaro 13/01/17
 */

namespace RASP\RComBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rcom_action")
 * @ORM\Entity(repositoryClass="RASP\RComBundle\Repository\ActionRepository")
 */

class Action
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="RASP\RaspBundle\Entity\Raspberry")
     */
    protected $rasp;

    /**
     * @ORM\Column(name="codeCmd", type="integer")
     */
    protected $codeCmd;

    /**
     * @ORM\Column(name="cmd", type="text")
     */
    protected $cmd;

    /**
     * @ORM\Column(name="paramsTab", type="simple_array")
     */
    protected $paramsTab;
}
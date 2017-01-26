<?php
/*
 * Created by sydney_manjaro 13/01/17
 */

namespace RASP\RComBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rcom_action")
 * @ORM\Entity(repositoryClass="RASP\RComBundle\Repository\RaspActionRepository")
 */

class RaspAction
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
     * @ORM\Column(name="status", type="integer", options={"default":0})
     */
    protected $status=0;

    /**
     * @ORM\Column(name="codeCmd", type="integer")
     */
    protected $codeCmd;

    /**
     * @ORM\Column(name="cmd", type="text")
     */
    protected $cmd;

    /**
     * @ORM\Column(name="paramsTab", type="simple_array", nullable=true)
     */
    protected $paramsTab;

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
    public function getRasp()
    {
        return $this->rasp;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $rasp
     */
    public function setRasp($rasp)
    {
        $this->rasp = $rasp;
    }

    /**
     * @return mixed
     */
    public function getCodeCmd()
    {
        return $this->codeCmd;
    }

    /**
     * @param mixed $codeCmd
     */
    public function setCodeCmd($codeCmd)
    {
        $this->codeCmd = $codeCmd;
    }

    /**
     * @return mixed
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * @param mixed $cmd
     */
    public function setCmd($cmd)
    {
        $this->cmd = $cmd;
    }

    /**
     * @return mixed
     */
    public function getParamsTab()
    {
        return $this->paramsTab;
    }

    /**
     * @param mixed $paramsTab
     */
    public function setParamsTab($paramsTab)
    {
        $this->paramsTab = $paramsTab;
    }
}
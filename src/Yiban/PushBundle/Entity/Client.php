<?php

namespace Yiban\PushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yiban\PushBundle\Entity\App;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="Yiban\PushBundle\Repository\ClientRepository")
 */
class Client
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $uid;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $cid;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $channel_id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $platform;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App", inversedBy="clients")
     * @ORM\JoinColumn(name="app_id", referencedColumnName="id", nullable=false)
     * */
    protected $app;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return Client
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    
        return $this;
    }

    /**
     * Get uid
     *
     * @return integer 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set cid
     *
     * @param string $cid
     * @return Client
     */
    public function setCid($cid)
    {
        $this->cid = $cid;
    
        return $this;
    }

    /**
     * Get cid
     *
     * @return string 
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * Set channel_id
     *
     * @param string $channelId
     * @return Client
     */
    public function setChannelId($channelId)
    {
        $this->channel_id = $channelId;
    
        return $this;
    }

    /**
     * Get channel_id
     *
     * @return string 
     */
    public function getChannelId()
    {
        return $this->channel_id;
    }

    /**
     * Set platform
     *
     * @param string $platform
     * @return Client
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;
    
        return $this;
    }

    /**
     * Get platform
     *
     * @return string 
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Client
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set app
     *
     * @param \Yiban\PushBundle\Entity\App $app
     * @return Client
     */
    public function setApp(App $app)
    {
        $this->app = $app;
    
        return $this;
    }

    /**
     * Get app
     *
     * @return \Yiban\PushBundle\Entity\App 
     */
    public function getApp()
    {
        return $this->app;
    }
}
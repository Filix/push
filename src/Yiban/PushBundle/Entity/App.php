<?php 
namespace Yiban\PushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="apps")
 * @ORM\Entity(repositoryClass="Yiban\PushBundle\Repository\AppRepository")
 */

class App
{
    CONST BAIDU_TYPE = 'BAIDU'; //百度云推送
    CONST JPUSH_TYPE = 'JPUSH'; //极光推送
    CONST GETUI_TYPE = 'GETUI'; //个推
    CONST ANDROID_PLATFORM = 'ANDROID';
    CONST IOS_PLATFORM = 'IOS';
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $app_id;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $app_key;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $app_secret;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    protected $access_token;
    
    /**
     * 只有个推使用
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $master_secret;
    
    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $platform = 0;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    protected $type;
    
   /**
    * @ORM\ManyToMany(targetEntity="User", mappedBy="apps")
    */
   protected $users;
   
   /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="app")
     **/
    private $messages;
    
   /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="app")
     **/
    private $clients;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clients = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set app_id
     *
     * @param string $appId
     * @return App
     */
    public function setAppId($appId)
    {
        $this->app_id = $appId;
    
        return $this;
    }

    /**
     * Get app_id
     *
     * @return string 
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * Set app_key
     *
     * @param string $appKey
     * @return App
     */
    public function setAppKey($appKey)
    {
        $this->app_key = $appKey;
    
        return $this;
    }

    /**
     * Get app_key
     *
     * @return string 
     */
    public function getAppKey()
    {
        return $this->app_key;
    }

    /**
     * Set app_secret
     *
     * @param string $appSecret
     * @return App
     */
    public function setAppSecret($appSecret)
    {
        $this->app_secret = $appSecret;
    
        return $this;
    }

    /**
     * Get app_secret
     *
     * @return string 
     */
    public function getAppSecret()
    {
        return $this->app_secret;
    }

    /**
     * Set platform
     *
     * @param integer $platform
     * @return App
     */
    public function setPlatform($platform)
    {
        if(is_array($platform)){
            $g = in_array(self::ANDROID_PLATFORM, $platform) ? '1' : '0';
            $s = in_array(self::IOS_PLATFORM, $platform) ? '1' : '0';
            $this->platform = (int) $s.$g;
        }else{
            $this->platform = $platform;
        }
        return $this;
    }

    /**
     * Get platform
     *
     * @return integer 
     */
    public function getPlatform()
    {
        if($this->platform == 11){
            return array(self::ANDROID_PLATFORM, self::IOS_PLATFORM);
        }else if($this->platform == 1){
            return array(self::ANDROID_PLATFORM);
        }else if($this->platform == 10){
            return array(self::IOS_PLATFORM);
        }else{
             return array();
        }
    }
    
    public function getPlatformInt(){
        return $this->platform;
    }
    /**
     * Get platform
     *
     * @return string 
     */
    public function getPlatformString()
    {
        if($this->platform == 11){
            return 'Android, IOS';
        }else if($this->platform == 1){
            return 'Android';
        }else if($this->platform == 10){
            return 'IOS';
        }else{
             return '-';
        }
    }

    /**
     * Set type
     *
     * @param string $type
     * @return App
     */
    public function setType($type)
    {
        if(!in_array($type, array(self::BAIDU_TYPE, self::GETUI_TYPE, self::JPUSH_TYPE))){
            return $this;
        }
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Get type string
     *
     * @return string 
     */
    public function getTypeString(){
        $name = '-';
        switch($this->getType()){
            case self::BAIDU_TYPE:
                $name = '百度云推送';
                break;
            case self::GETUI_TYPE:
                $name = '个推推送';
                break;
            case self::JPUSH_TYPE:
                $name = '极光推送';
                break;
        }
        return $name;
    }

    /**
     * Add messages
     *
     * @param \Yiban\PushBundle\Entity\Message $messages
     * @return App
     */
    public function addMessage(\Yiban\PushBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;
    
        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Yiban\PushBundle\Entity\Message $messages
     */
    public function removeMessage(\Yiban\PushBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return App
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add users
     *
     * @param \Yiban\PushBundle\Entity\User $users
     * @return App
     */
    public function addUser(\Yiban\PushBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Yiban\PushBundle\Entity\User $users
     */
    public function removeUser(\Yiban\PushBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set master_secret
     *
     * @param string $masterSecret
     * @return App
     */
    public function setMasterSecret($masterSecret)
    {
        $this->master_secret = $masterSecret;
    
        return $this;
    }

    /**
     * Get master_secret
     *
     * @return string 
     */
    public function getMasterSecret()
    {
        return $this->master_secret;
    }

    /**
     * Set access_token
     *
     * @param string $accessToken
     * @return App
     */
    public function setAccessToken($accessToken)
    {
        $this->access_token = $accessToken;
    
        return $this;
    }

    /**
     * Get access_token
     *
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Add clients
     *
     * @param \Yiban\PushBundle\Entity\Message $clients
     * @return App
     */
    public function addClient(\Yiban\PushBundle\Entity\Message $clients)
    {
        $this->clients[] = $clients;
    
        return $this;
    }

    /**
     * Remove clients
     *
     * @param \Yiban\PushBundle\Entity\Message $clients
     */
    public function removeClient(\Yiban\PushBundle\Entity\Message $clients)
    {
        $this->clients->removeElement($clients);
    }

    /**
     * Get clients
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClients()
    {
        return $this->clients;
    }
}
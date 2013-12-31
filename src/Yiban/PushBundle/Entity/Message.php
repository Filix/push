<?php 
namespace Yiban\PushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yiban\PushBundle\Entity\App;
/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="Yiban\PushBundle\Repository\MessageRepository")
 */

class Message
{
    CONST NOTICE_TYPE = 'NOTICE';
    CONST MESSAGE_TYPE = 'MESSAGE';
    
    CONST OPENAPP_ACTION = 'OPENAPP';
    CONST OPENURL_ACTION = 'OPENURL';
    CONST DOWNLOAD_ACTION = 'DOWNLOAD';
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * 个推、百度20个字，极光无该字段
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * 个推50个字，百度40个字，极光72个字
     */
    protected $message;
    
    /**
     * 推送平台
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $platform = 0;
    
    /**
     * 推送user_id
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $user_id;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=false)
     * 通知或透传消息
     */
    protected $type;
    
    /**
     * 推送结果
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $result = 0;
    
    /**
     * 是否立即推送
     * @ORM\Column(type="boolean")
     */
    protected $right_now = true;
    
    /**
     * 定时推送时间
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $delay_at;
    
    /**
     * 是否可清除
     * @ORM\Column(type="boolean")
     */
    protected $clearable = true;
    
    /**
     * 是否响铃
     * @ORM\Column(type="boolean")
     */
    protected $is_ring = true;
    
    /**
     * 是否震动
     * @ORM\Column(type="boolean")
     */
    protected $is_shake = false;
    
    /**
     * 离线时间
     * @ORM\Column(type="integer")
     */
    protected $offline_time = 0;
    
    /**
     * 点击动作 
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    protected $click_action;
    
    /**
     * 点击跳转到的url
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $click_url;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * 创建时间
     */
    protected $created_at; 
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 发送到第三方时间
     */
    protected $published_at;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages")
     * @ORM\JoinColumn(name="publisher_id", referencedColumnName="id", nullable=true)
     * 发布者
     **/
   protected $publisher;
   
    /**
     * @ORM\ManyToOne(targetEntity="App", inversedBy="messages")
     * @ORM\JoinColumn(name="app_id", referencedColumnName="id", nullable=false)
     * 所属app
     **/
   protected $app;

   public function __construct()
   {
       $this->created_at = new \DateTime;
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
     * Set title
     *
     * @param string $title
     * @return Message
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Message
     */
    public function setType($type)
    {
        $type = strtoupper($type);
        if(!in_array($type, array(self::MESSAGE_TYPE, self::NOTICE_TYPE))){
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
    public function getTypeString()
    {
        return $this->type == self::NOTICE_TYPE ? '通知' : '消息';
    }

    /**
     * Set result
     *
     * @param integer $result
     * @return Message
     */
    public function setResult($result)
    {
        $this->result = $result;
    
        return $this;
    }

    /**
     * Get result
     *
     * @return integer 
     */
    public function getResult()
    {
        return $this->result;
    }
    
    public function getResultString()
    {
        return $this->result ? '成功' : '失败';
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Message
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
     * Set published_at
     *
     * @param \DateTime $publishedAt
     * @return Message
     */
    public function setPublishedAt($publishedAt)
    {
        $this->published_at = $publishedAt;
    
        return $this;
    }

    /**
     * Get published_at
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * Set publisher
     *
     * @param \Yiban\PushBundle\Entity\User $publisher
     * @return Message
     */
    public function setPublisher(\Yiban\PushBundle\Entity\User $publisher)
    {
        $this->publisher = $publisher;
    
        return $this;
    }

    /**
     * Get publisher
     *
     * @return \Yiban\PushBundle\Entity\User 
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set app
     *
     * @param \Yiban\PushBundle\Entity\App $app
     * @return Message
     */
    public function setApp(\Yiban\PushBundle\Entity\App $app)
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

    /**
     * Set platform
     *
     * @param integer $platform
     * @return Message
     */
    public function setPlatform($platform)
    {
        if(is_array($platform)){
            $g = in_array(App::ANDROID_PLATFORM, $platform) ? '1' : '0';
            $s = in_array(App::IOS_PLATFORM, $platform) ? '1' : '0';
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
            return array(App::ANDROID_PLATFORM, App::IOS_PLATFORM);
        }else if($this->platform == 1){
            return array(App::ANDROID_PLATFORM);
        }else if($this->platform == 10){
            return array(App::IOS_PLATFORM);
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
     * Set right_now
     *
     * @param boolean $rightNow
     * @return Message
     */
    public function setRightNow($rightNow)
    {
        $this->right_now = $rightNow;
    
        return $this;
    }

    /**
     * Get right_now
     *
     * @return boolean 
     */
    public function getRightNow()
    {
        return $this->right_now;
    }

    /**
     * Set delay_at
     *
     * @param \DateTime $delayAt
     * @return Message
     */
    public function setDelayAt($delayAt)
    {
        $this->delay_at = $delayAt;
    
        return $this;
    }

    /**
     * Get delay_at
     *
     * @return \DateTime 
     */
    public function getDelayAt()
    {
        return $this->delay_at;
    }

    /**
     * Set clearable
     *
     * @param boolean $clearable
     * @return Message
     */
    public function setClearable($clearable)
    {
        $this->clearable = $clearable;
    
        return $this;
    }

    /**
     * Get clearable
     *
     * @return boolean 
     */
    public function getClearable()
    {
        return $this->clearable;
    }

    /**
     * Set is_ring
     *
     * @param boolean $isRing
     * @return Message
     */
    public function setIsRing($isRing)
    {
        $this->is_ring = $isRing;
    
        return $this;
    }

    /**
     * Get is_ring
     *
     * @return boolean 
     */
    public function getIsRing()
    {
        return $this->is_ring;
    }

    /**
     * Set is_shake
     *
     * @param boolean $isShake
     * @return Message
     */
    public function setIsShake($isShake)
    {
        $this->is_shake = $isShake;
    
        return $this;
    }

    /**
     * Get is_shake
     *
     * @return boolean 
     */
    public function getIsShake()
    {
        return $this->is_shake;
    }

    /**
     * Set offline_time
     *
     * @param integer $offlineTime
     * @return Message
     */
    public function setOfflineTime($offlineTime)
    {
        $this->offline_time = $offlineTime;
    
        return $this;
    }

    /**
     * Get offline_time
     *
     * @return integer 
     */
    public function getOfflineTime()
    {
        return $this->offline_time;
    }

    /**
     * Set click_action
     *
     * @param string $clickAction
     * @return Message
     */
    public function setClickAction($clickAction)
    {
        $clickAction = strtoupper($clickAction);
        if(!in_array($clickAction, array(self::DOWNLOAD_ACTION, self::OPENAPP_ACTION, self::OPENURL_ACTION))){
            return $this;
        }
        $this->click_action = $clickAction;
    
        return $this;
    }

    /**
     * Get click_action
     *
     * @return string 
     */
    public function getClickAction()
    {
        return $this->click_action;
    }

    /**
     * Set click_url
     *
     * @param string $clickUrl
     * @return Message
     */
    public function setClickUrl($clickUrl)
    {
        $this->click_url = $clickUrl;
    
        return $this;
    }

    /**
     * Get click_url
     *
     * @return string 
     */
    public function getClickUrl()
    {
        return $this->click_url;
    }
    
    

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return Message
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
    
        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
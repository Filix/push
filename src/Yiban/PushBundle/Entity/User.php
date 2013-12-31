<?php
namespace Yiban\PushBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Yiban\PushBundle\Repository\UserRepository")
 */

class User extends BaseUser 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToMany(targetEntity="App", inversedBy="users")
     * @ORM\JoinTable(name="users_have_apps")
     */
    protected $apps;
    
    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="publisher")
     **/
    protected $messages;
    
     public function __construct()
    {
        parent::__construct();
        $this->apps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add messages
     *
     * @param \Yiban\PushBundle\Entity\Message $messages
     * @return User
     */
    public function addMessage(\Yiban\PushBundle\Entity\Message $message)
    {
        $this->messages[] = $message;
    
        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Yiban\PushBundle\Entity\Message $messages
     */
    public function removeMessage(\Yiban\PushBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
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
     * Add apps
     *
     * @param \Yiban\PushBundle\Entity\App $apps
     * @return User
     */
    public function addApp(\Yiban\PushBundle\Entity\App $app)
    {
        $app->addUser($this);
        $this->apps[] = $app;
    
        return $this;
    }

    /**
     * Remove apps
     *
     * @param \Yiban\PushBundle\Entity\App $apps
     */
    public function removeApp(\Yiban\PushBundle\Entity\App $app)
    {
        $this->apps->removeElement($app);
    }

    /**
     * Get apps
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApps()
    {
        return $this->apps;
    }

}
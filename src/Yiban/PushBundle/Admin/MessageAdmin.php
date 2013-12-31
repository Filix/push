<?php

namespace Yiban\PushBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Yiban\PushBundle\Entity\Message;

class MessageAdmin extends Admin
{

    protected $container;

    public function __construct($code, $class, $baseControllerName, $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }

    public function postPersist($object)
    {
        $s = $this->container->get('yiban.pusher');
        $s->pushNotice($object);
    }

    public function configure()
    {
        if (!$this->hasRequest()) {
            $this->datagridValues = array(
                '_page' => 1,
                '_sort_order' => 'desc', // sort direction
                '_sort_by' => 'created_at', // field name
                '_per_page' => 25,
            );
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('title', 'text', array('label' => '标题'))
                ->add('message', 'textarea', array('label' => '内容', 'required' => false))
                ->add('type', 'choice', array('label' => '类型', 'expanded' => true, 'choices' => array(Message::NOTICE_TYPE => '通知', Message::MESSAGE_TYPE => '透传消息')))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('title')
                ->add('message')
                ->add('type')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('id', null, array('label' => 'ID'))
                ->add('title', null, array('label' => '标题'))
                ->add('message', null, array('label' => '内容'))
                ->add('type', null, array('label' => '类型', 'template' => 'YibanPushBundle:Message:_type.html.twig'))
                ->add('result', null, array('label' => '推送结果', 'template' => 'YibanPushBundle:Message:_result.html.twig'))
        ;
    }

    public function getBatchActions()
    {
        return array();
    }

}

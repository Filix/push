<?php

namespace Yiban\PushBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Yiban\PushBundle\Entity\App;

class AppAdmin extends Admin
{

    protected $container;
    
    protected $formOptions = array(
        'validation_groups' => array('App')
    );
    
    public function getFormBuilder() {
        $request = $this->container->get('request')->request;
        $keys = $request->keys();
        if(count($keys)){
            $data = $request->filter($keys[0]);
            $type = $data['type'];
            if($type){
                $this->formOptions['validation_groups'] = array_merge($this->formOptions['validation_groups'], array(strtolower($type)));
            }
        }
        
        $formBuilder = parent::getFormBuilder();
        return $formBuilder;
    }

    public function __construct($code, $class, $baseControllerName, $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }

    public function createQuery($context = 'list')
    {
        if($this->container->get('security.context')->isGranted('ROLE_ADMIN')){
            return parent::createQuery($context);
        }
        $user = $this->container->get('security.context')->getToken()->getUser();
        $query = parent::createQuery($context);
        $query->leftJoin($query->getRootAlias() . '.users', 'u')
                ->where('u.id = :ss')
                ->setParameter('ss', $user->getId())
                ;
        return $query;
    }
    
    public function prePersist($object)
    {
        #生成app的access_token
        $object->setAccessToken($this->container->get('fos_user.util.token_generator')->generateToken());
    }
    
    public function postPersist($object)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $user->addApp($object);
        $this->getModelManager()->update($user);
    }

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        $collection->add('pushnotice', $this->getRouterIdParameter() . '/push/notice');
        $collection->add('pushusernotice', $this->getRouterIdParameter() . '/push/user/notice');
        $collection->add('pushmessage', $this->getRouterIdParameter() . '/push/message');
        $collection->add('statistics', $this->getRouterIdParameter() . '/statistics');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name', 'text', array('label' => '应用名称'))
                ->add('app_id', 'text', array('label' => 'app id', 'required' => false))
                ->add('app_key', 'text', array('label' => 'app key'))
                ->add('app_secret', 'text', array('label' => 'app secret'))
                ->add('master_secret', 'text', array('label' => 'master secret', 'required' => false))
                ->add('platform', 'choice', array('label' => '平台', 'required' => false, 'choices' => array('ANDROID' => 'Android', 'IOS' => 'IOS'), 'expanded' => true, 'multiple' => true))
                ->add('type', 'choice', array('label' => '第三方服务', 'choices' => array('' => '----选择第三方服务----', App::BAIDU_TYPE => '百度云推送', App::GETUI_TYPE => '个推推送', App::JPUSH_TYPE => '极光推送')))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('name')
                ->add('app_id')
                ->add('app_key')
                ->add('app_secret')
                ->add('master_secret')
                ->add('access_token')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id', null, array('label' => 'ID'))
                ->addIdentifier('name', null, array('label' => '应用名'))
                ->add('第三方秘钥', null, array('template' => 'YibanPushBundle:App:_keys.html.twig'))
                ->add('access_token')
                ->add('app_platform', NULL, array('template' => 'YibanPushBundle:App:_platform.html.twig', 'label' => '平台'))
                ->add('app_type', null, array('label' => '第三方服务', 'template' => 'YibanPushBundle:App:_type.html.twig'))
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'pushnotice' => array(
                            'template' => 'YibanPushBundle:App:_pushnotice_action.html.twig'
                        ),
                        'pushmessage' => array(
                            'template' => 'YibanPushBundle:App:_pushmessage_action.html.twig'
                        ),
                        'pushusernotice' => array(
                            'template' => 'YibanPushBundle:App:_pushusernotice_action.html.twig'
                        ),
                        'statistics' => array(
                            'template' => 'YibanPushBundle:App:_statistics.html.twig'
                        )
                    )
                ))
        ;
    }

}

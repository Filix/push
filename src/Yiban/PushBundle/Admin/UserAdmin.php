<?php
namespace Yiban\PushBundle\Admin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
    protected $container;
    
    public function __construct($code, $class, $baseControllerName, $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }
    protected function configureFormFields(FormMapper $formMapper)
    {
        $form = $this->container->get('fos_user.resetting.form');
        $formHandler = $this->container->get('fos_user.resetting.form.handler');
//        $pass = !$this->getSubject()->getId() ? true : false;
        $formMapper
                ->add('username', 'text', array('label' => '用户名'))
                ->add('email', 'text', array('label' => '邮箱', 'required' => false))
//                ->add('password', 'password', array('label' => '密码', 'required' => $pass ))
//                ->add('password2', 'password', array('label' => '重复密码', 'required' => $pass))
                ->add('roles', 'choice', array('label' => '管理员', 'choices' => array('ROLE_ADMIN' => '设为管理员'), 'expanded' => true, 'multiple' => true))
                ->add('apps', 'sonata_type_model',  array('expanded' => true, 'by_reference' => false, 'multiple' => true))
                
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('username')
                ->add('email')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id', null, array('label' => 'ID'))
                ->addIdentifier('username', null, array('label' => '用户名'))
                ->add('email', null, array('label' => '邮箱'))
                ->add('limit', null, array('label' => '权限'))
        ;
    }
    
    public function create($user)
    {
        $user_manager = $this->container->get('fos_user.user_manager');
        #默认密码
        $user->setPlainPassword('yiban.push.default.passport');
        //免激活
        $user->setEnabled(true);
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());  
        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $user_manager->updateUser($user);
    }
}

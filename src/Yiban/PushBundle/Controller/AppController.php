<?php

namespace Yiban\PushBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Yiban\PushBundle\Entity\App;
use Yiban\PushBundle\Entity\Message;
use Ob\HighchartsBundle\Highcharts\Highchart;

class AppController extends Controller
{

    protected $message_admin;

    //推送通知
    public function pushnoticeAction($id)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $app = $this->admin->getObject($id);
        if (!$app) {
            throw new NotFoundHttpException(sprintf('unable to find the app with id : %s', $id));
        }
        // the key used to lookup the template
        $templateKey = 'create';
        //更改 $this->admin 对象
        $this->message_admin = $this->container->get('yiban.admin.message');
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $message = $this->message_admin->getNewInstance();
        $message->setType(Message::NOTICE_TYPE);
        $message->setPublisher($this->getUser());
        $message->setApp($app);
        $this->message_admin->setSubject($message);
        $form = $this->getNoticeForm($app);

        if ($this->getRestMethod() == 'POST') {
            $form->bind($this->get('request'));
            $isFormValid = $form->isValid();
            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $this->message_admin->create($message);
                if ($message->getResult()) {
                    $this->addFlash('sonata_flash_success', '消息推送成功');
                } else {
                    $this->addFlash('sonata_flash_error', '消息推送失败');
                }
                // redirect to edit mode
                return $this->redirect($this->message_admin->generateUrl('list'));
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash('sonata_flash_error', 'flash_create_error');
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render('YibanPushBundle:App:pushnotice.html.twig', array(
                    'action' => 'create',
                    'form' => $view,
                    'object' => $app,
        ));
    }

    public function statisticsAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $app = $this->admin->getObject($id);
        if (!$app) {
            throw new NotFoundHttpException(sprintf('unable to find the app with id : %s', $id));
        }
        $series = array(
            array("name" => "Data Serie Name", "data" => array(1, 2, 4, 5, 6, 3, 8))
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text' => "Horizontal axis title"));
        $ob->yAxis->title(array('text' => "Vertical axis title"));
        $ob->series($series);
        return $this->render('YibanPushBundle:App:statistics.html.twig', array(
                    'application' => $app,
                    'chart' => $ob
        ));
    }

    public function pushusernoticeAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $app = $this->admin->getObject($id);
        if (!$app) {
            throw new NotFoundHttpException(sprintf('unable to find the app with id : %s', $id));
        }
        // the key used to lookup the template
        $templateKey = 'create';
        //更改 $this->admin 对象
        $this->message_admin = $this->container->get('yiban.admin.message');
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $message = $this->message_admin->getNewInstance();
        $message->setType(Message::NOTICE_TYPE);
        $message->setPublisher($this->getUser());
        $message->setApp($app);
        $this->message_admin->setSubject($message);
        $form = $this->getNoticeForm($app, true);

        if ($this->getRestMethod() == 'POST') {
            $form->bind($this->get('request'));
           
            $isFormValid = $form->isValid();
            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $this->message_admin->create($message);
                if ($message->getResult()) {
                    $this->addFlash('sonata_flash_success', '消息推送成功');
                } else {
                    $this->addFlash('sonata_flash_error', '消息推送失败');
                }
                // redirect to edit mode
                return $this->redirect($this->message_admin->generateUrl('list'));
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash('sonata_flash_error', 'flash_create_error');
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render('YibanPushBundle:App:pushusernotice.html.twig', array(
                    'action' => 'create',
                    'form' => $view,
                    'object' => $app,
        ));
    }

    protected function getNoticeForm(App $app, $to_user = false)
    {
        $formOptions = array(
            'validation_groups' => array('Message')
        );
        if($to_user){
            $formOptions['validation_groups'] = array_merge($formOptions['validation_groups'], array('push_to_user'));
        }else{
            $formOptions['validation_groups'] = array_merge($formOptions['validation_groups'], array('push_to_app', strtolower($app->getType() . '_notice')));
        }
        
        $form = $this->createFormBuilder($this->message_admin->getSubject(), $formOptions)
                ->add('title', 'text', array('label' => '标题'))
                ->add('type', 'hidden')
                ->add('message', 'textarea', array('label' => '内容', 'attr' => array('style' => 'width:400px; height:200px;')))
        ;
        if($to_user){
            $form->add('user_id', 'text', array('label' => 'user id'));
        }else{
            if ($app->getPlatformInt() == 11) {
                $form->add('platform', 'choice', array('multiple' => true, 'label' => '平台', 'expanded' => true, 'choices' => array('ANDROID' => 'Android', 'IOS' => 'IOS')));
            } else {
                $form->add('platform', 'hidden', array('attr' => array('value' => $app->getPlatformInt())));
            }
        }
        
        switch ($app->getType()) {
            case App::BAIDU_TYPE:
            case App::GETUI_TYPE:
                $form->add('is_ring', null, array('label' => '收到后响铃', 'required' => false));
                $form->add('is_shake', null, array('label' => '收到后震动', 'required' => false));
                $form->add('clearable', null, array('label' => '可清除', 'required' => false));
                $form->add('click_action', 'choice', array('label' => '点击动作', 'expanded' => true, 'choices' => array(Message::OPENAPP_ACTION => '打开应用', Message::OPENURL_ACTION => '打开网页')));
                $form->add('click_url', null, array('label' => '点击后开发的网页url'));
                $form->add('offline_time', null, array('label' => '离线保留时间'));
                break;
//            case App::GETUI_TYPE:
//                $form->add('is_ring', null, array('label' => '收到后响铃', 'required' => false));
//                $form->add('is_shake', null, array('label' => '收到后震动', 'required' => false));
//                $form->add('clearable', null, array('label' => '可清除', 'required' => false));
//                $form->add('click_action', 'choice', array('label' => '点击动作', 'expanded' => true, 'choices' => array(Message::OPENAPP_ACTION => '打开应用', Message::OPENURL_ACTION => '打开网页')));
//                $form->add('click_url', null, array('label' => '点击后开发的网页url'));
//                $form->add('offline_time', null, array('label' => '离线保留时间'));
                break;
            case App::JPUSH_TYPE:

                break;
            default :
                break;
        }
        return $form->getForm();
    }

}

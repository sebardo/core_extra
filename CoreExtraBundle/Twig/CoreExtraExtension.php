<?php

namespace CoreExtraBundle\Twig;

use Twig_SimpleFunction;
use CoreExtraBundle\Entity\Notification;

/**
 * Class CoreExtraExtension
 */
class CoreExtraExtension extends \Twig_Extension
{

    private $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('get_notification', array($this, 'getNotification')),
            new Twig_SimpleFunction('get_notification_url', array($this, 'getNotificationUrl')),
            new Twig_SimpleFunction('get_slider_items', array($this, 'getSliderItems')),
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function getNotification($type, $count=true)
    {
        $notificationManager = $this->container->get('notification_manager');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $notif = $notificationManager->getNotification($user, $type, $count);
        
        return $notif;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getNotificationUrl(Notification $notification)
    {

        return $this->container->get('router')->generate('notification_show', array(
                'slug' => $notification->getProject()->getSlug(),
                $notification->getType() => true
            ));

    }
    
    /**
     * {@inheritDoc}
     */
    public function getSliderItems()
    {
        $em = $this->container->get('doctrine')->getManager();
        $headers = $em->getRepository("CoreExtraBundle:Slider")->findBy(array('active' => true), array('order' => 'ASC'));
        return $headers;
    }
   
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'coreextra_extension';
    }
}
<?php

namespace CoreExtraBundle\Service;

use CoreExtraBundle\Entity\Notification;
use CoreBundle\Entity\BaseActor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of NotificationManager
 *
 * @author sebastian
 */
class NotificationManager 
{

    protected $container = null;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }
    
    public function getManager()
    {
        return $this->container->get('doctrine')->getManager();
    }
    
    public function setNotification(BaseActor $actor, $dest, $type, $detail)
    {
        $em = $this->getManager();
        $notif = new Notification();
        $notif->setActor($actor);
        if($dest instanceof BaseActor)  $notif->setActorDest($dest);
        $notif->setType($type);
        $notif->setDetail(json_encode($detail));
        $em->persist($notif);
        $em->flush();
        
        return $notif;
    }
    
    public function getNotification($actorDest, $type, $count=false)
    {
        $em = $this->getManager();
        if($type == 'all'){
            $notifications = $em->getRepository('CoreExtraBundle:Notification')->findBy(array('actorDest' => $actorDest, 'isActive' => true), array('created' => 'DESC'));
        }else{
            $notifications = $em->getRepository('CoreExtraBundle:Notification')->findBy(array('actorDest' => $actorDest, 'type' => $type, 'isActive' => true), array('created' => 'DESC'));
        }
        if($count) return count($notifications);
        return $notifications;
    }
    
    public function disableNotification($actorDest, $type, $actor=null)
    {
        $em = $this->getManager();
        if(!is_null($actor)){
            $notifications = $em->getRepository('CoreExtraBundle:Notification')->findBy(array('actor' => $actor, 'actorDest' => $actorDest, 'type' => $type));
        }else{
            $notifications = $em->getRepository('CoreExtraBundle:Notification')->findBy(array('actorDest' => $actorDest, 'type' => $type));
        }
        foreach ($notifications as $notification) {
            $notification->setActive(false);
            $em->flush();
        }
    }
    
    public function disableNotificationByDetail($actor, $actorDest, $type, $detail)
    {
        $em = $this->getManager();
        
        $notification = $em->getRepository('CoreExtraBundle:Notification')->findOneByLikeDetail(
            $actor, 
            $actorDest, 
            $type, 
            $detail
                );
       
        if(!is_null($notification)){
            $notification->setActive(false);
            $em->flush();
        }
    }
    
//    public function addNotification(BaseActor $actor, Project $project, $type)
//    {
//        $em = $this->getManager();
//        $actorIds = $this->getActorRelatedProject($project);
//         
//        foreach ($actorIds as $actorId) {
//               $actorDest = $em->getRepository('CoreBundle:BaseActorActor')->find($actorId);
//               $this->setNotification($actor, $actorDest, $project, $type);
//            
//        } 
//    }
    
//    private function getActorRelatedProject($project) 
//    {
//        return $this->container->get('front_manager')->getActorRelatedProject($project);
//    }
}

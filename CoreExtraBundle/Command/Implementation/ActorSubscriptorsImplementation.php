<?php
namespace CoreExtraBundle\Command\Implementation;

use CoreExtraBundle\Command\AbstractImplementation;
use CoreExtraBundle\Command\LoadImplementation;
use CoreExtraBundle\Entity\Actor;

/**
 * Description of ActorSubscriptorsImplementation
 * If not exist email add user 
 * If exist turn on newsletter
 *
 * @author sebastian
 */
class ActorSubscriptorsImplementation extends AbstractImplementation implements LoadImplementation{
    
     public function getFileLocation(){
        return 'src/CoreExtraBundle/Command/src/actor_subscriptors.csv';
    }
    
    public function load($line, $lineNo){
//        print_r($line);die();
        if($lineNo!=1){
            $email = trim($line[0]);
            $names = trim($line[1]);
            $postalCode = trim($line[2]);
            
            //names
            if(count(explode(' ', $names)) > 1){
                if(count($arr = explode(' ', $names)) == 2){
                    $firstname = $arr[0];
                    $lastname = $arr[1];
                }elseif(count($arr =  explode(' ', $names)) == 3){
                    $firstname = $arr[0];
                    $lastname = $arr[1].' '.$arr[2];
                }elseif(count($arr = explode(' ', $names)) == 4){
                    $firstname = $arr[0].' '.$arr[1];
                    $lastname = $arr[2].' '.$arr[3];
                }
            }else{
                $firstname = $names;
                $lastname = '';
            }
            
            $actor = $this->manager->getRepository('CoreBundle:Actor')->findOneByEmail($email);
            if(!$actor instanceof Actor){
                //create new user
                $this->output->writeln('<error>Actor or Optic  '.$email.'  does not exist, proced to created(actor) and swith on newsletter notification.</error>');
                $actor = new Actor();
                $actor->setUsername($email);
                $actor->setEmail($email);
                $actor->setPassword($email);
                $actor->setName($firstname);
                $actor->setLastname($lastname);
                $actor->setActive(true);
                $actor->setNewsletter(true);
                 try {
                     $this->manager->persist($actor);
                    $this->manager->flush();
                } catch (\Exception $ex) {
                    $this->output->writeln('<error>'.$ex->getMessage().'</error>');
                }
            }else{
                //active newsletter
                $this->output->writeln('<comment>Actor '.$email.' already exist and have been swith on newsletter notification .</comment>');
                $actor->setNewsletter(true);
                $this->manager->flush(); 
            } 
            
           
        }
    }
}

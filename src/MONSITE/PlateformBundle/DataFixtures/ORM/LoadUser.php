<?php

namespace MONSITE\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MONSITE\UserBundle\Entity\User;

class LoadUser implements FixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $listNames = array('gabin', 'guillaume', 'sam', 'mathieu');
        
        foreach($listNames as $names){
            
            $user = new User;
            
            $user->setUsername($names);
            $user->setPassword($names);
            $user->setSalt('');
            $user->setRoles(array('ROLE_USER'));
            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
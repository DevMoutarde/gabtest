<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MONSITE\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MONSITE\PlateformBundle\Entity\Skill;

class LoadSkill implements FixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $names = array(
            'PHP',
            'Symfony',
            'C++',
            'Java',
            'Photoshop',
            'Blender',
            'Bloc-note'
        );
        
        foreach($names as $name){
            $skill = new Skill();
            $skill->setName($name);
            
            $manager->persist($skill);
        }
        
        $manager->flush();
    }
}
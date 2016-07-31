<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MONSITE\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MONSITE\PlateformBundle\Entity\Category;

class LoadCategory implements FixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $names = array(
            'Développement web',
            'Développement mobile',
            'Graphisme',
            'Intégration',
            'Réseau'
        );
        
        foreach($names as $name){
            $category = new Category();
            $category->setName($name);
            
            $manager->persist($category);
        }
        
        $manager->flush();
    }
}
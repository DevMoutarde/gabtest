<?php
// src/MONSITE/PlateformBundle/Antispam/MONSITEAntispam.php
namespace MONSITE\PlateformBundle\Antispam;

class MONSITEAntispam{
    

/**
 * vérifie si un texte est un spam ou non
 * @param string $text
 * @return bool
 */
    
    public function isSpam($text){
    
        return strlen($text) > 50;
    }
}


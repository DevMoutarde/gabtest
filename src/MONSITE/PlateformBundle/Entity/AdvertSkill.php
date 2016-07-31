<?php

namespace MONSITE\PlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdvertSkill
 *
 * @ORM\Table(name="advert_skill")
 * @ORM\Entity(repositoryClass="MONSITE\PlateformBundle\Repository\AdvertSkillRepository")
 */
class AdvertSkill
{
    /**
     * @ORM\ManyToOne(targetEntity="MONSITE\PlateformBundle\Entity\Advert")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;
    /**
     * @ORM\ManyToOne(targetEntity="MONSITE\PlateformBundle\Entity\Skill")
     * @ORM\JoinColumn(nullable=false)
     */
    private $skill;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255)
     */
    private $level;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set level
     *
     * @param string $level
     * @return AdvertSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    public function setAdvert(Advert $advert){
        $this->advert = $advert;
        return $this;
    }
    public function getAdvert(){
        return $this->advert;
    }
    
    public function setSkill(Skill $skill){
        $this->skill = $skill;
        return $this;
    }
    public function getSkill(){
        return $this->skill;
    }
}

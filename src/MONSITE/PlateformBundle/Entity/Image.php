<?php

namespace MONSITE\PlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="MONSITE\PlateformBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;


    private $file;
    private $tempFilename;
    
    public function getFile(){
        return $this->file;
    }
    
  
    
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
     * Set url
     *
     * @param string $url
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }
    
    
    
    public function setFile(UploadedFile $file = null){
        
         echo "setFile, contenu de url: ".$this->url;
        //on vérifie le setter de File, pour prendre en compte l'upload d'un fichier
        //lorsequ'il en existe déjà
        $this->file = $file;
        
        //on vérifie si on avait déjà un fichier pour cette entité
        if("" !== $this->url){
            
            $this->tempFilename = $this->url;
            
            $this->url = null;
            $this->alt = null;
            echo "contenu de url: ".$this->url;
        }
    }
    
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(){
        
        //le nom du fichier est son id et la var URL sera son extension
        $this->url = $this->file->guessExtension();
        
        //on met le nom original du fichier dans ALT
        $this->alt = $this->file->getClientOriginalName();
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */       
    public function upload(){
       
        //si il y avait un fichier on le supprime
        if(null !== $this->tempFilename){
            //si le fichier existe déjà en le supprime
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)){
                unlink($oldFile);
            }
        }
        
        //on déplace maintenant le fichier envoyé dans le répertoire
        $this->file->move(
                $this->getUploadRootDir(),
                $this->id.'.'.$this->url);
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload(){
        //on sauvegarde temporairement le nom du fichier, car il dépend 
        //de l'ID
        
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;
    }
    
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if(file_exists($this->tempFilename)){
            unlink($this->tempFilename);
        }
    }
    
    
    public function getUploadDir(){
        return 'uploads/img';
    }
    
    public function getUploadRootDir(){
        
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    public function getWebPath(){
        
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
    }
            
    
}

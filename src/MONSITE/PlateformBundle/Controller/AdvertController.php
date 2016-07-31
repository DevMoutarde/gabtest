<?php

namespace MONSITE\PlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use MONSITE\PlateformBundle\Entity\Advert;
use MONSITE\PlateformBundle\Entity\Image;
use MONSITE\PlateformBundle\Entity\Application;
use MONSITE\PlateformBundle\Entity\AdvertSkill;
use MONSITE\PlateformBundle\Entity\Skill;



use \MONSITE\PlateformBundle\Form\AdvertType;
use \MONSITE\PlateformBundle\Form\AdvertEditType;

class AdvertController extends Controller
{
    
    
    public function indexAction($page)
    {
        
       
       
        
        $repo = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('MONSITEPlateformBundle:Advert');
        
        
        $adverts = $repo->getAdvertWithApplications();
        
        
        
        
        $manager = $this->getDoctrine()->getManager();
        $advertRepository = $manager->getRepository('MONSITEPlateformBundle:Advert');
        $adverts = $advertRepository->myFindAll();
        
        
        
        //$urlSlug = $this->get('router')->generate('MONSITE_platform_view_slug', array('year' => 2015, 'slug' => "test"), true);
        $content = $this
                ->get('templating')
                ->render('MONSITEPlateformBundle:Advert:index.html.twig', array(
                    'nom' => 'Gab',
                    'adverts' => $adverts
                ));
        
        
        return new Response($content);
    }
    
    
    /**
     * @security("has_role('ROLE_AUTEUR')")
     */
    public function addAction(Request $request){
        
       
        $advert = new Advert();
        
        $form = $this->createForm(new AdvertType, $advert);
         
        
        // lien entre request et le formulaire advert contient les données entrées
        // dans le formulaire
        $form->handleRequest($request);
        
        // isValid va vérifier que les données sont correctes
        if($form->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée.');
            return $this->redirect($this->generateUrl('MONSITE_platform_view', array('id' => $advert->getId())));
        
            
        }
        
        return $this->render('MONSITEPlateformBundle:Advert:add.html.twig', array(
            'form' => $form->createView()
        ));
        
        
    }
    
    public function menuAction(){
        
        $listAdvert = array(
            array('id' => 2, 'title'=> 'Recherche développeur'),
            array('id' => 5, 'title'=> 'Mission'),
            array('id' => 9, 'title'=> 'offre')
        );
        //ini, on ne fait que passer des valeurs au template, il n'y a pas de 
        //génération de template vie get('templating');
        return $this -> render('MONSITEPlateformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdvert
        ));
    }
    
    
    public function viewAction($id){
        $manager = $this->getDoctrine()->getManager();
        
        
        $advert = $manager
                ->getRepository('MONSITEPlateformBundle:Advert')
                ->find($id);
        
        
        $essai = $manager
                ->getRepository('MONSITEPlateformBundle:Advert')
                ->myFindOne($id);
        
        
        
        
        if (null === $advert){
            throw new NotFoundHttpException("l'id n existe pas");
        }
        $listApplication = $manager->getRepository('MONSITEPlateformBundle:Application')->findBy(array('advert' => $advert));
        
        
        $listAdvertSkill = $manager->getRepository('MONSITEPlateformBundle:AdvertSkill')->findBy(array('advert' => $advert));
        
        
        
        return $this -> render('MONSITEPlateformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApplication' => $listApplication,
            'listAdvertSkill' => $listAdvertSkill
        ));
        
    }
    
    
    public function editAction($id, Request $request){
        
        $manager = $this->getDoctrine()->getManager();
        
        $advert = $manager
                ->getRepository('MONSITEPlateformBundle:Advert')
                ->find($id);
        
        if (null === $advert){
            throw new NotFoundHttpException("L'annonce ".$id." n\'existe pas");
        }
        
        $advert->setAuthor('bouboule');
        
        $manager->flush();
                
        
        
        echo $request ->query->get('tag');
        $listAdvert = array(
                'title'=>'Recherche développeur Symfony',
                'id'=>$id,
                'author'=>'Gabgab',
                'content'=>'Recherche de développeur symfony',
                'date'=> new \Datetime()
            );
        
        return $this->render('MONSITEPlateformBundle:Advert:edit.html.twig', array(
           'advert'=> $listAdvert,
            'id'=> $id
        ));
    }
    
    
    public function deleteAction(Request $request, $id){
        
        $manager = $this->getDoctrine()->getManager();
        
        $advert = $manager->getRepository('MONSITEPlateformBundle:Advert')->find($id);
        
        if(null === $advert){
            throw new NotFoundHttpException("l'annonce n'a pas ete trouvée");
        }
        
        //creation d'un formulaire vide qui contiendra le champ CSRF
        $form = $this->createFormBuilder()->getForm();
        
        if($form->handleRequest($request)->isValid()){
            
            $manager->remove($advert);
            $manager->flush();
            
            $request->getSession()->getFlashBag()->add('info', "annonce supprimée");
            return $this->redirect($this->generateUrl('MONSITE_platform_home'));
        }
        
        return $this->render('MONSITEPlateformBundle:Advert:delete.html.twig', array(
            'advert'=>$advert,
            'form'=>$form->createView()
        ));
    }
    
    
    
    
    
    public function byeAction($id)
    {
        $content = $this
                ->get('templating')
                ->render('MONSITEPlateformBundle:Advert:bye.html.twig', array(
                    'contenu' => 'La page bye bye est un exercice',
                    'id'=> $id
                ));
        return new Response($content);
    }
    
    
    
    
    
    
    
    
    
    
    
    public function viewSlugAction($slug, $year, $format){
        
        $urle = $this->get('router')->generate('MONSITE_platform_bye');
        $tabl = array(
                        1=>"a",
                        2=>"b",
                        3=>"c",
                        4=>"d",
                        5=>"e");
        $content = $this
                ->get('templating')
                ->render('MONSITEPlateformBundle:Advert:viewSlug.html.twig', array(
                    'contenu' => ' '.$slug.' '.$year.' '.$format.'',
                    'annee' => $year,
                    'sluge' => $slug,
                    'format' => 'html',
                    'lien' => ' '.$urle.' ',
                    'table' => $tabl
                    
                    
                ));
        return new Response($content);
    }         
            
}
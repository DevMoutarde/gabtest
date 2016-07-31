<?php

namespace MONSITE\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction(Request $request){
        
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            
            return $this->redirectToRoute('MONSITE_platform_home');
        }
        
        //authentication utils récupère le nom d'utilisateur et l'erreur si on a rentré de 
        //mauvaises informations.
        $authenticationUtils = $this->get('security.authentication_utils');
        
        return $this->render('MONSITEUserBundle:Security:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
    
}

<?php

namespace MONSITE\PlateformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MONSITE\PlateformBundle\Repository\AdvertRepository;
use \MONSITE\PlateformBundle\Repository\CategoryRepository;

use \Symfony\Bridge\Doctrine\Form\Type\EntityType;

use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use \Symfony\Component\Form\Extension\Core\Type\TextareaType;
use \Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use \Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\CollectionType;

use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\Form\FormEvent;

class AdvertType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern ='D%';
        $builder
            ->add('date',  DateTimeType::class)
            ->add('title', TextType::class)
            ->add('author', TextareaType::class)
            ->add('content', TextType::class)
            ->add('image',  new ImageType())

            
            ->add('categories', EntityType::class, array(
                'class' => 'MONSITEPlateformBundle:Category',
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function(CategoryRepository $repository) use($pattern){
                    return $repository->getLikeQueryBuilder($pattern);
                }
            ))
            ->add('save', SubmitType::class)
        ;
            
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            
                $advert = $event->getData();
                
                if(null === $advert){
                    return;
                }
                
                if(!$advert->getPublished() || null === $advert->getId()){
                    //si l'annonce n'est pas publiée ou si elle n'existe pas en base
                    $event->getForm()->add('published', CheckboxType::class, array('required' => false));
                }else{
                    $event->getForm()->remove('published');
                }
                
                
            });

    }
    

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MONSITE\PlateformBundle\Entity\Advert'
        ));
    }
    
    public function getName(){
        return 'monsite_plateformbundle_advert';
    }
}

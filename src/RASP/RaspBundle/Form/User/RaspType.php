<?php
/*
 * Created by sydney_manjaro the 07/01/17
 */


namespace RASP\RaspBundle\Form\User;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use RASP\RaspBundle\Entity\Raspberry;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvents;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RaspType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $listUfr = $options['listUfr'];
        $isAdmin = $options['isAdmin'];
        $builder
            ->add('place', null, array('required' => true, 'label' => 'Emplacement physique'))
            ->add('status', null, array('required' => true, 'label' => 'Statut'))
            ->add('info', null, array('required' => true))
            ->add('maxVol', null, array('required' => true, 'label' => 'Volume maximum'))
            ->add('ufr', null, array(
                    'class' => 'RASP\RaspBundle\Entity\Ufr',
                    'choices' => $listUfr
            ))
        ;
        if($isAdmin){
            $builder->add('uuid');
        }

        $builder->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'listUfr' => null,
            'isAdmin' => null,
        ));
    }
}

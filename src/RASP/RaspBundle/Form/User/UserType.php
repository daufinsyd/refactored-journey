<?php
/*
 * Created by sydney_manjaro the 07/01/17
 */


namespace RASP\RaspBundle\Form\User;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use RASP\RaspBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvents;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;


class UserType extends BaseType
{

    public function __construct($class = 'RASP\RaspBundle\Entity\User')
    {
        parent::__construct($class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $listUfr = $options['listUfr'];
        $isSuperAdmin = $options['super_admin'];
        $builder
            ->add('username')
            ->add('email')
            ->add('super_admin', CheckboxType::class, array(
                'mapped' => false,  // because non-entiity field
                'required' => false,  // allow not checked
                'data' => $isSuperAdmin,
            ))
            ->add('ufr', null, array(
                    'class' => 'RASP\RaspBundle\Entity\Ufr',
                    'choices' => $listUfr
            ))
            ->add('save', SubmitType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'listUfr' => null,
            'super_admin' => false,
        ));
    }

    public function getName(){
        return 'rasp_user_registration_form';
    }
}

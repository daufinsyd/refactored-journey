<?php
/**
 * Created by PhpStorm.
 * User: sydney_manjaro
 * Date: 07/01/17
 * Time: 15:07
 */


namespace RASP\RaspBundle\Form\User;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use RASP\RaspBundle\Entity\Ufr;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UfrType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('save', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RASP\RaspBundle\Entity\Ufr', //UfrType::class,
        ));
    }
}
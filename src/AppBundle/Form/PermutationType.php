<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermutationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('target',ChoiceType::class,['choices' => [ 'Arctic (Cloud)'=> "Arctic (Cloud)",
                                                                            'DataScience'=> "DataScience",
                                                                            'ERP-BI'=> "ERP-BI",
                                                                            'GL'=> "GL",
                                                                            'Infini'=> "Infini",
                                                                            'IRT'=> "IRT",
                                                                            'ISEM'=> "ISEM",
                                                                            'NIDS'=> "NIDS",
                                                                            'TWIN'=> "TWIN",
                                                                            'SIM'=> "SIM",
                                                                            'SLEAM'=> "SLEAM"]]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Permutation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_permutation';
    }


}

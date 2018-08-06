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
        $builder->add('target',ChoiceType::class,['choices' => array(
            'GL' => 'GL',
            'INFINI' => 'INFINI',
            'DS' => 'DS',
            'ISEM' => 'ISEM',
            'SLEAM' => 'SLEAM',
            'INFO B' => 'INFO B',
            'NIDS' => 'NIDS',
            'SIM' => 'SIM',
            'ARCTIC (Cloud)' => 'ARCTIC (Cloud)',
            'IRT' => 'IRT',
            'TWIN' => 'TWIN',
            'ERP/BI' => 'ERP/BI'
        )]);
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

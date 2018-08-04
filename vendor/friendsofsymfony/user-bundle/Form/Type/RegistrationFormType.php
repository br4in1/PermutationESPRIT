<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle','attr'=>['placeholder'=>'form.email','class' => 'form-email form-control']))
            ->add('firstname',TextType::class,array('label' => 'form.firstname', 'translation_domain' => 'FOSUserBundle','attr'=>['placeholder'=>'form.firstname','class' => 'form-username form-control']))
            ->add('lastname',TextType::class,array('label' => 'form.lastname', 'translation_domain' => 'FOSUserBundle','attr'=>['placeholder'=>'form.lastname','class' => 'form-username form-control']))
            ->remove('username')
            //->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array('label' => 'form.password','attr'=>['placeholder'=>'form.password','class' => 'form-password form-control']),
                'second_options' => array('label' => 'form.password_confirmation','attr'=>['placeholder'=>'form.password_confirmation','class' => 'form-password form-control']),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('phone',TextType::class,array('label' => 'form.phone', 'translation_domain' => 'FOSUserBundle','attr'=>['placeholder'=>'form.phone','class' => 'form-username form-control']))
            ->add('specialite',ChoiceType::class,array(
                'label' => 'form.specialite',
                'translation_domain' => 'FOSUserBundle',
                'choices' => array(
                    'GL' => 'GL',
                    'INFINI' => 'INFINI',
                    'DS' => 'DS',
                    'ISEM' => 'ISEM',
                    'SLEAM' => 'SLEAM',
                    'INFO B' => 'INFO B',
                    'NIDS' => 'NIDS',
                    'SIM' => 'SIM',
                    'ArcTic (Cloud)' => 'ArcTic (Cloud)',
                    'IRT' => 'IRT',
                    'TWIN' => 'TWIN',
                    'ERP/BI' => 'ERP/BI'
                ),
                'placeholder'=>'form.specialite',
                'attr'=>['class' => 'form-select form-control']
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'registration',
        ));
    }

    // BC for SF < 3.0

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fos_user_registration';
    }
}

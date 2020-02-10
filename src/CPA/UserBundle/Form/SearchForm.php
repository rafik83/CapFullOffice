<?php

namespace CPA\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('date_debut', DateType::class, [
                    'widget' => 'single_text',
                    'attr' => ['class'=>'js-datepicker'],
                    'html5' => false,
        ])
                ->add('date_fin', DateType::class, [
                    'widget' => 'single_text',
                    'attr' => ['class'=>'js-datepicker'],
                    'html5' => false,
        ]);
    }

}

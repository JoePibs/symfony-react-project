<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType as TextArea;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Firstname','empty_data' => ''])
            ->add('lastname', TextType::class, ['label' => 'Lastname','empty_data' => ''])
            ->add('subject', TextType::class, ['label' => 'Subject','empty_data' => ''])
            ->add('content',TextArea::class, ['label' => 'Your message','empty_data' => ''])
            ->add('email', TextType::class, ['label' => 'Email','empty_data' => ''])    
            ->add('service_email', ChoiceType::class, [
                    'choices'  => [
                        'Aquapony' => 'aquapony@gmail.com',
                        'Dwarf throw' => 'dwarf@gmail.com',
                        'Shrink Hunt' => 'shrink@gmail.com'
                    ],
                    ])
            ->add('send', SubmitType::class, ['label' => 'Send'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;

class CategoryFormType extends AbstractType
{
    public function __construct(private FormListenerFactory $formListenerFactory)
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name','empty_data' => ''])
            ->add('slug', HiddenType::class)
            ->add('save', SubmitType::class, ['label' => 'Save Category'])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoslug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->autodate())
        ;
    }
    public function autoslug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if(empty($data['slug'])){
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['name']));
            $event->setData($data);
        }
    }

    public function autodate(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if(!$data instanceof Category ){
            return;
        }
        $data->setUpdatedAt(new \DateTimeImmutable());
        
        if(!$data->getId()){
            $data->setCreatedAt(new \DateTimeImmutable());
        }
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
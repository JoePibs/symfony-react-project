<?php

namespace App\Form;

use App\Entity\Recipe;
use Doctrine\ORM\Id\SequenceGenerator;
use Doctrine\ORM\Mapping\Entity;
use PhpParser\Node\Stmt\Label;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\Category;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class RecipeType extends AbstractType
{
    public function __construct(private FormListenerFactory $formListenerFactory)
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, ['label' => 'Titre','empty_data' => ''])
        ->add('slug', HiddenType::class)
        ->add('category',EntityType::class, ['class' => Category::class, 'choice_label' => 'name'])
        ->add('content')
        ->add('duration')
        ->add('ingredients')
        ->add('thumbnailFile', FileType::class, ['label' => 'Image',])
        ->add('save', SubmitType::class, ['label' => 'Save Recipe'])
        ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoslug('title'))
        ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->autodate());
    }

    public function autoslug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if(empty($data['slug'])){
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['title']));
            $event->setData($data);
        }
    }

    public function autodate(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if(!$data instanceof Recipe ){
            return;
        }
        $data->setUpdatedAt(new \DateTimeImmutable());
        
        if(!$data->getId()){
            $data->setCreateAt(new \DateTimeImmutable());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'validation_groups' => ['Default']
        ]);
    }
}

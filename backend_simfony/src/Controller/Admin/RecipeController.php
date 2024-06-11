<?php

namespace App\Controller\Admin;

use App\Entity\Recipe as EntityRecipe;
use App\Entity\Category;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('admin/recettes', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{

    #[Route('/', name: 'list')]
    public function index(Request $request, RecipeRepository $recipeRepository,EntityManagerInterface $entityManagerInterface, CategoryRepository $categoryRepository ): Response
    {
        $recipes = $recipeRepository->findAll();//$recipeRepository->findAllByCategory($slug);
        $category = (new Category())
        ->setUpdatedAt(new \DateTimeImmutable())
        ->setCreatedAt(new \DateTimeImmutable())
        ->setName('Poney')
        ->setSlug('poney');
        $recipes[0]->setCategory($category);
        $entityManagerInterface->flush();

        return $this->render('admin/recipe/index.html.twig',['recipes' => $recipes]);
    }

     #[Route('/create', name: 'create')]
    public function createRecipe(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $recipe = new EntityRecipe();
        $form = $this->createForm(RecipeType::class,$recipe);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setCreateAt(new \DateTimeImmutable());
            $entityManagerInterface->persist($recipe);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Recipe  created');
            
            return $this->redirectToRoute('admin.recipe.list');
        }
        Return $this->render('admin/recipe/create.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET','POST'], requirements: ['id' => Requirement::DIGITS])]
    public function editRecipe(EntityRecipe $recipe, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var UploadedFile $file */
            $file = $form->get('thumbnail')->getData();
            if ($file) {
                $fileName = $recipe->getId() . '_' . $recipe->getTitle() . '.' . $file->guessExtension();
                $file->move($this->getParameter('kernel.project_dir').'/public/recettes/images', $fileName);
                $recipe->setThumbnail($fileName);
            }
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Recipe updated');

            return $this->redirectToRoute('admin.recipe.list');
        }
        Return $this->render('admin/recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }

    #[Route('/{id}', name: 'delete',methods: ['DELETE'],requirements: ['id' => Requirement::DIGITS])]
    public function deleteRecipe(EntityRecipe $recipe, EntityManagerInterface $entityManagerInterface)
    {
    
        $entityManagerInterface->remove($recipe);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Recipe deleted');
        return $this->redirectToRoute('admin.recipe.list');
    }


}

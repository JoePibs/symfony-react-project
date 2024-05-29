<?php

namespace App\Controller;

use App\Entity\Recipe as EntityRecipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RecipeType;

class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'recipes.list')]
public function index(Request $request, RecipeRepository $recipeRepository,EntityManagerInterface $entityManagerInterface ): Response
{
    $recipes = $recipeRepository->findAll();

    return $this->render('recipe/index.html.twig',['recipes' => $recipes]);
}

    #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, Int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()], 301);
        }
        Return $this->render('recipe/recipe.html.twig', ['recipe' => $recipe]);
    }

    #[Route('/recettes/rapide', name: 'recipes-fast')]
    public function fastRecipes(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findRecipeWithLowDuration(10);
        Return $this->render('recipe/index.html.twig', ['recipes' => $recipes]);
    }

    #[Route('/recettes/{id}/edit', name: 'recipe-edit',methods: ['GET','POST'])]
    public function editRecipe(EntityRecipe $recipe, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManagerInterface->persist($recipe);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Recipe updated');

            return $this->redirectToRoute('recipe.show', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
        }
        Return $this->render('recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }
    
    
    #[Route('/recettes/create', name: 'recipe-create')]
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
            
            return $this->redirectToRoute('recipes.list');
        }
        Return $this->render('recipe/create.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }

    #[Route('/recettes/{id}/delete', name: 'recipe-delete',methods: ['DELETE'])]
    public function deleteRecipe(EntityRecipe $recipe, EntityManagerInterface $entityManagerInterface)
    {
    
        $entityManagerInterface->remove($recipe);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Recipe deleted');
        return $this->redirectToRoute('recipes.list');
    }


}

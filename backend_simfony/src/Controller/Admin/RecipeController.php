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
use App\Security\Voter\RecipeVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
#[Route('admin/recettes', name: 'admin.recipe.')]

class RecipeController extends AbstractController
{

    #[Route('/', name: 'list')]
    #[IsGranted(RecipeVoter::LIST)]
    public function index(Request $request, RecipeRepository $recipeRepository,Security $security ): Response
    {
        $page = $request->query->getInt('page', 1);
        $userId = $security->getUser()->getId();
        $canListAll = $security->isGranted(RecipeVoter::LIST_ALL);
        $recipes = $recipeRepository->paginateRecipes($page, $canListAll ? null : $userId);
        return $this->render('admin/recipe/index.html.twig',[
            'recipes' => $recipes,
        ]);
    }

     #[Route('/create', name: 'create')]
     #[IsGranted(RecipeVoter::CREATE)]
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

#[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    #[Route('/{id}', name: 'edit', methods: ['GET','POST'], requirements: ['id' => Requirement::DIGITS])]
    public function editRecipe(EntityRecipe $recipe, Request $request, EntityManagerInterface $entityManagerInterface, UploaderHelper $helper): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form -> handleRequest($request);
        $recipePath = $helper->asset($recipe, 'thumbnailFile');
        if ($form->isSubmitted() && $form->isValid()) {
        
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Recipe updated');

            return $this->redirectToRoute('admin.recipe.list');
        }
        Return $this->render('admin/recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form, 'recipePath' => $recipePath]);
    }
#[IsGranted('ROLE_ADMIN', 'ROLE_SUPER_ADMIN')]
    #[Route('/{id}', name: 'delete',methods: ['DELETE'],requirements: ['id' => Requirement::DIGITS])]
    public function deleteRecipe(EntityRecipe $recipe, EntityManagerInterface $entityManagerInterface)
    {
    
        $entityManagerInterface->remove($recipe);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Recipe deleted');
        return $this->redirectToRoute('admin.recipe.list');
    }


}

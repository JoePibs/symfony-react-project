<?php
namespace App\Controller\API;

use App\DTO\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\EntityManagerInterface;

class RecipesController extends AbstractController
{
    public function __construct()
    {
    }

#[Route('api/recipes', name: 'recipes', methods: ['GET'])]
    public function index(
        RecipeRepository  $recipeRepository ,
        Request $request, 
        SerializerInterface $serializer,
        #[MapQueryString]
        ?PaginationDTO $paginationDTO = null)
    {
        $recipes = $recipeRepository->paginateRecipes($request->query->getInt('page', 1));
        return $this->json($recipes,200,[],['groups' => 'recipe']);
    }


#[Route('api/recipe/{id}',requirements: ['id' => Requirement::DIGITS])]
    public function detailRecipe(Recipe $recipe)
    {
        return $this->json($recipe,200,[],['groups' => ['recipe','detail_recipe']]);
    }


    #[Route('api/recipes', name: 'create',methods: ['POST'])]
    public function create (Request $request, 
    #[MapRequestPayload(
        serializationContext: ['groups' => ['recipe','detail_recipe']]
    )]Recipe $recipe, EntityManagerInterface $entityManager)
    {
        $recipe->setCreateAt(new \DateTimeImmutable());
        $recipe->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($recipe);
        $entityManager->flush();
        return $this->json($recipe,201,[],['groups' => ['recipe','detail_recipe']]);
        
    }
}
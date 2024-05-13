<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'recipes.list')]
public function index(Request $request): Response
{
    return $this->render('recipe/index.html.twig', [
        'title' => 'Liste des recettes',
        'recipes' => [
            ['id' => 32, 'name' => 'Pate bolognaise', 'slug' => 'pate-bolo'],
            ['id' => 33, 'name' => 'Gâteau au chocolat', 'slug' => 'gateau-au-chocolat'],
            ['id' => 34, 'name' => 'Tarte au citron', 'slug' => 'tarte-au-citron'],
            ['id' => 2, 'name' => 'Gâteau au chocolat', 'slug' => 'gateau-au-chocolat'],
            ['id' => 3, 'name' => 'Tarte au citron', 'slug' => 'tarte-au-citron']
        ]
    ]);
}


    #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, Int $id): Response
    {
        Return $this->render('recipe/recipe.html.twig', [
            'slug' => $slug,
            'id' => $id
        ]);
    }
}

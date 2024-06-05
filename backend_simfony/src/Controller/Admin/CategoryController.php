<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping\Entity;
use App\Form\CategoryFormType;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/category', name: 'admin.category.')]
class CategoryController extends AbstractController
{

#[Route('/', name: 'index')]
    public function index(EntityManager $entityManager, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/category/index.html.twig', ['categories' => $categories]);
    }
#[Route('/create', name: 'create')]
    public function create(EntityManager $entityManager, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category created');
            return $this->redirectToRoute('admin.category.index');
        }
        Return $this->render('admin/category/create.html.twig', ['category' => $category, 'form' => $form]);
    }

#[Route('/{id}', name: 'edit', methods: ['GET','POST'],requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, EntityManager $entityManager, Category $category): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category updated');
            return $this->redirectToRoute('admin.category.index');
        }
        Return $this->render('admin/category/edit.html.twig', ['category' => $category, 'form' => $form]);
    }

#[Route('/{id}', name: 'remove', methods: ['DELETE'],requirements: ['id' => Requirement::DIGITS])]
    public function remove(Category $category, EntityManager $entityManagerInterface)
    {
        $entityManagerInterface->remove($category);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Category deleted');
        return $this->redirectToRoute('admin.category.index');
    }

}

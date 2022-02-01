<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/back/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="back_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('back/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $sluggerInterface->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_categories_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement de l\'image');
                    return $this->renderForm('back/product/new.html.twig', [
                        'category' => $category,
                        'form' => $form,
                    ]);
                }

                $category->setImage($newFilename);
            }
            
            $slugName = $sluggerInterface->slug($category->getName())->lower();
            $category->setSlug($slugName);
            
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Nouvelle catégorie ajoutée');
            return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('back/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $sluggerInterface->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_categories_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement de l\'image');
                    return $this->renderForm('back/product/new.html.twig', [
                        'category' => $category,
                        'form' => $form,
                    ]);
                }

                $category->setImage($newFilename);
            }

            $slugName = $sluggerInterface->slug($category->getName())->lower();
            $category->setSlug($slugName);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie modifiée');
            return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Catégorie supprimée');
        return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\PicturesManager;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/category")
 */
class CategoryController extends AbstractController
{
    /**
     * List of all categories registred 
     * 
     * @Route("/", name="back_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $categoryRepository->findAll();

        // KnpPaginatorBundle
        $categories = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1), 
            5 
        );
        $categories->setCustomParameters(['size' => 'small']);

        return $this->render('back/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Add a new category
     * 
     * @Route("/new", name="back_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface, PicturesManager $picturesManager): Response
    {
        $category = new Category();
        
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            // Call PictureManager service if an image is uploaded, if return false the user is notified and the adding category is cancelled

            if ($image) {
                if(!$picturesManager->add($category, 'Image', $image, 'images_categories_directory')){

                    $this->addFlash('warning', 'Erreur durant le chargement de l\'image');

                    return $this->renderForm('back/category/new.html.twig', [
                        'category' => $category,
                        'form' => $form,
                    ]);
                }
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
     * Edit an existing category 
     * 
     * @Route("/{id}/edit", name="back_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager, PicturesManager $picturesManager, SluggerInterface $sluggerInterface): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Call PictureManager service if an image is uploaded, if return false the user is notified and the edit category is cancelled

            $image = $form->get('image')->getData();
            if ($image) {
                if(!$picturesManager->add($category, 'Image', $image, 'images_categories_directory')){

                    $this->addFlash('warning', 'Erreur durant le chargement de l\'image');

                    return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
                }
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
     * Delete a category
     * 
     * @Route("/{id}", name="back_category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager, PicturesManager $picturesManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            
            // Call PictureManager service for delete the image
            if($category->getImage() !== null){
                $picturesManager->delete($category, 'Image', 'images_categories_directory');
            }

            $entityManager->remove($category);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Catégorie supprimée');

        return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * To delete an image on a given category
     * 
     * @Route("/{id}/{image}", name="back_category_picture", methods={"POST"})
     */
    public function deletePicture($image, Category $category, EntityManagerInterface $entityManager, PicturesManager $picturesManager)
    {
        // Call PictureManager service if return false the user is notified.
        if(!$picturesManager->delete($category, $image, 'images_categories_directory')){

            $this->addFlash('danger', 'Erreur durant la suppression de l\'image');
            return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
        };

        $entityManager->flush();

        $this->addFlash('success', 'Image supprimée');

        return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
    }
}

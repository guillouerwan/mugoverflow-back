<?php

namespace App\Controller\Back;

use App\Entity\MainColor;
use App\Form\MainColorType;
use App\Repository\MainColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/maincolor")
 */
class MainColorController extends AbstractController
{
    /**
     * List of all main colors registred 
     * 
     * @Route("/", name="back_main_color_index", methods={"GET"})
     */
    public function index(MainColorRepository $mainColorRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $mainColorRepository->findAll();

        // KnpPaginatorBundle
        $mainColors = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1), 
            5 
        );
        $mainColors->setCustomParameters(['size' => 'small']);
        
        return $this->render('back/main_color/index.html.twig', [
            'main_colors' => $mainColors,
        ]);
    }

    /**
     * Add a new main color
     * 
     * @Route("/new", name="back_main_color_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mainColor = new MainColor();

        $form = $this->createForm(MainColorType::class, $mainColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($mainColor);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvelle couleur ajouté');

            return $this->redirectToRoute('back_main_color_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/main_color/new.html.twig', [
            'main_color' => $mainColor,
            'form' => $form,
        ]);
    }

    /**
     * Edit an existing main color 
     * 
     * @Route("/{id}/edit", name="back_main_color_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MainColor $mainColor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MainColorType::class, $mainColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', 'Couleur éditée');

            return $this->redirectToRoute('back_main_color_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/main_color/edit.html.twig', [
            'main_color' => $mainColor,
            'form' => $form,
        ]);
    }

    /**
     * Delete a main color
     * 
     * @Route("/{id}", name="back_main_color_delete", methods={"POST"})
     */
    public function delete(Request $request, MainColor $mainColor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mainColor->getId(), $request->request->get('_token'))) {

            $entityManager->remove($mainColor);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'Couleur supprimée !');

        return $this->redirectToRoute('back_main_color_index', [], Response::HTTP_SEE_OTHER);
    }
}

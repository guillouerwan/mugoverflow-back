<?php

namespace App\Controller\Back;

use App\Entity\SecondaryColor;
use App\Form\SecondaryColorType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\SecondaryColorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/secondarycolor")
 */
class SecondaryColorController extends AbstractController
{
    /**
     * @Route("/", name="back_secondary_color_index", methods={"GET"})
     */
    public function index(SecondaryColorRepository $secondaryColorRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $secondaryColorRepository->findAll();

        $secondaryColors = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1), 
            5 
        );
        $secondaryColors->setCustomParameters(['size' => 'small']);
        return $this->render('back/secondary_color/index.html.twig', [
            'secondary_colors' => $secondaryColors,
        ]);
    }

    /**
     * @Route("/new", name="back_secondary_color_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $secondaryColor = new SecondaryColor();
        $form = $this->createForm(SecondaryColorType::class, $secondaryColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($secondaryColor);
            $entityManager->flush();

            return $this->redirectToRoute('back_secondary_color_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/secondary_color/new.html.twig', [
            'secondary_color' => $secondaryColor,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_secondary_color_show", methods={"GET"})
     */
    public function show(SecondaryColor $secondaryColor): Response
    {
        return $this->render('back/secondary_color/show.html.twig', [
            'secondary_color' => $secondaryColor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_secondary_color_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SecondaryColor $secondaryColor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SecondaryColorType::class, $secondaryColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('back_secondary_color_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/secondary_color/edit.html.twig', [
            'secondary_color' => $secondaryColor,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_secondary_color_delete", methods={"POST"})
     */
    public function delete(Request $request, SecondaryColor $secondaryColor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$secondaryColor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($secondaryColor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_secondary_color_index', [], Response::HTTP_SEE_OTHER);
    }
}

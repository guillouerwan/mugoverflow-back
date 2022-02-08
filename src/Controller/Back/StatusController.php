<?php

namespace App\Controller\Back;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/status")
 */
class StatusController extends AbstractController
{
    /**
     * List of all status registred 
     * 
     * @Route("/", name="back_status_index", methods={"GET"})
     */
    public function index(StatusRepository $statusRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $statusRepository->findAll();

        // KnpPaginatorBundle
        $statuses = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1), 
            5 
        );
        $statuses->setCustomParameters(['size' => 'small']);
        
        return $this->render('back/status/index.html.twig', [
            'statuses' => $statuses,
        ]);
    }

    /**
     * Add a new status
     * 
     * @Route("/new", name="back_status_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $status = new Status();

        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($status);
            $entityManager->flush();

            $this->addFlash('success', 'Statut ajouté');

            return $this->redirectToRoute('back_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/status/new.html.twig', [
            'status' => $status,
            'form' => $form,
        ]);
    }

    /**
     * Edit an existing status 
     * 
     * @Route("/{id}/edit", name="back_status_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Status $status, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', 'Statut mis à jour');

            return $this->redirectToRoute('back_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/status/edit.html.twig', [
            'status' => $status,
            'form' => $form,
        ]);
    }

    /**
     * Delete a status
     * 
     * @Route("/{id}", name="back_status_delete", methods={"POST"})
     */
    public function delete(Request $request, Status $status, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$status->getId(), $request->request->get('_token'))) {
            $entityManager->remove($status);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Statut supprimé');

        return $this->redirectToRoute('back_status_index', [], Response::HTTP_SEE_OTHER);
    }
}

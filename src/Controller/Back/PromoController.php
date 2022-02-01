<?php

namespace App\Controller\Back;

use App\Entity\Promo;
use App\Form\PromoType;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/promo")
 */
class PromoController extends AbstractController
{
    /**
     * @Route("/", name="back_promo_index", methods={"GET"})
     */
    public function index(PromoRepository $promoRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $promoRepository->findAll();

        $promos = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1), 
            5 
        );
        return $this->render('back/promo/index.html.twig', [
            'promos' => $promos,
        ]);
    }

    /**
     * @Route("/new", name="back_promo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promo = new Promo();
        $form = $this->createForm(PromoType::class, $promo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promo);
            $entityManager->flush();
            $this->addFlash('success', 'La promo à bien été ajouté');
            return $this->redirectToRoute('back_promo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/promo/new.html.twig', [
            'promo' => $promo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_promo_show", methods={"GET"})
     */
    public function show(Promo $promo): Response
    {
        return $this->render('back/promo/show.html.twig', [
            'promo' => $promo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_promo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Promo $promo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromoType::class, $promo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Promo édité !');
            return $this->redirectToRoute('back_promo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/promo/edit.html.twig', [
            'promo' => $promo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_promo_delete", methods={"POST"})
     */
    public function delete(Request $request, Promo $promo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promo);
            $entityManager->flush();
        }
        $this->addFlash('danger', 'La promo à bien été supprimé');
        return $this->redirectToRoute('back_promo_index', [], Response::HTTP_SEE_OTHER);
    }
}

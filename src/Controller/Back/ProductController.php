<?php

namespace App\Controller\Back;

use DateTime;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\PicturesManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/product")
 */
class ProductController extends AbstractController
{
    /**
     * List of all products registred 
     * 
     * @Route("/", name="back_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $productRepository->findBy([], ['id' => 'DESC']);

        // KnpPaginatorBundle
        $products = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1), 
            5 
        );
        $products->setCustomParameters(['size' => 'small']);

        return $this->render('back/product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * Add a new product
     * 
     * @Route("/new", name="back_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, PicturesManager $picturesManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mockupFrontFile = $form->get('mockupFront')->getData();
            $mockupOverviewFile = $form->get('mockupOverview')->getData();
            $assetFrontFile = $form->get('assetFront')->getData();
            $assetBackFile = $form->get('assetBack')->getData();

            // Call PictureManager service for each images uploaded, if return false the user is notified and the adding is cancelled

            if ($mockupFrontFile) {
                if(!$picturesManager->add($product, 'mockupFront', $mockupFrontFile, 'images_products_directory')){
                    $this->addFlash('warning', 'Erreur durant le chargement du mockup front');
                    return $this->renderForm('back/product/new.html.twig', [
                        'product' => $product,
                        'form' => $form,
                    ]);
                }
            }
            if ($mockupOverviewFile) {
                if(!$picturesManager->add($product, 'mockupOverview', $mockupOverviewFile, 'images_products_directory')){
                    $this->addFlash('warning', 'Erreur durant le chargement du mockup overview');
                    return $this->renderForm('back/product/new.html.twig', [
                        'product' => $product,
                        'form' => $form,
                    ]);
                }
            }
            if ($assetFrontFile) {
                if(!$picturesManager->add($product, 'assetFront', $assetFrontFile, 'images_products_directory')){
                    $this->addFlash('warning', 'Erreur durant le chargement de l\'asset front');
                    return $this->renderForm('back/product/new.html.twig', [
                        'product' => $product,
                        'form' => $form,
                    ]);
                }
            }
            if ($assetBackFile) {
                if(!$picturesManager->add($product, 'assetBack', $assetBackFile, 'images_products_directory')){
                    $this->addFlash('warning', 'Erreur durant le chargement de l\'asset back');
                    return $this->renderForm('back/product/new.html.twig', [
                        'product' => $product,
                        'form' => $form,
                    ]);
                }
            }

            $slugName = $slugger->slug($product->getName())->lower();
            $product->setSlug($slugName);

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté');

            return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Show an existing product 
     * 
     * @Route("/{id}", name="back_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('back/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Edit an existing product 
     * 
     * @Route("/{id}/edit", name="back_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger, PicturesManager $picturesManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mockupFrontFile = $form->get('mockupFront')->getData();
            $mockupOverviewFile = $form->get('mockupOverview')->getData();
            $assetFrontFile = $form->get('assetFront')->getData();
            $assetBackFile = $form->get('assetBack')->getData();

            // Call PictureManager service for each new image uploaded, if return false the user is notified and the edit is cancelled

            if ($mockupFrontFile) {
                if(!$picturesManager->add($product, 'mockupFront', $mockupFrontFile, 'images_products_directory')){
                    $this->addFlash('warning', 'Erreur durant le chargement du mockup front');
                    return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
                }
            }
            if ($mockupOverviewFile) {
                if(!$picturesManager->add($product, 'mockupOverview', $mockupOverviewFile, 'images_products_directory')){
                    $this->addFlash('warning', 'Erreur durant le chargement du mockup overview');
                    return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
                }
            }
            if ($assetFrontFile) {
                if(!$picturesManager->add($product, 'assetFront', $assetFrontFile, 'images_products_directory')){
                    $this->addFlash('warning', 'Erreur durant le chargement de l\'asset front');
                    return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
                }
            }
            if ($assetBackFile) {
                if(!$picturesManager->add($product, 'assetBack', $assetBackFile, 'images_products_directory')){
                    $this->addFlash('warning', 'Erreur durant le chargement de l\'asset back');
                    return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
                }
            }

            $product->setUpdatedAt(new DateTime());

            $slugName = $slugger->slug($product->getName())->lower();
            $product->setSlug($slugName);

            $this->addFlash('success', 'Produit modifié');

            $entityManager->flush();

            return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Delete a product
     * 
     * @Route("/{id}", name="back_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager, PicturesManager $picturesManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {

            // Call PictureManager service for each image to delete.

            if($product->getMockupFront() !== null){
                $picturesManager->delete($product, 'MockupFront', 'images_products_directory');
            }
            if($product->getMockupOverview() !== null){
                $picturesManager->delete($product, 'MockupOverview', 'images_products_directory');
            }
            if($product->getAssetFront() !== null){
                $picturesManager->delete($product, 'AssetFront', 'images_products_directory');
            }
            if($product->getAssetBack() !== null){
                $picturesManager->delete($product, 'AssetBack', 'images_products_directory');
            }

            $entityManager->remove($product);

            $this->addFlash('success', 'Produit supprimé');

            $entityManager->flush();
        }

        return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * To delete an image on a given product
     * 
     * @Route("/{id}/{image}", name="back_product_picture", methods={"POST"})
     */
    public function deletePicture($image, Product $product, EntityManagerInterface $entityManager, PicturesManager $picturesManager)
    {
        // Call PictureManager service if return false the user is notified.
        if(!$picturesManager->delete($product, $image, 'images_products_directory')){

            $this->addFlash('danger', 'Erreur durant la suppression de l\'image');
            
            return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
        };

        $entityManager->flush();

        $this->addFlash('success', 'Image supprimée');

        return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
    }
}

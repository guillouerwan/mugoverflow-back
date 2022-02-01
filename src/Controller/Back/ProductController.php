<?php

namespace App\Controller\Back;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

/**
 * @Route("/back/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="back_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('back/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mockupFrontFile = $form->get('mockupFront')->getData();
            $mockupOverviewFile = $form->get('mockupOverview')->getData();
            $assetFrontFile = $form->get('assetFront')->getData();
            $assetBackFile = $form->get('assetBack')->getData();

            if ($mockupFrontFile) {
                $originalFilename = pathinfo($mockupFrontFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$mockupFrontFile->guessExtension();
                try {
                    $mockupFrontFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement du mockup front');
                    return $this->renderForm('back/product/new.html.twig', [
                        'product' => $product,
                        'form' => $form,
                    ]);
                }

                $product->setMockupFront($newFilename);
            }
            if ($mockupOverviewFile) {
                $originalFilename = pathinfo($mockupOverviewFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$mockupOverviewFile->guessExtension();
                try {
                    $mockupOverviewFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement du mockup back');
                    return $this->renderForm('back/product/new.html.twig', [
                        'product' => $product,
                        'form' => $form,
                    ]);
                }

                $product->setMockupOverview($newFilename);
            }
            if ($assetFrontFile) {
                $originalFilename = pathinfo($assetFrontFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$assetFrontFile->guessExtension();
                try {
                    $assetFrontFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement de l\'image');
                    return $this->renderForm('back/product/new.html.twig', [
                        'product' => $product,
                        'form' => $form,
                    ]);
                }

                $product->setAssetFront($newFilename);
            }
            if ($assetBackFile) {
                $originalFilename = pathinfo($assetBackFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$assetBackFile->guessExtension();
                try {
                    $assetBackFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement du logo');
                    return $this->renderForm('back/product/new.html.twig', [
                        'product' => $product,
                        'form' => $form,
                    ]);
                }

                $product->setAssetBack($newFilename);
            }
            $slugName = $slugger->slug($product->getName());
            $product->setSlug($slugName);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('back/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mockupFrontFile = $form->get('mockupFront')->getData();
            $mockupOverviewFile = $form->get('mockupOverview')->getData();
            $assetFrontFile = $form->get('assetFront')->getData();
            $assetBackFile = $form->get('assetBack')->getData();
            
            if ($mockupFrontFile) {
                $originalFilename = pathinfo($mockupFrontFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$mockupFrontFile->guessExtension();
                try {
                    $mockupFrontFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement du mockup front');
                    return $this->redirectToRoute('back_product_index', [
                        'product' => $product,
                        'form' => $form,
                    ],
                    Response::HTTP_SEE_OTHER);
                }

                $product->setMockupFront($newFilename);
            }
            if ($mockupOverviewFile) {
                $originalFilename = pathinfo($mockupOverviewFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$mockupOverviewFile->guessExtension();
                try {
                    $mockupOverviewFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement du mockup back');
                    return $this->redirectToRoute('back_product_index', [
                        'product' => $product,
                        'form' => $form,
                    ],
                    Response::HTTP_SEE_OTHER);
                }

                $product->setMockupOverview($newFilename);
            }
            if ($assetFrontFile) {
                $originalFilename = pathinfo($assetFrontFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$assetFrontFile->guessExtension();
                try {
                    $assetFrontFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement de l\'image');
                    return $this->redirectToRoute('back_product_index', [
                        'product' => $product,
                        'form' => $form,
                    ],
                    Response::HTTP_SEE_OTHER);
                }
                
                $product->setAssetFront($newFilename);
            }
            if ($assetBackFile) {
                $originalFilename = pathinfo($assetBackFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$assetBackFile->guessExtension();
                try {
                    $assetBackFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur durant le chargement du logo');
                    return $this->redirectToRoute('back_product_index', [
                        'product' => $product,
                        'form' => $form,
                    ],
                    Response::HTTP_SEE_OTHER);
                }

                $product->setAssetBack($newFilename);
            }

            $product->setUpdatedAt(new DateTime());

            $slugName = $slugger->slug($product->getName());
            $product->setSlug($slugName);

            $entityManager->flush();

            return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/{image}", name="back_product_picture", methods={"POST"})
     */
    public function deletePicture($image, Product $product, EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        if ($image === "mockupFront"){
            $path = $this->getParameter('images_directory').'/'.$product->getMockupFront();
            $filesystem->remove($path);
            $product->setMockupFront(null);
        }
        if ($image === "mockupOverview"){
            $path = $this->getParameter('images_directory').'/'.$product->getMockupOverview();
            $filesystem->remove($path);
            $product->setMockupOverview(null);
        }
        if ($image === "assetFront"){
            $path = $this->getParameter('images_directory').'/'.$product->getAssetFront();
            $filesystem->remove($path);
            $product->setAssetFront(null);
        }
        if ($image === "assetBack"){
            $path = $this->getParameter('images_directory').'/'.$product->getAssetBack();
            $filesystem->remove($path);
            $product->setAssetBack(null);
        }

        $entityManager->flush();

        return $this->redirectToRoute('back_product_index', [], Response::HTTP_SEE_OTHER);
    }
}

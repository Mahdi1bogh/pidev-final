<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Product1Type;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
#[Route('/product')]
class ProductsController extends AbstractController
{
    #[Route('/stats', name: 'product_stats')]
    public function stats(ProductRepository $repository): JsonResponse
    {
        $totalCat = $repository->getCategorytotalCounts();
        $quantities = $repository->getQuantityStats();

        $data = [
            'categories' => $totalCat,
            'quantities' => $quantities,
        ];

        return new JsonResponse($data);
    }
    #[Route('/all', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository,CategoryRepository $categoryRepository)
    {
        
        $products = $productRepository->findAll();
        $categories = $categoryRepository->findAll();
    
    return $this->render('product/index.html.twig', [
        'products' => $products,
        'categories' => $categories,
    ]);
    }
    // public function index(ProductRepository $productRepository, NormalizerInterface $normalizer): JsonResponse
    // {
    //     $products = $productRepository->findAll();
        
    //     $data = $normalizer->normalize($products, 'json', ['groups' => 'product:read']);
        
    //     return $this->json($data);
    // }


    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}/rate', name: 'app_product_rate', methods: ['POST'])]
    public function rate(Request $request, Product $product): Response
    {
        $rating = (int) $request->request->get('rating');
        $product->setRating($rating);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Redirect back to the product page
        return $this->redirectToRoute('app_home_magasin', ['id' => $product->getId()]);
    }
   
    #[Route('/chart', name: 'app_product_chart', methods: ['GET', 'POST'])]
    public function chartAction(ProductRepository $repo)
{
   
    $products = $repo->findAll();
    $data = [];

    foreach ($products as $product) {
        if (isset($data[$product->getCategory()->getName()])) {
            $data[$product->getCategory()->getName()]++;
        } else {
            $data[$product->getCategory()->getName()] = 1;
        }
    }

    return $this->json($data);
}
#[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route("/products/filter", name:"get_filtered_products", methods:["POST"])]
public function filterProducts(Request $request, ProductRepository $productRepository)
{
    $category = $request->request->get('category');
    $priceMin = $request->request->get('priceMin');
    $priceMax = $request->request->get('priceMax');
    $search = $request->request->get('search');

    $filteredProducts = $productRepository->findFilteredProducts($category, $priceMin, $priceMax, $search);

    $productList = $this->renderView('product/product_list.html.twig', [
        'produits' => $filteredProducts
    ]);

    return new JsonResponse($productList);
}
}

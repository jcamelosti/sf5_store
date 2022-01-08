<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

#[Route('/products', name: 'products_')]
class ProductController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/', name: 'index', methods: "GET")]
    public function index()
    {
        $products = $this->doctrine->getRepository(Product::class)->findAll();

        return $this->json([
            'data' => $products
        ]);
    }

    #[Route('/{productId}', name: 'show', methods: "GET")]
    public function show($productId)
    {
        $product = $this->doctrine->getRepository(Product::class)->find($productId);

        return $this->json([
            'data' => $product
        ]);
    }

    #[Route('/', name: 'create', methods: "POST")]
    public function create(Request $request){
        $productData = $request->request->all();

        $product = new Product();
        
        $product->setName($productData['name']);
        $product->setDescription($productData['description']);
        $product->setSlug($productData['slug']);
        $product->setIsActive(true);
        $product->setContent($productData['content']);
        $product->setPrice($productData['price']);

        $product->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $product->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager = $this->doctrine->getManager();
        
        $manager->persist($product);
        $manager->flush();

        return $this->json([
            'message' => 'Produto Criado com Sucesso!',
        ]);
    }

    #[Route('/{productId}', name: 'update', methods: ["PUT","PATCH"])]
    public function update(Request $request, $productId){
        $productData = $request->request->all();
        $manager = $this->doctrine->getManager();

        $product = $this->doctrine->getRepository(Product::class)->find($productId);
        
        $product->setName($productData['name']);
        $product->setDescription($productData['description']);
        $product->setSlug($productData['slug']);
        //$product->setIsActive(true);
        $product->setContent($productData['content']);
        $product->setPrice($productData['price']);

        //$product->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $product->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager->flush();

        return $this->json([
            'message' => 'Produto Atualizado com Sucesso!',
        ]);
    }

    #[Route('/{productId}', name: 'remove', methods: ["DELETE"])]
    public function remove($productId){
        $manager = $this->doctrine->getManager();
        $product = $this->doctrine->getRepository(Product::class)->find($productId);
        
        $manager->remove($product);
        $manager->flush();         
        
        return $this->json([
            'message' => 'Produto Removido com Sucesso!',
        ]);
    }
}
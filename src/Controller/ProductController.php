<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/products', name: 'products_')]
class ProductController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/', name: 'index', methods: "GET")]
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    #[Route('/', name: 'create', methods: "POST")]
    public function create(){
        $product = new Product();
        $product->setName('Primeiro Produto');
        $product->setDescription('');
        $product->setSlug('primeiro-produto');
        $product->setIsActive(true);
        $product->setContent('Conteudo Produto 1');
        $product->setPrice(1999);
        $product->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $product->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager = $this->doctrine->getManager();
        
        $manager->persist($product);
        $manager->flush();

        return $this->json([
            'message' => 'Produto Criado com Sucesso!',
        ]);
    }
}

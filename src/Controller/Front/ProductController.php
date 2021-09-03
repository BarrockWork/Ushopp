<?php

namespace App\Controller\Front;

use App\Entity\Filter\SearchProduct;
use App\Entity\Product;
use App\Form\FilterProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/products")
 */
class ProductController extends AbstractController
{

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="all_products")
     */
    public function index(ProductRepository $repo, Request $request): Response
    {
        $search = new SearchProduct();
        $productFiltered = $repo->findAll();

        $form = $this->createForm(FilterProductType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $price = $search->getPrice();
            $category = $search->getCategories();
            $productFiltered = $repo->findFilter($price, $category);
        }

        return $this->render('Front/product/index.html.twig', [
            'productFiltered' => $productFiltered,
            'form' => $form->createView()
        ]);
    }


    /**
     * Show detail from a product both side(admin and customer side)
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('Front/product/show.html.twig', [
            'product' => $product,
        ]);
    }
}

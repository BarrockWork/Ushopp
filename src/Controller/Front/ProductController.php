<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Entity\Filter\SearchProduct;
use App\Entity\Product;
use App\Form\CommentType;
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
        $productFiltered = $repo->findByActive(true);
        $form = $this->createForm(FilterProductType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les valeurs des filtres
            $maxPrice = $search->getMaxPrice();
            $minPrice = $search->getMinPrice();
            $category = $search->getCategories();
            $sortProduct = $search->getSortProduct();

            // Produit filtrer
            $productFiltered = $repo->productFilter($maxPrice, $minPrice, $category, $sortProduct);

        }

        return $this->render('Front/product/index.html.twig', [
            'productFiltered' => $productFiltered,
            'form' => $form->createView()
        ]);
    }


    /**
     * Show detail from a product both side(admin and customer side)
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Product $product, Request $request): Response
    {
        $user = $this->getUser();
        // ajout d'un commentaire
        $comment = new Comment();
        $comment->setUser($user);
        $comment->setProduct($product);
        $form = $this->createForm(CommentType::class, $comment);
        $form->remove('isModerate');
        $form->remove('product');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            // FLash message
            $this->addFlash(
                'success',
                $this->translator->trans('comment.messages.successAdd')
            );

            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);

        }
        return $this->renderForm('Front/product/show.html.twig', [
            'product' => $product,
            'comment' => $comment,
            'form' => $form
        ]);
    }


}

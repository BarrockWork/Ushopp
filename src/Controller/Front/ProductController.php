<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Entity\Filter\SearchProduct;
use App\Entity\Product;
use App\Form\CommentType;
use App\Form\FilterProductType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function index(ProductRepository $repo, Request $request, PaginatorInterface $paginator, SessionInterface $session): Response
    {
        $search = new SearchProduct();

        $productFilteredSession = $session->get('productFiltered', []);

        if (empty($productFilteredSession)) {
            $session->set('productFiltered', [
                'maxPrice' => null,
                'minPrice' => null,
                'category' => null,
                'sortProduct' => null,
            ]);
            $productFilteredSession = $session->get('productFiltered');
        }

        $productFiltered = $repo->productFilter($productFilteredSession['maxPrice'], $productFilteredSession['minPrice'], $productFilteredSession['category'], $productFilteredSession['sortProduct']);

        $form = $this->createForm(FilterProductType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les valeurs des filtres
            $maxPrice = $search->getMaxPrice();
            $minPrice = $search->getMinPrice();
            $category = $search->getCategories();
            $sortProduct = $search->getSortProduct();

            $session->set('productFiltered', [
                'maxPrice' => $maxPrice,
                'minPrice' => $minPrice,
                'category' => $category,
                'sortProduct' => $sortProduct,
            ]);

            // Produit filtrer
            $productFiltered = $repo->productFilter($maxPrice, $minPrice, $category, $sortProduct);
        }

        $productFiltered = $paginator->paginate(
            $productFiltered, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            9 // Nombre de résultats par page
        );

        return $this->render('product/index.html.twig', [
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
        return $this->renderForm('product/show.html.twig', [
            'product' => $product,
            'comment' => $comment,
            'form' => $form
        ]);
    }


}

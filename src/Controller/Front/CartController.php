<?php

namespace App\Controller\Front;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/cart")
 */
class CartController extends AbstractController
{
    /**
     * @var Cart
     */
    private $cartClasse;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(Cart $cartClasse, TranslatorInterface $translator, EntityManagerInterface $em) {
        $this->cartClasse = $cartClasse;
        $this->translator = $translator;
        $this->em = $em;
    }

    /**
     * Cart index
     * @Route("/", name="my_cart")
     */
    public function index(): Response
    {
        $cartComplete = $this->cartClasse->getFull();

        return $this->render('cart/index.html.twig', [
            'cart' => $cartComplete
        ]);
    }

    /**
     * Add a product in the cart session
     *
     * @Route("/add/{id}", name="add_to_cart")
     */
    public function add($id): Response
    {
        $this->cartClasse->add($id);

        $this->addFlash(
            'success',
            $this->translator->trans('cart.messages.successAdd')
        );

        return $this->redirectToRoute('my_cart');
    }

    /**
     * Add a product in the cart session without redirection to cart
     *
     * @Route("/add_without_redirect/{id}/{routeName}", name="add_to_cart_without_redirection_to_cart")
     */
    public function addWithoutRedirectToCart($id, $routeName): Response
    {
        $this->cartClasse->add($id);

        $this->addFlash(
            'success',
            $this->translator->trans('cart.messages.successAdd')
        );

        return $this->redirectToRoute($routeName);
    }

    /**
     * Add a product with a quantity in the cart session
     *
     * @Route("/add_quantity/{id}/{quantity}", name="add_to_cart_with_quantity")
     */
    public function addWithQuantity($id, $quantity): Response
    {
        $response = new JsonResponse();

        // If quantity is not an integer
        if(intval($quantity) === 0) {
            $this->addFlash(
                'warning',
                $this->translator->trans('cart.errorAddCart')
            );

            return $this->redirectToRoute('product_show', [
                'id' =>$id
            ]);

        }

        $this->cartClasse->addWithQuantity($id, intval($quantity));

        $this->addFlash(
            'success',
            $this->translator->trans('cart.messages.successAdd')
        );

        $response->setData([
           'urlRedirect' => $this->generateUrl('my_cart')
        ]);
        return $response;
    }

    /**
     * Add a product in the cart session
     *
     * @Route("/increase/{id}", name="increase_to_cart")
     */
    public function increase($id): Response
    {
        $this->cartClasse->add($id);

        return $this->redirectToRoute('my_cart');
    }

    /**
     * Decrease a product in the cart session
     *
     * @Route("/decrease/{id}", name="decrease_to_cart")
     */
    public function decrease($id): Response
    {
        $this->cartClasse->decrease($id);

        return $this->redirectToRoute('my_cart');
    }

    /**
     * Remove a product in the cart
     *
     * @Route("/removeProduct/{id}", name="remove_cart_product")
     */
    public function removeProduct($id): Response
    {
        $this->cartClasse->removeProduct($id);
        return $this->redirectToRoute('my_cart');
    }

    /**
     * Erase the cart session
     *
     * @Route("/remove", name="remove_my_cart")
     */
    public function remove(): Response
    {
        $this->cartClasse->remove();
        return $this->redirectToRoute('all_products');
    }

}

<?php

namespace App\Classe;



use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SessionInterface $session, EntityManagerInterface $em) {
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * Add a product in the cart session
     * @param $id
     */
    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])) {
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    /**
     * Add a product with a quantity in the cart session
     * @param $id
     * @param $quantity
     */
    public function addWithQuantity($id, $quantity)
    {
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])) {
            $cart[$id] += $quantity;
        }else{
            $cart[$id] = $quantity;
        }

        $this->session->set('cart', $cart);
    }

    /**
     * Decrease a product in the cart session
     * @param $id
     */
    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if($cart[$id] > 1) {
            $cart[$id]--;
        }else{
           unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    /**
     * Retrieve the cart session
     *
     * @return mixed
     */
    public function get() {
        return $this->session->get('cart');
    }

    /**
     * Retrieve the cart with full datas
     * @return array
     */
    public function getFull() {
        $cartComplete = [];
        if($this->get())
        {
            foreach ($this->get() as $id => $quantity) {
                $productEntity =  $this->em->getRepository(Product::class)->findOneById($id);

                // Delete a product if it not exists
                if(!$productEntity) {
                    $this->removeProduct($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $productEntity,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }

    /**
     * Remove the cart session
     *
     * @return mixed
     */
    public function remove() {
        return $this->session->remove('cart');
    }

    /**
     * Remove a product from the cart session
     * @param $id
     * @return mixed
     */
    public function removeProduct($id) {

        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }
}
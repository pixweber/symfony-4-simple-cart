<?php
namespace App\Service;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionCart {

    /**
     * @var Session
     */
    private $session;

    /**
     * SessionCart constructor.
     * @param Session $session
     */
    public function __construct(SessionInterface $session) {
        $this->session = $session;
    }

    /**
     * @param Session $session
     * @return int
     */
    public function getCartItemsCount() {
        $cartItems = $this->session->get('cartItems');

        $counter = 0;
        if ($cartItems) {
            foreach ($cartItems as $cartItem) {
                $counter += $cartItem['quantity'];
            }
        }

        return $counter;
    }

    /**
     * @param $productId
     * @return int
     */
    public function getCartItemKeyByProductId($productId): int {
    }

    /**
     * @return mixed
     */
    public function getCartItems() {
        // Get current cart items
        return $this->session->get('cartItems');
    }

    /**
     * @param $productId
     * @param $quantity
     */
    public function addItemToCart($productId, $quantity) {

        $product = new Product();
        $cartItems = $this->getCartItems();

        if ($this->findProductInCart($productId)) {
            $cartItems[$this->findProductInCart($productId)]['quantity'] += 1;
            $cartItems[$this->findProductInCart($productId)]['total'] += $product->getPrice();
        } else {
            $cartItems[] = array(
                'productId' => $productId,
                'quantity' => 1,
                'price' => $product->getPrice(),
                'total' => $product->getPrice()
            );
        }

        $this->session->set('cartItems', $cartItems);
    }

    /**
     * @param $productId
     * @return int|string|null
     */
    public function findProductInCart($productId) {
        $cartItems = $this->getCartItems();

        $cartItemKey = null;

        if ($cartItems) {
            foreach ($cartItems as $key => $cartItem) {
                if ( $cartItem['productId'] == $productId) {
                    $cartItemKey = $key;
                    break;
                }
            }
        }
        return $cartItemKey;
    }
}
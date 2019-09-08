<?php
namespace App\Service;
use App\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionCart {

    /**
     * @var Session
     */
    private $session;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * SessionCart constructor.
     * @param Session $session
     */
    public function __construct(SessionInterface $session, ManagerRegistry $managerRegistry) {
        $this->session = $session;
        $this->managerRegistry = $managerRegistry;
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
        $product = $this->managerRegistry->getRepository(Product::class)->find($productId);

        $cartItems = $this->getCartItems();

        if ( $this->findProductInCart($productId) !== null) {
            $cartItems[$this->findProductInCart($productId)]['quantity'] += 1;
            $cartItems[$this->findProductInCart($productId)]['total'] += $product->getPrice();
        } else {
            $cartItems[] = array(
                'productId' => $productId,
                'quantity' => $quantity,
                'price' => $product->getPrice(),
                'total' => $product->getPrice()
            );
        }

        $this->session->set('cartItems', $cartItems);
    }

    /**
     * @param $productId
     */
    public function removeItemFromCart($productId): void {
        $cartItems = $this->getCartItems();
        $index = $this->findProductInCart($productId);
        array_splice($cartItems, $index, 1);
        $this->session->set('cartItems', $cartItems);
    }

    /**
     * @param $productId
     * @return int|string|null Return cart item key that contains productId
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

    /**
     * @return int|mixed
     */
    public function getCartSubtotal(): float {
        $subtotal = 0;

        if (!$this->getCartItems()) {
            return $subtotal;
        }

        foreach ($this->getCartItems() as $cartItem) {
            $subtotal += $cartItem['total'];
        }

        return (float)$subtotal;
    }

    /**
     * @return float
     */
    public function getTotalTaxes(): float {
        return $this->getCartSubtotal() * 0.2; // Taxes rate applied in France
    }

    /**
     * @return float
     */
    public function getCartTotal(): float {
        return $this->getCartSubtotal() + $this->getTotalTaxes();
    }

    /**
     *
     */
    public function emptyCart(): void {
        $this->session->set('cartItems', null);
    }

    /**
     * @return bool
     */
    public function isCartEmtpy(): bool {
        return !$this->getCartItemsCount() ?  true : false;
    }
}
<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddItemType;
use App\Service\SessionCart;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;


class CartController extends AbstractController {

    /**
     * @var SessionCart
     */
    private $sessionCart;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(SessionCart $sessionCart, ManagerRegistry $managerRegistry) {
        $this->sessionCart = $sessionCart;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function index() {

        $sessionCartItems = $this->sessionCart->getCartItems();

        $cartItems = array();

        if ($sessionCartItems) {
            foreach ($sessionCartItems as $sessionCartItem) {
                $productId = $sessionCartItem['productId'];
                $product = $this->managerRegistry->getRepository(Product::class)->find($productId);

                $cartItems[] = array(
                    'productId' => $sessionCartItem['productId'],
                    'productImage' => $product->getImage(),
                    'productName' => $product->getName(),
                    'productPrice' => $product->getPrice(),
                    'quantity' => $sessionCartItem['quantity'],
                    'total' => $sessionCartItem['total']
                );
            }
        }

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cartItemsCount' => is_array($sessionCartItems) ? count($sessionCartItems) : 0,
            'cartItems' => $cartItems,
            'cartSubtotal' => $this->sessionCart->getCartSubtotal(),
            'taxesTotal' => $this->sessionCart->getTotalTaxes(),
            'cartTotal' => $this->sessionCart->getCartTotal(),
            'cartItemsJson' => json_encode($cartItems)
        ]);
    }

    public function addItemForm($id): Response {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $form = $this->createForm(AddItemType::class, $id);

        return $this->render('cart/add_item_form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/cart/addItem/{productId}", name="cart_add_item", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addItem(Request $request) {
        $productId = $request->get('productId');
        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);
        $productName = $product->getName();

        $this->sessionCart->addItemToCart($productId, 1);

        $this->addFlash('success', "Product $productName added to cart");
        return $this->redirectToRoute('cart');
    }

    /**
     * @param Request $request
     * @Route("/cart/update", name="cart_update", methods={"POST"})
     * @return Response
     */
    public function updateCart(Request $request, Session $session) {
        $cartItemsJson = $request->request->get('cart-items-json');
        $session->set('cartItems', json_decode($cartItemsJson, true));

        return $this->redirectToRoute('cart');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("cart/removeItem/{productId}", name="cart_remove_item", methods={"POST"})
     */
    public function removeItem(Request $request) {
        $productId = $request->get('productId');
        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);
        $productName = $product->getName();

        $this->sessionCart->removeItemFromCart($productId);

        $this->addFlash('warning', "Item $productName has been removed from cart");

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/emptyCart", name="cart_empty_cart", methods={"POST"})
     * @return Response
     */
    public function emptyCart() {
        $this->sessionCart->emptyCart();

        $this->addFlash('warning', 'Cart has been emptied');

        return $this->redirectToRoute('cart');
    }
}

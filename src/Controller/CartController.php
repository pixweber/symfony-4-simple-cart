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

    public function __construct(SessionCart $sessionCart) {

        $this->sessionCart = $sessionCart;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function index() {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cartItemsCount' => $this->sessionCart->getCartItemsCount()
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
     * @Route("/cart/addItem/{id}", name="cart_add_item", methods={"POST"})
     * @param Product $product
     */
    public function addItem(Request $request, Session $session, SessionCart $sessionCart, ManagerRegistry $doctrine) {
        $productId = $request->get('id');

        $sessionCart->addItemToCart($productId, 1);

        return $this->redirectToRoute('home');
    }

    public function removeItem(Request $request, Session $session) {

    }


}

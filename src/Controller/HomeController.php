<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\SessionCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController {


    /**
     * @Route("/", name="home")
     * @param Session $session
     * @return
     */
    public function index(ProductRepository $productRepository, Session $session, SessionCart $sessionCart) {

        $products = $productRepository->findAll();

        return $this->render('home/index.html.twig', array(
            'products' => $products,
            'cartItems' => $session->get('cartItems'),
            'cartItemsCount' => $sessionCart->getCartItemsCount()
        ));
    }
}

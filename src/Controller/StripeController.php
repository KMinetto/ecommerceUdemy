<?php

namespace App\Controller;

use App\Classes\Cart;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commandes/create-session", name="stripe_create_session")
     */
    public function index(Cart $cart)
    {
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $productForStripe = [];

        foreach ($cart->getFull() as $product) {
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                    ],
                ],
                'quantity' => $product['quantity'],
            ];
        }

        //Api key
        Stripe::SetApiKey('sk_test_51IkX7MKhhCHPcauo0aarfTkTXQdyyvKXhdS7Ln5T1HL7bg7hwFND6cOm7Fu4HaFOToc0TZp5s4pzLFk7RVzFpBxA007mN2if9Z');


        //Checkout session
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                $productForStripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        return new JsonResponse(['id' => $checkout_session->id]);
    }
}

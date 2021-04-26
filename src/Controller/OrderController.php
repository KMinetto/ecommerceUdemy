<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    private EntityManagerInterface $entity;

    public function __construct(EntityManagerInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @Route("/commandes", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/commandes-recapitulatif", name="order-recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $carrier = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br>'.$delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br>'.$delivery->getCompany();
            }
            $delivery_content .= '<br>'.$delivery->getAdress();
            $delivery_content .= '<br>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br>'.$delivery->getCountry();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entity->persist($order);

            foreach ($cart->getFull() as $product) {
                $orderDetail = new OrderDetails();
                $orderDetail->setMyOrder($order);
                $orderDetail->setProduct($product['product']->getName());
                $orderDetail->setQuantity($product['quantity']);
                $orderDetail->setPrice($product['product']->getPrice());
                $orderDetail->setTotal($product['product']->getPrice() * $product['quantity']);

                $this->entity->persist($orderDetail);
            }

            $this->entity->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carrier,
                'delivery' => $delivery_content
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}

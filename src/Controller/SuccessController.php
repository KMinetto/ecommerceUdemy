<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuccessController extends AbstractController
{
    private EntityManagerInterface $entity;

    public function __construct(EntityManagerInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @Route("/commandes/success/{stripSessionId}", name="success")
     */
    public function index(Cart $cart, $stripSessionId): Response
    {
        $order = $this->entity->getRepository(Order::class)->findOneByStripSessionId($stripSessionId);

        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        //TODO: Modifier le statut isPaid de notre commande en mettant 1
        if (!$order->getIsPaid()) {
            //TODO: Vider la session "cart"
            $cart->remove();

            $order->setIsPaid(1);
            $this->entity->flush();
        }
        //TODO: Envoyer un e-mail pour confirmer sa commande
        //TODO: Afficher les infos de sa commande

        return $this->render('success/index.html.twig', [
            'order' => $order
        ]);
    }
}

<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Classes\Mail;
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
        $mail = new Mail();
        $content = "Bonjour ".$order->getUser()->getFirstname()."Merci pour votre commande<br><br>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda eveniet expedita illum ipsa laboriosam pariatur, perferendis quos repellendus, reprehenderit rerum sequi voluptatem. Atque dolore eaque explicabo nam, nostrum quod voluptates.";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande sur EcommerceUdemy est bien validée', $content);

        //TODO: Afficher les infos de sa commande

        return $this->render('success/index.html.twig', [
            'order' => $order
        ]);
    }
}

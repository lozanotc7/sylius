<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class NewsletterController extends abstractController
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function unsubscribeConfirmationAction(int $idCustomer, int $idNewsletter, Request $request)
    {
        $customer   = $this->em->getRepository('App:Customer\Customer')->find($idCustomer);
        $newsletter = $this->em->getRepository('App:Newsletter\Newsletter')->find($idNewsletter);

        $twig = [
            'customer' => $customer,
            'newsletter' => $newsletter
        ];

        return $this->render('Newsletter/unsubscribeMe.html.twig', $twig);
    }

    public function unsubscribeAction(int $idCustomer, int $idNewsletter, Request $request)
    {
        $customer   = $this->em->getRepository('App:Customer\Customer')->find($idCustomer);
        $newsletter = $this->em->getRepository('App:Newsletter\Newsletter')->find($idNewsletter);

        $customer->removeNewsletter($newsletter);
        $this->em->flush();

        return $this->render('Newsletter/unsubscribed.html.twig', ['customer_firstName' => $customer->getFirstName()]);
    }
}

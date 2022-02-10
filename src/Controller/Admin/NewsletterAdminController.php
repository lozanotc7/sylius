<?php

namespace App\Controller\Admin;

use App\Entity\Newsletter\Newsletter;
use App\Form\NewsletterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class NewsletterAdminController extends AbstractController
{
	/*
	 * i think this is not necessary
	 */
}

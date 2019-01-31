<?php

namespace App\ViewBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ViewController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('@View/index.html.twig');
    }

    /**
     * @Route("/login_page")
     */
    public function loginPage()
    {
        return $this->render('@View/login.html.twig');
    }

    /**
     * @Route("/register_page")
     */
    public function registerPage()
    {
        return $this->render('@View/reg.html.twig');
    }

    /**
     * @Route("/after_login_page")
     */
    public function afterLoginPage()
    {
        return $this->render('@View/logged.html.twig');
    }
}
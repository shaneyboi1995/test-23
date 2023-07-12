<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    #[Route('/', name: 'app_character_listing', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('character/index.html.twig', [
            'controller_name' => 'CharacterController',
        ]);
    }
}

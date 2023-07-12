<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    #[Route('/', name: 'app_character_listing', methods: ['GET'])]
    public function index(Request $request, ApiService $api): Response
    {

        $characters = $api->getCharacters();
        dd($characters);

        return $this->render('character/index.html.twig', [
            'controller_name' => 'CharacterController',
        ]);
    }
}

<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CharacterController extends AbstractController
{
    #[Route('/', name: 'app_character_listing', methods: ['GET'])]
    public function index(Request $request, ApiService $api): Response
    {
        $page = $request->query->get('page', 1);

        try {
            $characters = $api->getCharacters($page);
            return $this->render('character/index.html.twig', [
                'characters' => $characters['results'],
                'pagination' => $characters['info'],
                'currentPage' => $page
            ]);
        } catch (DecodingExceptionInterface|TransportExceptionInterface|\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
    }

    #[Route('/character/{id}', name: 'app_character_profile', methods: ['GET'])]
    public function singleCharacter(int $id, ApiService $api): Response
    {

        $character = $api->getCharacterProfile($id);
        $episodes = [];

        foreach($character['episode'] as $episode) {
            try {
                $episode = $api->getEpisodeData($episode);
            } catch (DecodingExceptionInterface|TransportExceptionInterface $e) {
                return $this->render('error.html.twig', [
                    'message' => $e->getMessage()
                ]);
            }
            try {
                $arrayIndex = $this->getSeasonsName($episode['episode']);
                $episodes[$arrayIndex][$episode['id']] = $episode;
            } catch (\Exception $e) {
                return $this->render('error.html.twig', [
                    'message' => $e->getMessage()
                ]);
            }

        }
        return $this->render('character/profile.html.twig', [
            'character' => $character,
            'seasons' => $episodes
        ]);
    }

    /**
     * @throws \Exception
     */
    private function getSeasonsName(string $episode): string
    {
        $index = null;
        if (str_contains($episode, "S01")){
            $index = 'Season 1';
        } elseif (str_contains($episode, "S02")){
            $index = 'Season 2';
        }elseif (str_contains($episode, "S03")){
            $index = 'Season 3';
        }elseif (str_contains($episode, "S04")){
            $index = 'Season 4';
        }elseif (str_contains($episode, "S05")){
            $index = 'Season 5';
        } else {
            throw new \Exception('The episode\'s season could not be established');
        }
        return $index;
    }
}

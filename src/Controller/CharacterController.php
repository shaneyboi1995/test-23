<?php

namespace App\Controller;

use App\Form\FilterFormType;
use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CharacterController extends AbstractController
{
    /**
     * @param Request $request
     * @param ApiService $api
     * @return Response
     */
    #[Route('/', name: 'app_character_listing', methods: ['GET', 'POST'])]
    public function index(Request $request, ApiService $api): Response
    {
        $filterForm = $this->createForm(FilterFormType::class);
        $filterForm->handleRequest($request);
        // lets see if any filters were set
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $formData = $filterForm->getData();

            // need to reset the page otherwise get an error if you deep in to the pagination link but then select a filter
            $requestOptions = [
                'page' => 1
            ];

            if ($formData['name']) {
                $requestOptions['name'] = $formData['name'];
            }
            if ($formData['status']) {
                $requestOptions['status'] = $formData['status'];
            }

        }else {
            // the filter/search data is coming from the pagination link
            $requestOptions = [
                'page' => $request->query->get('page', 1)
            ];
            if ($request->query->get('name')) {
                $requestOptions['name'] = $request->query->get('name');
            }
            if ($request->query->get('status')) {
                $requestOptions['status'] = $request->query->get('status');
            }

        }
        $currentPage = (int)$requestOptions['page'];
        try {
            // get the character results
            $characters = $api->getCharacters($requestOptions);
            return $this->render('character/index.html.twig', [
                'characters' => $characters['results'],
                'totalPages' => (int)$characters['info']['pages'],
                'currentPage' => $currentPage,
                'filterForm' => $filterForm,
                'name' => (array_key_exists('name', $requestOptions)) ? $requestOptions['name'] : null,
                'status' => (array_key_exists('status', $requestOptions)) ? $requestOptions['status'] : null
            ]);
        } catch (DecodingExceptionInterface|TransportExceptionInterface|\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param int $id
     * @param ApiService $api
     * @return Response
     */
    #[Route('/character/{id}', name: 'app_character_profile', methods: ['GET'])]
    public function singleCharacter(int $id, ApiService $api): Response
    {

        try {
            $character = $api->getCharacterProfile($id);
            $episodes = [];

            foreach($character['episode'] as $episode) {
                try {
                    // get the single episode
                    $episode = $api->getEpisodeData($episode);
                } catch (DecodingExceptionInterface|TransportExceptionInterface|\Exception $e) {
                    return $this->render('error.html.twig', [
                        'message' => $e->getMessage()
                    ]);
                }
                try {
                    // grouping the episode by their season for a better ui
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
        } catch (DecodingExceptionInterface|TransportExceptionInterface $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param string $episode
     * @return string
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

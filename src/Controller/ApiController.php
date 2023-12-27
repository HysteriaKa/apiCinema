<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ApiController extends AbstractController
{
    private $client;
	private $em;
	private $params;

	public function __construct(HttpClientInterface $client, EntityManagerInterface $em, ParameterBagInterface $params)
    {
        $this->client = $client;
		$this->em = $em;
		$this->params = $params;
    }
	public function __invoke(Movie $movie): Movie
    {

        if ($movie->getPictureUrl() === null) {
            $q = $movie->getTitle();
			$rapidApiKey = $this->params->get('rapidapi_key');			
            $options = [$rapidApiKey];
            $response = $this->client->request('GET', 'https://imdb8.p.rapidapi.com/auto-complete?q=$q', [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-RapidAPI-Host' => 'imdb8.p.rapidapi.com',
                    'X-RapidAPI-Key' => $rapidApiKey,
                ],
            ]);
			if ($response->getStatusCode() === 200){       
				$result = $response->toArray();
				$movie->setPictureUrl($result['d'][0]['i']['imageUrl']);
				$this->em->flush();
				
			}
			return $movie;
     
        } else {
            return $movie;
        }

    }
}

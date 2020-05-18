<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/list", name="movies-list", methods={"GET"})
     */
    public function list()
    {
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();
        
        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
        ]);
    }

     /**
     * @Route("/{id}/view", requirements={"id" = "\d+"}, name="movie_view", methods={"GET"})
     */
    public function view(Movie $movie)
    {
        return $this->render('movie/view.html.twig', [
            'movie' => $movie,
        ]);
    }


}

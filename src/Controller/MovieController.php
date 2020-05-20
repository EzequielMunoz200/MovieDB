<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/list", name="movie_list", methods={"GET"})
     */
    public function list(Request $request)
    {
        $search = $request->query->get("search", "");

        $movies = $this->getDoctrine()->getRepository(Movie::class)->findByPartialTitle($search);
        
        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
            'search' => $search
        ]);
    }

     /**
     * @Route("/{id}/view", requirements={"id" = "\d+"}, name="movie_view", methods={"GET"})
     */
    public function view($id)
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->findWithFullData($id);

        if(!$movie){
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        return $this->render('movie/view.html.twig', [
            'movie' => $movie,
        ]);
    }


}

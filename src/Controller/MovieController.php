<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
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

        if (!$movie) {
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        return $this->render('movie/view.html.twig', [
            'pageTitle' => ' · ' . $movie->getTitle(),
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/add", name="movie_add", methods={"GET", "POST"})
     */
    public function add(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($movie);
            $manager->flush();

            $this->addFlash("success", "Le film a été ajoutée");

            return $this->redirectToRoute('movie_view', ['id' => $movie->getId()]);
        }

        return $this->render('movie/add.html.twig', [
            "pageTitle" => " · Ajouter un film",
            "form" => $form->createView(),
            "movie" => $movie,

        ]);
    }

    /**
     * @Route("/{id}/update", requirements={"id" = "\d+"}, name="movie_update", methods={"GET", "POST"})
     */
    public function update(Movie $movie, Request $request)
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "Le film a été mis a jour");

            return $this->redirectToRoute('movie_view', ['id' => $movie->getId()]);
        }


        return $this->render('movie/update.html.twig', [
            "pageTitle" => ' · ' . $movie->getTitle(),
            "form" => $form->createView(),
            "movie" => $movie,
        ]);
    }



    /**
     * @Route("/add/poster", name="add_poster", methods={"GET"})
     */
    public function addPoster()
    {
        //TODO

        return $this->render('movie/add_poster.html.twig', [
            'pageTitle' => " · Ajouter un poster"
        ]);
    }
}

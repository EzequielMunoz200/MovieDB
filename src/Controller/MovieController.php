<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\MovieActor;
use App\Form\MovieActorType;
use App\Form\MovieType;
use App\Service\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/movie", name="movie_")
 */
class MovieController extends AbstractController
{
    //On aurait pu utiliser le slugger avec une injection de dépendances
    //cette technique peut remplacer l'usage des paramètres dans les méthodes
   /*  private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    } */

    /**
     * @Route("/list", name="list", methods={"GET"})
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
     * @Route("/{id}/view", requirements={"id" = "\d+"}, name="view", methods={"GET"})
     */
    public function view($id, Slugger $slugger)
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->findWithFullData($id);

        if (!$movie) {
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        //dd($slugger->slugify($movie->getTitle()));

        return $this->render('movie/view.html.twig', [
            'pageTitle' => ' · ' . $movie->getTitle(),
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request, Slugger $slugger)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //on ajoute le slug du $movie à partir de son titre
            $slug = $slugger->slugify($movie->getTitle());
            $movie->setSlug($slug);

            /**@var UploadFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $filename = uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('movie_image_directory'),
                    $filename
                );
                $movie->setImageFilename($filename);
            }

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
     * @Route("/{id}/update", requirements={"id" = "\d+"}, name="update", methods={"GET", "POST"})
     */
    public function update(Movie $movie, Request $request, Slugger $slugger)
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //on recalcule le slug au cas où il a été modifié
            $slug = $slugger->slugify($movie->getTitle());
            $movie->setSlug($slug);

            /**@var UploadFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $filename = uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('movie_image_directory'),
                    $filename
                );
                $movie->setImageFilename($filename);
            }


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
     * @Route("/{id}/actor/add", name="actor_add", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function addMovieActor(Movie $movie, Request $request)
    {
        $movieActor = new MovieActor();
        $movieActor->setMovie($movie);

        $form = $this->createForm(MovieActorType::class, $movieActor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($movieActor);
            $manager->flush();

            $this->addFlash('success', $movieActor->getPerson() . 'a été ajouté');
            return $this->redirectToRoute('movie_view', ['id' => $movie->getId()]);
        }

        return $this->render('movie/add_actor.html.twig', [
            'pageTitle' => ' · Ajouter un acteur',
            'form' => $form->createView(),
            'movie' => $movie
        ]);
    }
}

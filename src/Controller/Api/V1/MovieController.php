<?php

namespace App\Controller\Api\V1;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * @Route("/api/v1/movies", name="api_v1_movies_")
 */
class MovieController extends AbstractController
{

    private $errorsString;

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(MovieRepository $movieRepository, ObjectNormalizer $objetNormalizer)
    {
        $movies = $movieRepository->findAll();

        $serializer = new Serializer([$objetNormalizer]);

        $json = $serializer->normalize($movies, null, ['groups' => 'api_v1_movies']);

        return $this->json($json);
    }


    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Movie $movie, ObjectNormalizer $objetNormalizer)
    {
        $serializer = new Serializer([$objetNormalizer]);

        $json = $serializer->normalize($movie, null, ['groups' => 'api_v1_movies']);

        //dd($json);

        return $this->json($json);
    }

    /**
     * @Route("", name="new", methods={"POST"})
     */
    public function new(Request $request, ObjectNormalizer $objetNormalizer)
    {
        //$content = $request->getContent();
        //$json = json_decode($content)
        //Ajouter des movies depuis le json (sans form)
        //$movie = new Movie();
        //$movie->setTitle($json->title);
        //$movie->setReleaseDate(new \DateTime($json->release_date));

        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie, ['csrf_protection' => false]);

        //le true c'est pour indiquer que on veut un array et pas un objet
        $json = json_decode($request->getContent(), true);

        $form->submit($json);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $serializer = new Serializer([$objetNormalizer]);
            $movieJson = $serializer->normalize($movie, null, ['groups' => 'api_v1_movies']);

            return $this->json($movieJson, 201);
        } else {
            //si le form  n'est pas valide , on renvoi les erreurs
            $errors = [];
            $err = $form->getErrors(true, true);

            for ($i = 0; $i < count($err); $i++) {
                $errors[] = [
                    'field' => $err[$i]->getOrigin()->getName(),
                    'message' => $err[$i]->getMessageTemplate()
                ];
            }

            return $this->json($errors, 400);
        }
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     */
    public function update()
    {
        return $this->json([
            'message' => 'Welcome to your new controller! get',
            'path' => 'src/Controller/Api/V1/MovieController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete()
    {
        return $this->json([
            'message' => 'Welcome to your new controller! delete',
            'path' => 'src/Controller/Api/V1/MovieController.php',
        ]);
    }
}

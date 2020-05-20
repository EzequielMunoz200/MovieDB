<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/person")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/{id}/view", requirements={"id" = "\d+"}, name="person_view", methods={"GET"})
     */
    public function view($id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findWithFullData($id);

        if(!$person){
            throw $this->createNotFoundException("Cette personne n'existe pas !");
        }

        return $this->render('person/view.html.twig', [
            'person' => $person,
        ]);
    }
}

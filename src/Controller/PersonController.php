<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/person", name="person_")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/{id}/view", requirements={"id" = "\d+"}, name="view", methods={"GET"})
     */
    public function view($id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findWithFullData($id);

        if (!$person) {
            throw $this->createNotFoundException("Cette personne n'existe pas !");
        }

        return $this->render('person/view.html.twig', [
            'pageTitle' => ' · ' . $person->getName(),
            'person' => $person,
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request)
    {

        $newPerson = new Person();

        /*  $builder = $this->createFormBuilder($newCategory);
        $builder->add("name", TextType::class);
        $builder->add("submit", SubmitType::class, ["label" => "Valider"]);
        $form = $builder->getForm(); */

        $form = $this->createForm(PersonType::class, $newPerson);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$data = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($newPerson);
            $manager->flush();

            $this->addFlash("success", "La personne a été ajoutée");

            return $this->redirectToRoute('person_view', ['id' => $newPerson->getId()]);
        }


        return $this->render(
            'person/add.html.twig',
            [
                'pageTitle' => " · Ajouter une personne",
                "form" => $form->createView()
            ]
        );
    }


    /**
     * @Route("/{id}/update", requirements={"id" = "\d+"}, name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, Person $person)
    {
        /*  $builder = $this->createFormBuilder($newCategory);
        $builder->add("name", TextType::class);
        $builder->add("submit", SubmitType::class, ["label" => "Valider"]);
        $form = $builder->getForm(); */

        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "Les données ont été mise à jour");

            return $this->redirectToRoute('person_view', ['id' => $person->getId()]);
        }

        return $this->render(
            'person/update.html.twig',
            [
                'pageTitle' => ' · MAJ ' . $person->getName(),
                'form' => $form->createView(),
                'person' => $person
            ]
        );
    }


    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Person $person): Response
    {
        if (!$person->getDirectedMovies()->isEmpty()) {
            $this->addFlash("warning", "Impossible de supprimer le directeur d'un film existent");
            return $this->redirectToRoute('person_update', ['id' => $person->getId()]);
        }

        if ($this->isCsrfTokenValid('delete' . $person->getId(), $request->request->get('_token'))) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($person);
            $manager->flush();

            $this->addFlash("info", $person->getName() . " a été supprimé(e)");
        }

        return $this->redirectToRoute('category_list', ['id' => $person->getId()]);
    }
}

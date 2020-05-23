<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(array(), ['name' => 'ASC']);
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}/view", requirements={"id" = "\d+"}, name="view", methods={"GET", "POST"})
     */
    public function view(Request $request, $id)
    {

        $category = $this->getDoctrine()->getRepository(Category::class)->findWithFullData($id);

        if (!$category) {
            throw $this->createNotFoundException('Cette catégorie n\'existe pas !');
        }

        return $this->render('category/view.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request)
    {

        $newCategory = new Category();

        $form = $this->createForm(CategoryType::class, $newCategory);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$data = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($newCategory);
            $manager->flush();

            $this->addFlash('success', 'La catégorie a été ajoutée');

            return $this->redirectToRoute('category_list');
        }


        return $this->render('category/add.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/{id}/update", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, Category $category)
    {
        //$category = new Category();
        /*  $builder = $this->createFormBuilder($newCategory);
        $builder->add("name", TextType::class);
        $builder->add("submit", SubmitType::class, ["label" => "Valider"]);
        $form = $builder->getForm(); */

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$data = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            //$manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'La catégorie a été mise à jour');

            return $this->redirectToRoute('category_view', ['id' => $category->getId()]);
        }

        return $this->render('category/update.html.twig', [
            'pageTitle' => ' · Mise à jour de la catégorie: ' . $category->getName(),
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($category);
            $manager->flush();

            $this->addFlash('success', 'La catégorie a été supprimée');
        }

        return $this->redirectToRoute('category_list');
    }
}

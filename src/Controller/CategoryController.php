<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/category")
*/
class CategoryController extends AbstractController
{
    /**
     * @Route("/list", name="category-list", methods={"GET"})
     */
    public function list()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}/view", requirements={"id" = "\d+"}, name="category-view", methods={"GET"})
     */
    public function view(Category $category)
    {

        return $this->render('category/view.html.twig', [
            'category' => $category,
        ]);
    }

}

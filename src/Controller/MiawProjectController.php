<?php

namespace App\Controller;


use App\Form\LivreFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Livre;
use App\Entity\Categories;

class MiawProjectController extends AbstractController
{
    /**
     *  @Route("/new_livre/", name = "new_livre")
     */
    public function NewLivre(Request $request)
    {
        $livre = new Livre();

        $formBuilder = $this->get('form.factory')->createBuilder(LivreFormType::class, $livre);

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {
                $livre = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($livre);
                $entityManager->flush();

                echo 'Nouveau livre enregistré';
            } else {
                echo "Erreur !";
            }
        }
        return $this->render('miaw_project/form-livre.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *  @Route("/livres/list", name="list_livres")
     */
    public function listLivre()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $livres = $entityManager->getRepository(Livre::class)->findAll();

        return $this->render('miaw_project/liste-livres.html.twig', ['livres' => $livres]);
    }
    /**
     * @Route("/livre/show/{uid}", name="show_livre")
     */
    public function showLivre($uid)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $livre = $entityManager->getRepository(Livre::class)->find($uid);

        return $this->render('miaw_project/show-livre.html.twig', ['livre' => $livre]);
    }

    /**
     *  @Route("/add_categories/{nom}", name = "add_categories")
     */
    public function addCategories($nom): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $maCategories = new Categories();
        $maCategories->setNom($nom);
        $entityManager->persist($maCategories);
        $entityManager->flush();

        return new Response('nouvelle catégorie créeé : ' . $maCategories->getId());
    }
}

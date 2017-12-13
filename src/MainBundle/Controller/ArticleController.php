<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ArticleController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('MainBundle:Article')->findAll();

        return $this->render('MainBundle:Article:index.html.twig', array(
            'articles' => $articles
        ));
    }

    public function addAction(Request $request)
    {
        $article = new Article();
        
        $form = $this->get('form.factory')->createBuilder('form', $article)
            ->add('title',     'text')
            ->add('content',   'textarea')
            ->add('save',      'submit')
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($article);
            $em->flush();

            return new RedirectResponse($this->generateUrl('main_article'));
        }

        return $this->render('MainBundle:Article:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $articleId)
    {
        $em = $this->getDoctrine()->getManager();

        // var_dump($articleId);

        $article = $em->getRepository('MainBundle:Article')->findOneById($articleId);

        $form = $this->get('form.factory')->createBuilder('form', $article)
            ->add('title',     'text')
            ->add('content',   'textarea')
            ->add('save',      'submit')
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($article);
            $em->flush();

            return new RedirectResponse($this->generateUrl('main_article'));
        }

        return $this->render('MainBundle:Article:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction(Request $request, $articleId)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('MainBundle:Article')->findOneById($articleId);

        $em->remove($article);
        $em->flush();

        return new RedirectResponse($this->generateUrl('main_article'));
    }
}

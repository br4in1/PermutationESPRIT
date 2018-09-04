<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Covoiturage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Covoiturage controller.
 *
 */
class CovoiturageController extends Controller
{
    /**
     * Lists all covoiturage entities.
     *
     */
    public function indexAction(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        if($request->get('lieu') === null){
            $em = $this->getDoctrine()->getManager();

            $covoiturages = $em->getRepository('AppBundle:Covoiturage')->findAll();
            $covoiturages = $paginator->paginate(
                $covoiturages,
                $request->get('page',1),
                8
            );

            return $this->render('@App/covoiturage/index.html.twig', array(
                'covoiturages' => $covoiturages,
            ));
        }
        else{
            $em = $this->getDoctrine()->getManager();

            $covoiturages = $em->getRepository('AppBundle:Covoiturage')->findByCity($request->get('lieu'));
            $covoiturages = $paginator->paginate(
                $covoiturages,
                $request->get('page',1),
                8
            );

            return $this->render('@App/covoiturage/index.html.twig', array(
                'covoiturages' => $covoiturages,
            ));
        }
    }

    /**
     * Creates a new covoiturage entity.
     *
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $covoiturage = new Covoiturage();



        $date=new \DateTime($date=$request->get('heure'));
        $date->setDate((int)substr($request->get('date'),6,4),(int)substr($request->get('date'),0,2),(int)substr($request->get('date'),3,2));

        $covoiturage->setDate($date);
        $covoiturage->setDatePublication(new \DateTime());

        if ($request->get('type') == "Quotidien")
            $covoiturage->setType(1);
        else
            $covoiturage->setType(0);

        if ($request->get('fumeur') == "Fumeur")
            $covoiturage->setFumeur(1);
        else
            $covoiturage->setFumeur(0);

        $covoiturage->setDepart($request->get('depart'));
        $covoiturage->setDestination($request->get('destination'));
        $covoiturage->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($covoiturage);
        $em->flush();
    return $this->redirectToRoute('covoiturage_index');
    }

    /**
     * Finds and displays a covoiturage entity.
     *
     */
    public function showAction(Covoiturage $covoiturage)
    {
        $deleteForm = $this->createDeleteForm($covoiturage);

        return $this->render('@App/covoiturage/show.html.twig', array(
            'covoiturage' => $covoiturage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing covoiturage entity.
     *
     */
    public function editAction(Request $request, Covoiturage $covoiturage)
    {
        $deleteForm = $this->createDeleteForm($covoiturage);
        $editForm = $this->createForm('AppBundle\Form\CovoiturageType', $covoiturage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('covoiturage_edit', array('id' => $covoiturage->getId()));
        }

        return $this->render('@App/covoiturage/edit.html.twig', array(
            'covoiturage' => $covoiturage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a covoiturage entity.
     *
     */
    public function deleteAction(Request $request, Covoiturage $covoiturage)
    {
        $form = $this->createDeleteForm($covoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($covoiturage);
            $em->flush();
        }

        return $this->redirectToRoute('covoiturage_index');
    }

    /**
     * Creates a form to delete a covoiturage entity.
     *
     * @param Covoiturage $covoiturage The covoiturage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Covoiturage $covoiturage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('covoiturage_delete', array('id' => $covoiturage->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Permutation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Permutation controller.
 *
 */
class PermutationController extends Controller
{
    /**
     * Lists all permutation entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $permutations = $em->getRepository('AppBundle:Permutation')->findAll();

        $permutation = new Permutation();
        $form = $this->createForm('AppBundle\Form\PermutationType', $permutation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $permutation->setDate(new \DateTime());
            $permutation->setState(1);
            $permutation->setUser(1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($permutation);
            $em->flush();

            return $this->redirectToRoute('permutation_index', array('id' => $permutation->getId()));
        }

        return $this->render('@App/permutation/index.html.twig', array(
            'permutations' => $permutations,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a permutation entity.
     *
     */
    public function showAction(Permutation $permutation)
    {
        $deleteForm = $this->createDeleteForm($permutation);

        return $this->render('@App/permutation/show.html.twig', array(
            'permutation' => $permutation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing permutation entity.
     *
     */
    public function editAction(Request $request, Permutation $permutation)
    {
        $deleteForm = $this->createDeleteForm($permutation);
        $editForm = $this->createForm('AppBundle\Form\PermutationType', $permutation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('permutation_edit', array('id' => $permutation->getId()));
        }

        return $this->render('@App/permutation/edit.html.twig', array(
            'permutation' => $permutation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a permutation entity.
     *
     */
    public function deleteAction(Request $request, Permutation $permutation)
    {
        $form = $this->createDeleteForm($permutation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($permutation);
            $em->flush();
        }

        return $this->redirectToRoute('permutation_index');
    }

    /**
     * Creates a form to delete a permutation entity.
     *
     * @param Permutation $permutation The permutation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Permutation $permutation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('permutation_delete', array('id' => $permutation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

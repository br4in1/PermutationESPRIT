<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if($this->isGranted("ROLE_USER")) return $this->redirectToRoute("index");
        $user = $this->get('fos_user.user_manager')->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->get('event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->get('fos_user.registration.form.factory')->createForm();
        $form->setData($user);
        $user->setUsername($user->getEmail());
        $user->setUsernameCanonical($user->getUsername());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid() && substr($user->getEmail(), -strlen("@esprit.tn")) === "@esprit.tn") {
                $event = new FormEvent($form, $request);
                $this->get('event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $this->get('fos_user.user_manager')->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $this->get('event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $this->get('event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        //return $this->render('@FOSUser/Registration/register.html.twig', array('form' => $form->createView(),));
        return $this->render('@FOSUser/Registration/register_content.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

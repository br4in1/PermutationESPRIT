<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Colocation;
use AppBundle\Entity\Colocation_Amenity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use \Sightengine\SightengineClient;

class ColocationController extends Controller
{
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $c = $em->getRepository('AppBundle:Colocation')->findOneBy(array('id' => $id));
        if($c === null || $c->getUser()->getEmail() !== $this->getUser()->getEmail()) return $this->render('@App/default/404.html.twig');
        else{
            $em->remove($c);
            $em->flush();
            return $this->redirectToRoute('colocations_index');
        }
    }
    
    public function disableAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $coloc = $em->getRepository('AppBundle:Colocation')->findOneBy(array('id' => $id));
        if($coloc === null || $coloc->getUser()->getEmail() !== $this->getUser()->getEmail()) return $this->render('@App/default/404.html.twig');
        else {
            $coloc->setAvailable(false);
            $em->persist($coloc);
            $em->flush();
            return $this->redirectToRoute('colocations_single',array('id' => $coloc->getId()));
        }
    }

    public function enableAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $coloc = $em->getRepository('AppBundle:Colocation')->findOneBy(array('id' => $id));
        if($coloc === null || $coloc->getUser()->getEmail() !== $this->getUser()->getEmail()) return $this->render('@App/default/404.html.twig');
        else {
            $coloc->setAvailable(true);
            $em->persist($coloc);
            $em->flush();
            return $this->redirectToRoute('colocations_single',array('id' => $coloc->getId()));
        }
    }

    public function singleAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $coloc = $em->getRepository('AppBundle:Colocation')->findOneBy(array('id' => $id));
        if($coloc === null) return $this->render('@App/default/404.html.twig');
        else {
            $amenities = $em->getRepository('AppBundle:Colocation_Amenity')->findBy(array('colocation' => $coloc));
            return $this->render('@App/colocations/single.html.twig',array(
                'c' => $coloc,
                'amenities' => $amenities
            ));
        }
    }

    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()){
           $colocs = $em->getRepository('AppBundle:Colocation')->findByCity($request->get('city'));
            $amenities = [];
           foreach ($colocs as $c){
               $amenities[$c->getId()] = $em->getRepository('AppBundle:Colocation_Amenity')->findBy(array('colocation' => $c));
           }
            return new JsonResponse(
                array(
                    'html' => $this->renderView('@App/colocations/index_content.html.twig',
                        ['colocs' => $colocs,'amenities' => $amenities]
                    )
                )
            );
        }
        else{
            $colocs = $em->getRepository('AppBundle:Colocation')->findAll();
            $amenities = [];
            foreach ($colocs as $c){
                $amenities[$c->getId()] = $em->getRepository('AppBundle:Colocation_Amenity')->findBy(array('colocation' => $c));
            }
            return $this->render('@App/colocations/index.html.twig',
                array('colocs' => $colocs,
                    'amenities' => $amenities
                ));
        }
    }

    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $amenities = $em->getRepository('AppBundle:Amenity')->findAll();
        return $this->render('@App/colocations/new.html.twig',array(
            'amenities' => $amenities
        ));
    }

    public function uploadPicturesAction(Request $request)
    {
        $client = new SightengineClient('1093368', 'gpP6JhRwskt4SoJuWwov');
        $files = $request->files->get("file");
        $response = [];
        foreach ($files as $file){
            $output = $client->check(['nudity'])->set_file($file->getRealPath());
            $output = json_decode(json_encode($output,true),true);
            if($output["nudity"]["safe"] > 0.5){
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $file->move(
                    '../assets/images/colocs_pics',
                    $fileName
                );
                $response[] = $fileName;
            }
        }
        return new JsonResponse(json_encode($response));
    }

    public function addAction(Request $request){
        $coloc = new Colocation();
        $coloc->setUser($this->getUser());
        $coloc->setAdded(new \DateTime("now"));
        $coloc->setTitle($request->get('title'));
        $coloc->setCity($request->get('city'));
        $coloc->setCost($request->get('prix'));
        $coloc->setAvailable(true);
        $coloc->setNbpersonnes($request->get('nbper'));
        $coloc->setDescription($request->get('description'));
        $pics = explode(',',$request->get('pics'));
        if(count($pics)>=1)$coloc->setPicture1($pics[0]);
        if(count($pics)>=2)$coloc->setPicture2($pics[1]);
        if(count($pics)>=3)$coloc->setPicture3($pics[2]);
        if(count($pics)>=4)$coloc->setPicture4($pics[3]);
        if(count($pics)>=5)$coloc->setPicture5($pics[4]);
        if(count($pics)>=6)$coloc->setPicture6($pics[5]);
        $em = $this->getDoctrine()->getManager();
        $am = $em->getRepository('AppBundle:Amenity')->findAll();
        $em->persist($coloc);
        $em->flush();
        foreach($am as $_am){
            $a_c = new Colocation_Amenity();
            $a_c->setColocation($coloc);
            $a_c->setAmenity($_am);
            if($request->get("check-".$_am->getId()) === "true")
                $a_c->setValid(true);
            else $a_c->setValid(false);
            $em->persist($a_c);
        }
        $em->flush();
        return new JsonResponse(array('success' => true));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}

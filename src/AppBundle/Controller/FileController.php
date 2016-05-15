<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FileEntity;
use AppBundle\Form\FileEntityType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FileController extends Controller
{
    /**
     * @Route("/files/new", name="filesnew")
     */
    public function  filesnewAction(Request $request)
    {
        $fileEntity = new FileEntity();

        $form = $this->createForm(FileEntityType::class, $fileEntity);

        // tester si le formulaire est déjà rempli
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $path = $fileEntity->getPath();

            $fileEntity->setOriginalname($path->getClientOriginalName());

            $fileName = md5(uniqid()).'.'.$path->guessExtension();

            $filesDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/files';
            $path->move($filesDir, $fileName);

            $fileEntity->setPath($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($fileEntity);
            $em->flush();

            return $this->redirect($this->generateUrl('fileslist'));
        }

        // afficher le formulaire s'il n'est pas déjà rempli
        return $this->render('files/new.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/files/list", name="fileslist")
     */
    public function fileslistAction(Request $request){
        $files = $this->getDoctrine()->getRepository("AppBundle:FileEntity")->findAll();
        return $this->render('files/fileslist.html.twig', array('files' => $files));
    }

    /**
     * @Route("/files/delete", name="filesdelete")
     */
    public function deleteFileAction(Request $req){

        if($req->isXmlHttpRequest()) {
            $filesDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/files/';
            unlink($filesDir.$req->get("file"));

            $em = $this->getDoctrine()->getEntityManager();
            $file = $this->getDoctrine()->getRepository("AppBundle:FileEntity")->findOneByPath($req->get("file"));
            $em->remove($file);
            $em->flush();

            return new JsonResponse(array('data' => 0));
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax",400);
    }

}

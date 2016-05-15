<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Formation;
use AppBundle\Entity\Role;

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

class FormationController extends Controller
{
    /**
     * @Route("/formations/newformation", name="newformation")
     */
    public function newformationAction(Request $request)
    {
        $formation = new Formation();

        $form = $this->createFormBuilder($formation)
        ->add('name', TextType::class, array('label' => 'Name of the formation'))
        ->add('contents', TextareaType::class, array('label' => 'Contents', 'attr' => array('rows' => '11')))
        ->add('date', DateType::class, array('label' => 'Date'))
        ->add('cost', IntegerType::class, array('label' => 'Cost'))
        ->add('duration', IntegerType::class, array('label' => 'Duration in days'))
        ->add('prerequisites', TextareaType::class, array('label' => 'Prerequisites', 'attr' => array('rows' => '5')))
        ->add('submit', SubmitType::class, array('label' => 'Create formation'))
        ->getForm();

        // tester si le formulaire est déjà rempli
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('formations');
            //return $this->redirectToRoute('created', array('url' => $url));
        }

        // afficher le formulaire s'il n'est pas déjà rempli
        return $this->render('formations/newformation.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/formations", name="formations")
     */
    public function formationsAction(Request $request){
        $formations = $this->getDoctrine()->getRepository("AppBundle:Formation")->findAll();
        return $this->render("formations/formations.html.twig", ['formations' => $formations]);
    }


    /**
     * @Route("/formation/{id}",name="formation", defaults={"id" = 0})
     */
    public function formationAction($id, Request $request){
        $formation = $this->getDoctrine()->getRepository('AppBundle:Formation')->findOneById($id);

        if(!$formation){
            throw $this->createNotFoundException("No formation with this id : ".$id);
        }

        return $this->render("formations/formation.html.twig",['formation' => $formation]);
    }

    /**
     * @Route("/formationapplicants",name="formationapplicants")
     */

    public function GetApplicantsAction(Request $req){
        if($req->isXmlHttpRequest()) {

            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));

            $applicants = $formation->getApplicants();

            foreach ($applicants as $a){
                if($a->getId()==$this->getUser()->getId()){
                    return new JsonResponse(array('data' => 1));
                }
            }
            return new JsonResponse(array('data' => 0));
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax",400);
    }

    /**
     * @Route("/formations/joinformation",name="joinformation")
     */
    public function joinformationAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));
            $formation->addApplicant($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
            return new Response("Ok");
        }else{
            return new Response("Erreur : Ce n'est pas une requête Ajax",400);
        }
    }

    /**
     * @Route("/formations/addapplicant",name="formationaddapplicant")
     */
    public function addApplicantAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneById($req->get('iduser'));
            $formation->addApplicant($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
            return new Response("Ok");
        }else{
            return new Response("Erreur : Ce n'est pas une requête Ajax",400);
        }
    }

    /**
     * @Route("/formations/rmapplicant",name="formationrmapplicant")
     */
    public function rmApplicantAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneById($req->get('iduser'));
            $formation->removeApplicant($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
            return new Response("Ok");
        }else{
            return new Response("Erreur : Ce n'est pas une requête Ajax",400);
        }
    }

    /**
     * @Route("/formations/addintraining",name="formationaddintraining")
     */
    public function addIntrainingAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneById($req->get('iduser'));
            $formation->addIntraining($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
            return new Response("Ok");
        }else{
            return new Response("Erreur : Ce n'est pas une requête Ajax",400);
        }
    }

    /**
     * @Route("/formations/rmintraining",name="formationrmintraining")
     */
    public function rmIntrainingAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneById($req->get('iduser'));
            $formation->removeIntraining($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
            return new Response("Ok");
        }else{
            return new Response("Erreur : Ce n'est pas une requête Ajax",400);
        }
    }

    /**
     * @Route("/formations/validation", name="validationformations")
     */
    public function validationformationsAction(Request $request){
        $formations = $this->getDoctrine()->getRepository("AppBundle:Formation")->findByApplicantsSuperiorId($this->getUser()->getId());



        return $this->render("formations/validationformations.html.twig", ['formations' => $formations]);
    }

    /**
     * @Route("/myJoursformation",name="myjoursformation")
     */
    public function myJoursformationAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $user = $this->getUser();
            $jours = $user->getJoursformations();
            return new JsonResponse(array('data' => $jours));
        }else{
            return new Response("Erreur : Ce n'est pas une requête Ajax",400);
        }
    }

    /**
     * @Route("/myBudgetformation",name="mybudgetformation")
     */
    public function myBudgetformationAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $user = $this->getUser();
            $budget = $user->getBudgetformations();
            return new JsonResponse(array('data' => $budget));
        }else{
            return new Response("Erreur : Ce n'est pas une requête Ajax",400);
        }
    }

    /**
     * @Route("/acceptApplicantFormation",name="acceptapplicantformation")
     */
    public function acceptApplicantFormationAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $user = $this->getUser();
            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));
            $applicant = $this->getDoctrine()->getRepository("AppBundle:User")->findOneById($req->get('iduser'));

            $budget = $user->getBudgetformations();
            $cost = $formation->getCost();
            if(($budget-$cost)<0){
                return new JsonResponse(array('error' => 1));
            }

            $jours = $applicant->getJoursformations();
            $duration = $formation->getDuration();
            if(($jours-$duration)<0){
                return new JsonResponse(array('error' => 2));
            }

            $user->setBudgetformations($budget-$cost);
            $applicant->setJoursformations($jours-$duration);
            $formation->removeApplicant($applicant);
            $formation->addIntraining($applicant);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($applicant);
            $em->persist($formation);
            $em->flush();

            return new JsonResponse(array('error' => 0));
        }else{
            return new Response("Erreur : Ce n'est pas une requête Ajax",400);
        }
    }




}

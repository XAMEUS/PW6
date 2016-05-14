<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Message;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/chat")
 */
class ChatController extends Controller
{

    /**
     * @Route("/{channel}", name="chat_show", defaults={"channel" = "default"})
     */
    public function showAction($channel)
    {
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findBy(
            array('channel' => $channel),
            array('id' => 'desc'),
            10
        );

        return $this->render('chatshow.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'updateInterval' => 500,
            'channel' => $channel,
            'messages' => $messages
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @Route("/post/{channel}", name="chat_post", defaults={"channel" = "default"})
     */
    public function postAction(Request $request, $channel)
    {
        $message = new Message();
        $message->setAuthor($this->getUser());
        $message->setChannel($channel);
        $message->setMessage($request->get('message'));
        $message->setInsertDate(new \DateTime());
        $this->getDoctrine()->getManager()->persist($message);
        $this->getDoctrine()->getManager()->flush();

        return new Response('Successful');
    }

    /**
     * @Route("/list/{channel}", name="chat_list", defaults={"channel" = "default"})
     * @Template
     */
    public function listAction($channel)
    {
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findBy(
            array('channel' => $channel),
            array('id' => 'asc')
        );

        return new JsonResponse(array('data' => json_encode($messages)));
    }
}

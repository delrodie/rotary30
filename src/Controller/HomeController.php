<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack, private ParticipantRepository $participantRepository
    )
    {
    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->participantRepository->save($participant, true);

            return $this->redirectToRoute('app_inscription_show', ['slug' => $participant->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/index.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name:'app_inscription_show', methods: ['GET'])]
    public function show(Participant $participant)
    {
        return $this->render('home/show.html.twig',['participant' => $participant]);
    }
}

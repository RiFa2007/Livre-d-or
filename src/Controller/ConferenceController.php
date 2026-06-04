<?php

namespace App\Controller;
use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

final class ConferenceController extends AbstractController
{
   #[Route('/', name: 'homepage')]
   public function index(ConferenceRepository $conferenceRepository): Response
   {

      return $this->render("conference\index.html.twig", [
         'conferences' => $conferenceRepository->findAll()
      ]);
   }

   #[Route('/conference/{id}', name: 'conference')]
   public function show(Request $request,Environment $twig, Conference $conference, CommentRepository $commentRepository, ConferenceRepository $conferenceRepository): Response
   {

      $offset = max(0,$request->query->getInt('offset',0));
      $paginator = $commentRepository->getCommentPaginator($conference,$offset);

      return $this->render('conference/show.html.twig', [
         'conferences' => $conferenceRepository->findAll(),
         'conference' => $conference,
         // 'comments' => $commentRepository->findBy(['conference' => $conference], ['createdAt' => 'DESC']),
         /* on recupere les commentaire de chaque conference
            et on les arrange en ordre decroissant par date*/
         'comments' => $paginator,// On rajoute de la pagination
         'previous' => $offset - CommentRepository::COMMENTS_PER_PAGE,
         'next' => min(count($paginator),$offset + CommentRepository::COMMENTS_PER_PAGE)
      ]);
   }
}

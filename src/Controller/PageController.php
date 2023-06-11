<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Project;
use App\Entity\User;
use App\Form\PageType;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/page')]
#[IsGranted('ROLE_ADMIN')]
class PageController extends AbstractController
{
    #[Route('/new', name: 'app_page_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PageRepository $pageRepository, SerializerInterface $serializer): Response
    {
        $projectId = intval($request->query->get('projectId')) ?? null;

        $page = new Page();
        $form = $this->createForm(PageType::class, $page, ['project_id' => $projectId]);
        $form->handleRequest($request);

        if ($page->getParent()) {
            $page->setLevel($page->getParent()->getLevel() + 1);
        } else {
            $page->setLevel(1);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $pageRepository->save($page, true);

            return $this->redirectToRoute('app_page_show', ['id' => $page->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('page/new.html.twig', [
            'page' => $page,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_page_show', methods: ['GET'])]
    public function show(Page $page, ManagerRegistry $doctrine, NormalizerInterface $normalizer): Response
    {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['pageShow'])
            ->withSkipNullValues(true)
            ->toArray();

        /** @var ?User $user */
        $user = $this->getUser();

        $isEditor = in_array('ROLE_DEV', $user->getRoles()) || in_array('ROLE_ADMIN', $user->getRoles());

        return $this->render('page/show.html.twig', [
            'pageData' => $normalizer->normalize($page, null, $context), //$project
            'page' => $page,
            'projectId' => $page->getProject()->getId(),
            'isEditor' => $isEditor
        ]);
    }



    #[Route('/{id}/edit', name: 'app_page_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Page $page, PageRepository $pageRepository, SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        $newPage = $serializer->deserialize(
            $request->getContent(),
            Page::class,
            'json'
        );

        $project = $page->getProject();

        $oldPage = $project->getPages()->matching(Criteria::create()->where(Criteria::expr()->eq('id', $page->getId())))->get(0);
        $oldPage->setFile($newPage->getFile());
        $oldPage->setHeader($newPage->getHeader());

        $pageRepository->save($oldPage, true);

        return new JsonResponse(
            [
                'status' => '200',
            ]
        );
    }

    #[Route('/{id}', name: 'app_page_delete', methods: ['POST'])]
    public function delete(Request $request, Page $page, PageRepository $pageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            $pageRepository->remove($page, true);
        }

        return $this->redirectToRoute('app_project_show', ['id' => $page->getProject()->getId()], Response::HTTP_SEE_OTHER);
    }
}

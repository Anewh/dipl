<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/page')]
class PageController extends AbstractController
{
    #[Route('/', name: 'app_page_index', methods: ['GET'])]
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('page/index.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_page_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PageRepository $pageRepository): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageRepository->save($page, true);

            return $this->redirectToRoute('app_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('page/new.html.twig', [
            'page' => $page,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_page_show', methods: ['GET'])]
    public function show(Page $page, ManagerRegistry $doctrine, NormalizerInterface $normalizer): Response
    {

        $entityManager = $doctrine->getManager();
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['pageShow'])
            ->withSkipNullValues(true)
            ->toArray();

        return $this->render('page/show.html.twig', [
            'pageData' => $normalizer->normalize($page, null, $context),//$project
            'page' => $page,
            'projectId' => $page->getProject()->getId()
        ]);
    }

    
    
    #[Route('/{id}/edit', name: 'app_page_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Page $page, PageRepository $pageRepository,SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        // dd($page);
        // $newPage = ($request->getContent());
        //dd($newPage);

        $newPage = $serializer->deserialize(
            $request->getContent(),
            Page::class,
            'json'
        );

        $project = $page->getProject();


        

        //$project->removePage($project->getPages()->matching(Criteria::create()->where(Criteria::expr()->eq('id', $page->getId())))->get(0));
        $oldPage = $project->getPages()->matching(Criteria::create()->where(Criteria::expr()->eq('id', $page->getId())))->get(0);
        $oldPage->setFile($newPage->getFile());
        $oldPage->setHeader($newPage->getHeader());

        $pageRepository->save($oldPage, true);

        return new JsonResponse([
            'status' => '200',
            ]
        );

        //$oldPage->
        //$entityManager = $doctrine->getManager();
        //$projectRepository = $entityManager->getRepository(User::class)->findOneByPage($page);

        
        //dd($page);
        //$projectRepository->save($project, true);     
        

        // return new JsonResponse([
        //     'status' => 'deleted',
        //     'id' => $field_id
        //     ]
        // );



        // $form = $this->createForm(PageType::class, $page);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $pageRepository->save($page, true);

        //     return $this->redirectToRoute('app_page_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->renderForm('page/edit.html.twig', [
        //     'page' => $page,
        //     'form' => $form,
        // ]);
    }

    #[Route('/{id}', name: 'app_page_delete', methods: ['POST'])]
    public function delete(Request $request, Page $page, PageRepository $pageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $pageRepository->remove($page, true);
        }

        return $this->redirectToRoute('app_page_index', [], Response::HTTP_SEE_OTHER);
    }
}

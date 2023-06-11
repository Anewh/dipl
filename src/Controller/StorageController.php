<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Storage;
use App\Entity\Team;
use App\Entity\User;
use App\Form\StorageType;
use App\Repository\StorageRepository;
use App\Service\GithubService;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/storage')]
#[IsGranted('ROLE_USER')]
class StorageController extends AbstractController
{
    #[Route('/', name: 'app_storage_index', methods: ['GET'])]
    public function index(GithubService $githubService): Response
    {
        /** @var ?User $user */
        $user = $this->getUser();
        $team = $user->getTeam();
        $projects = $team->getProjects()->toArray();

        $charts = [];

        foreach ($projects as $project) {
            $charts[] = [
                'graph' => $githubService->getAccumulatedData($project,  $user),
                'project' => $project
            ];
        }

        return $this->render('storage/index.html.twig', [
            'charts' => $charts,
        ]);
    }

    // #[Route('/{projectId}', name: 'app_project_storage_index', methods: ['GET'])]
    // public function indexByProject(string $projectId, GithubService $githubService,  ManagerRegistry $doctrine): Response
    // {
    //     /** @var ?User $user */
    //     $user = $this->getUser();

    //     $entityManager = $doctrine->getManager();
    //     $project = $entityManager->getRepository(Project::class)->findOneById($projectId);

    //     $chart = ['graph' => $githubService->getAccumulatedData($project,  $user), 'project' => $project];

    //     return $this->render('storage/index_by_project.html.twig', [
    //         'chart' => $chart,
    //     ]);
    // }

    #[Route('/new', name: 'app_storage_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DEV')]
    public function new(Request $request, StorageRepository $storageRepository): Response
    {
        $storage = new Storage();
        $form = $this->createForm(StorageType::class, $storage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $storageRepository->save($storage, true);

            return $this->redirectToRoute('app_storage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('storage/new.html.twig', [
            'storage' => $storage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_storage_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DEV')]
    public function edit(Request $request, Storage $storage, StorageRepository $storageRepository): Response
    {
        $form = $this->createForm(StorageType::class, $storage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $storageRepository->save($storage, true);

            return $this->redirectToRoute('app_storage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('storage/edit.html.twig', [
            'storage' => $storage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_storage_delete', methods: ['POST'])]
    #[IsGranted('ROLE_DEV')]
    public function delete(Request $request, Storage $storage, StorageRepository $storageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $storage->getId(), $request->request->get('_token'))) {
            $storageRepository->remove($storage, true);
        }

        return $this->redirectToRoute('app_storage_index', [], Response::HTTP_SEE_OTHER);
    }
}

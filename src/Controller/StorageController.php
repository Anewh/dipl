<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Storage;
use App\Entity\User;
use App\Form\StorageType;
use App\Repository\StorageRepository;
use App\Service\GithubService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/storage')]
class StorageController extends AbstractController
{
    #[Route('/', name: 'app_storage_index', methods: ['GET'])]
    public function index(StorageRepository $storageRepository, GithubService $githubService): Response
    {

        /** @var ?User $user */
        $user = $this->getUser();
        $team = $user->getTeam();
        //dd($team);

        $projects = $team->getProjects();
        $chart = [];

        //dd($projects);
        // foreach($projects as $p){
        //     array_push($chart, $githubService->getStoragesData($p, $user));
        // }


        $chart = $githubService->getStoragesData($projects[0], $user);




        return $this->render('storage/index.html.twig', [
            'chart' => $chart,
        ]);
    }

    /**
     *
     * @ParamConverter("project", options={"mapping": {"project_id"   : "id"}})
     *
     */
    // #[Route('/{project_id}/statistic', name: 'app_storage_by_project', methods: ['GET'])]
    // public function indexByProject(StorageRepository $storageRepository, Project $project, GithubService $githubService): Response
    // {
    //     //$storages = $storageRepository->findByProject($project);
    //     // $charts = $githubService->getStoragesData($project);
    //     // return $this->render('storage/index_by_project.html.twig', [
    //     //     'chart' => $charts,
    //     // ]);
    // }


    #[Route('/new', name: 'app_storage_new', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_storage_show', methods: ['GET'])]
    public function show(Storage $storage): Response
    {
        return $this->render('storage/show.html.twig', [
            'storage' => $storage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_storage_edit', methods: ['GET', 'POST'])]
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
    public function delete(Request $request, Storage $storage, StorageRepository $storageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $storage->getId(), $request->request->get('_token'))) {
            $storageRepository->remove($storage, true);
        }

        return $this->redirectToRoute('app_storage_index', [], Response::HTTP_SEE_OTHER);
    }
}
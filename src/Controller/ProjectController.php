<?php

namespace App\Controller;

use App\Entity\Field;
use App\Entity\Page;
use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Repository\FieldRepository;
use App\Repository\PageRepository;
use App\Repository\ProjectRepository;
use App\Service\GithubService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Github\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/project')]
#[IsGranted('ROLE_USER')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        /** @var ?User $user */
        $user = $this->getUser();

        if(in_array('ROLE_ADMIN', $user->getRoles())){
            $projects = $projectRepository->findAll();
        }
        else if ($user->getTeam()!== null) {
            $projects = array_merge($user->getTeam()->getProjects()->toArray(), $user->getProjects()->toArray());
        } else {
            $projects = $user->getProjects()->toArray();
        }

        return $this->render('project/index.html.twig', [
            'projects' => array_unique($projects),
            'personal_projects' => $user->getProjects()
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DEV')]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        /** @var ?User $user */
        $user = $this->getUser();

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->addTeam($user->getTeam());
            $projectRepository->save($project, true);

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/field/{field_id}/edit', name: 'app_field_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DEV')]
    public function editField(Request $request, Project $project, string $field_id, FieldRepository $fieldRepository, SerializerInterface $serializer, ProjectRepository $projectRepository): Response
    {
        $field = $serializer->deserialize(
            $request->getContent(),
            Field::class,
            'json'
        );

        if($field_id === 'new'){
            $project->addField($field);
        }
        else {
            $oldField = $project->getFields()->matching(
                Criteria::create()
                ->where(Criteria::expr()->eq('id', $field_id))
            )->get(0);

            $project->removeField($oldField);
            $project->addField($field);
        }
        $projectRepository->save($project, true);


        return new JsonResponse([
            'status' => '200',
            'new_id' => $field->getId()
            ]
        );
    }

    #[Route('/{id}/field/{field_id}/delete', name: 'app_field_delete', methods: ['POST'])]
    #[IsGranted('ROLE_DEV')]
    public function deleteField(Request $request, Project $project, string $field_id, ProjectRepository $projectRepository): Response
    {
        $field = ($request->getContent());

        $project->removeField($project->getFields()->matching(Criteria::create()->where(Criteria::expr()->eq('id', $field_id)))->get(0));
        $projectRepository->save($project, true);     
        

        return new JsonResponse([
            'status' => 'deleted',
            'id' => $field_id
            ]
        );
    }

    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project, NormalizerInterface $normalizer): Response
    {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['projectShow'])
            ->withSkipNullValues(true)
            ->toArray();

        /** @var ?User $user */
        $user = $this->getUser();

        $isEditor = in_array('ROLE_DEV', $user->getRoles()) || in_array('ROLE_ADMIN', $user->getRoles());
        
        $pages = $project->getPages()->toArray();

        return $this->render('project/show.html.twig', [
            'projectData' => $normalizer->normalize($project, null, $context),//$project
            'project' => $project,
            'isEditor' => $isEditor,
            'pages' => $pages
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DEV')]
    public function edit(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectRepository->save($project, true);

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_delete', methods: ['POST'])]
    #[IsGranted('ROLE_DEV')]
    public function delete(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $projectRepository->remove($project, true);
        }

        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }
}

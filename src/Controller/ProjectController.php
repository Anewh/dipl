<?php

namespace App\Controller;

use App\Entity\Field;
use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Repository\FieldRepository;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Github\Client;
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

#[Route('/projects')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        /** @var ?User $user */
        $user = $this->getUser();
     
        return $this->render('project/index.html.twig', [
            'projects' => $user->getTeam()->getProjects(),
            'personal_projects' => $user->getProjects()
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectRepository->save($project, true);

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/field/{field_id}/edit', name: 'app_field_edit', methods: ['GET', 'POST'])]
    public function editField(Request $request, Project $project, string $field_id, FieldRepository $fieldRepository, SerializerInterface $serializer, ProjectRepository $projectRepository): Response
    {
        // $form = $this->createForm(FieldType::class, $field);
        
        // $form->handleRequest($request);


        //dd($request->getContent());
        $field = $serializer->deserialize(
            $request->getContent(),
            Field::class,
            'json'
        );

        
        
        //$person = $serializer->deserialize($data, Person::class, 'xml')
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
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $fieldRepository->save($field, true);

        //     return $this->redirectToRoute('app_field_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->renderForm('field/edit.html.twig', [
        //     'field' => $field,
        //     'form' => $form,
        // ]);
    }


    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project, ManagerRegistry $doctrine, NormalizerInterface $normalizer): Response
    {
        $entityManager = $doctrine->getManager();
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['projectShow'])
            ->withSkipNullValues(true)
            ->toArray();

        return $this->render('project/show.html.twig', [
            'projectData' => $normalizer->normalize($project, null, $context),//$project
            'project' => $project
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
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
    public function delete(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $projectRepository->remove($project, true);
        }

        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }

}

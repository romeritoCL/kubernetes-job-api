<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Job;
use App\Form\JobType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Job controller.
 *
 * @package App/Controler
 * @Route("/api", name="api_")
 */
class JobController extends AbstractFOSRestController
{
    /**
     * Lists all Jobs.
     * @Rest\Get("/job")
     *
     * @return Response
     */
    public function getJobAction(ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Job::class);
        $jobs = $repository->findall();
        return $this->handleView($this->view($jobs));
    }
    /**
     * Create Job.
     * @Rest\Post("/job")
     *
     * @return Response
     */
    public function postJobAction(ManagerRegistry $doctrine, Request $request)
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($job);
            $em->flush();
            return $this->handleView($this->view($job, Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}

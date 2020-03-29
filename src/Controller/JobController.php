<?php

namespace App\Controller;

use App\Entity\Job;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    public $serializer;

    public function __construct(SerializerService $serializerService)
    {
        $this->serializer = $serializerService->getSerializer();
    }

    /**
     * @Route("/jobs", name="job_index", methods={"GET"})
     */
    public function index()
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();
        $data = $this->serializer->normalize($jobs, null, ['groups' => 'jobs']);

        $jsonContent = $this->serializer->serialize($data, 'json');

        return new Response($jsonContent, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/jobs/{job}", name="jobs_index", methods={"GET"})
     */
    public function show(Job $job)
    {
        $data = $this->serializer->normalize($job, null, ['groups' => 'employees']);

        $jsonContent = $this->serializer->serialize($data, 'json');

        return new Response($jsonContent, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/jobs", name="job_create", methods={"POST"})
     */
    public function create(Request $request) {

        $job = new Job();
        $job->setTitle($request->request->get('title'));
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($job);
        $manager->flush();

        $data = $this->serializer->normalize($job, null, ['groups' => 'jobs']);
        $jsonContent = $this->serializer->serialize($data, 'json');

        return new Response($jsonContent, 201, [
            'Content-Type' => 'application/json'
        ]);
    }
}

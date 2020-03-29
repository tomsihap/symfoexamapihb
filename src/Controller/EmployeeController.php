<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Job;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    public $serializer;

    public function __construct(SerializerService $serializerService)
    {
        $this->serializer = $serializerService->getSerializer();
    }

    /**
     * @Route("/employees", name="employees_index", methods={"GET"})
     */
    public function index()
    {
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $data = $this->serializer->normalize($employees, null, ['groups' => 'employees']);

        $jsonContent = $this->serializer->serialize($data, 'json');

        return new Response($jsonContent, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/employees/{employee}", name="employees_show", methods={"GET"})
     */
    public function show(Employee $employee)
    {
        $data = $this->serializer->normalize($employee, null, ['groups' => 'employees']);

        $jsonContent = $this->serializer->serialize($data, 'json');

        return new Response($jsonContent, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/employees", name="employee_create", methods={"POST"})
     */
    public function create(Request $request) {

        $employee = new Employee();
        $employee->setLastname($request->request->get('lastname'));
        $employee->setFirstname($request->request->get('firstname'));
        $employee->setEmployementDate(new \DateTime($request->request->get('employement_date')));
        $employee->setJob(
            $this->getDoctrine()->getRepository(Job::class)->find(
                $request->request->get('job_id')
            )
        );
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($employee);
        $manager->flush();

        $data = $this->serializer->normalize($employee, null, ['groups' => 'employees']);
        $jsonContent = $this->serializer->serialize($data, 'json');

        return new Response($jsonContent, 201, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/employees/{employee}/edit", name="employee_patch", methods={"POST"})
     */
    public function update(Request $request, Employee $employee) {


        if ( !empty($request->request->get('firstname')) ) {
            $employee->setFirstname( $request->request->get('firstname') );
        }

        if ( !empty($request->request->get('lastname')) ) {
            $employee->setLastname( $request->request->get('lastname') );
        }

        if ( !empty($request->request->get('employement_date')) ) {
            $employee->setEmployementDate( new \DateTime($request->request->get('employement_date')) );
        }

        if ( !empty($request->request->get('job_id')) ) {
            $employee->setJob( $this->getDoctrine()->getRepository(Job::class)->find( $request->request->get('job_id') ) );
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        $data = $this->serializer->normalize($employee, null, ['groups' => 'employees']);
        $jsonContent = $this->serializer->serialize($data, 'json');

        return new Response($jsonContent, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/employees/{employee}", name="employee_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Employee $employee) {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($employee);

        $manager->flush();

        return new Response(null, 202);
    }
}

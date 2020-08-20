<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student")
     */
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    /**
     * @Route("/students/", name="add_student", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \JsonException
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['address'])) {
            throw new NotFoundHttpException('Required input missing!');
        }

        $address = new Address($data['address']['street'], $data['address']['streetNumber'], $data['address']['zipcode'], $data['address']['city']);

        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($data['teacher_id']);

        $student = new Student();
        $student->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setEmail($data['email'])
            ->setAddress($address)
            ->setTeacher($teacher);

        // you can fetch the EntityManager via $this->getDoctrine()
        $entityManager = $this->getDoctrine()->getManager();
        //persist & flush to save the entity
        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Student saved!'], Response::HTTP_CREATED);
    }

}

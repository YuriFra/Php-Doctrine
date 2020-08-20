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
     * @Route("/studentsBase", name="student")
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

        $address = new Address($data['address']['street'], $data['address']['street_number'], $data['address']['zipcode'], $data['address']['city']);

        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($data['teacher_id']);

        if (empty($data['teacher_id'])) {
            throw new NotFoundHttpException('Teacher id does not exist!');
        }

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

    /**
     * @Route("/students/{id}", name="get_student_detail", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        if (empty($id)) {
            throw new NotFoundHttpException('No student with this ID!');
        }

        $data = [
            'id' => $student->getId(),
            'first_name' => $student->getFirstName(),
            'last_name' => $student->getLastName(),
            'email' => $student->getEmail(),
            'street' => $student->getAddress()->getStreet(),
            'street_number' => $student->getAddress()->getStreetNumber(),
            'zipcode' => $student->getAddress()->getZipcode(),
            'city' => $student->getAddress()->getCity(),
            'teacher' => $student->getTeacher()->getFirstName()." ".$student->getTeacher()->getLastName()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/students", name="get_all_students", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        $data = [];

        foreach ($students as $student) {
            $data[] = [
                'id' => $student->getId(),
                'name' => $student->getFirstName()." ".$student->getLastName(),
                'email' => $student->getEmail(),
                'city' => $student->getAddress()->getCity(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/students/{id}", name="update_student", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        if (empty($id)) {
            throw new NotFoundHttpException('No student with this ID!');
        }

        $data = json_decode($request->getContent(), true);

        $address = new Address($data['address']['street'], $data['address']['street_number'], $data['address']['zipcode'], $data['address']['city']);

        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($data['teacher_id']);

        if (empty($data['teacher_id'])) {
            throw new NotFoundHttpException('Teacher id does not exist!');
        }

        empty($data['first_name']) ? true : $student->setFirstName($data['first_name']);
        empty($data['last_name']) ? true : $student->setLastName($data['last_name']);
        empty($data['email']) ? true : $student->setEmail($data['email']);
        empty($data['address']) ? true : $student->setAddress($address);
        empty($data['teacher_id']) ? true : $student->setTeacher($teacher);

        $this->getDoctrine()->getManager()->persist($student);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($student->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/students/{id}", name="delete_student", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        if (empty($id)) {
            throw new NotFoundHttpException('No student with this ID!');
        }

        $this->getDoctrine()->getManager()->remove($student);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['status' => 'Student deleted'], Response::HTTP_OK);
    }

}

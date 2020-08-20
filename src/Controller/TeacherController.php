<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacherBase/", name="teacher")
     */
    public function index()
    {
        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }

    /**
     * @Route("/teacher/", name="add_teacher", methods={"POST"})
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

        $teacher = new Teacher();
        $teacher->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setEmail($data['email'])
            ->setAddress($address);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($teacher);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Teacher saved!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/teacher/{id}", name="get_teacher_detail", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);

        if (empty($id)) {
            throw new NotFoundHttpException('No teacher with this ID!');
        }

        $data = [
            'id' => $teacher->getId(),
            'first_name' => $teacher->getFirstName(),
            'last_name' => $teacher->getLastName(),
            'email' => $teacher->getEmail(),
            'street' => $teacher->getAddress()->getStreet(),
            'street_number' => $teacher->getAddress()->getStreetNumber(),
            'zipcode' => $teacher->getAddress()->getZipcode(),
            'city' => $teacher->getAddress()->getCity(),
            'students' => $teacher->getStudents()->getValues()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/teachers", name="get_all_teachers", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $teachers = $this->getDoctrine()->getRepository(Teacher::class)->findAll();
        $data = [];

        foreach ($teachers as $teacher) {
            $data[] = [
                'id' => $teacher->getId(),
                'name' => $teacher->getFirstName()." ".$teacher->getLastName(),
                'email' => $teacher->getEmail(),
                'city' => $teacher->getAddress()->getCity(),
                'students' => $teacher->getStudents()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/teacher/{id}", name="delete_teacher", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);

        if (empty($id)) {
            throw new NotFoundHttpException('No student with this ID!');
        }

        $this->getDoctrine()->getManager()->remove($teacher);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['status' => 'Student deleted'], Response::HTTP_OK);
    }
}

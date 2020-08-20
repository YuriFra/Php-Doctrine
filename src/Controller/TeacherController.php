<?php

namespace App\Controller;

use App\Entity\Address;
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
     * @Route("/teachersBase/", name="teacher")
     */
    public function index()
    {
        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }

    /**
     * @Route("/teachers/", name="add_teacher", methods={"POST"})
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
     * @Route("/teachers/{id}", name="get_teacher_detail", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);

        if (empty($id)) {
            throw new NotFoundHttpException('No teacher with this ID!');
        }

        $studentList = [];
        foreach ($teacher->getStudents() as $student) {
            $studentList[] = ['name' => $student->getFirstName(). " ".$student->getLastName()];
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
            'students' => $studentList
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
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/teachers/{id}", name="update_teacher", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);

        if (empty($id)) {
            throw new NotFoundHttpException('No teacher with this ID!');
        }

        $data = json_decode($request->getContent(), true);

        $address = new Address($data['address']['street'], $data['address']['street_number'], $data['address']['zipcode'], $data['address']['city']);

        empty($data['first_name']) ? true : $teacher->setFirstName($data['first_name']);
        empty($data['last_name']) ? true : $teacher->setLastName($data['last_name']);
        empty($data['email']) ? true : $teacher->setEmail($data['email']);
        empty($data['address']) ? true : $teacher->setAddress($address);

        $this->getDoctrine()->getManager()->persist($teacher);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($teacher->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/teachers/{id}", name="delete_teacher", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);

        if (empty($id)) {
            throw new NotFoundHttpException('No teacher with this ID!');
        }

        $this->getDoctrine()->getManager()->remove($teacher);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['status' => 'Teacher deleted'], Response::HTTP_OK);
    }
}

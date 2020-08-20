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
     * @Route("/teacher/", name="teacher")
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
}

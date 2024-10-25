<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DoctorController extends AbstractController
{
  #[Route('/doctor', name: 'app_doctor')]
  public function index(): Response
  {
    return $this->render('doctor/index.html.twig', [
      'controller_name' => 'DoctorController',
    ]);
  }

  #[Route('/doctor/list', name: 'app_doctor_list')]
  public function doctorList(DoctorRepository $doctorRepository): Response
  {
    $doctorsDB = $doctorRepository->findAll();
    return $this->render('doctor/list-doctor.html.twig', [
      'doctors' => $doctorsDB,
    ]);
  }
  #[Route('/doctor/add', name: 'app_doctor_add')]
  public function addDoctor(Request $request, EntityManagerInterface $entityManager)
  {

    $doctor = new Doctor();
    $form = $this->createForm(type: DoctorType::class, data: $doctor);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($doctor);
      $entityManager->flush();
      return $this->redirectToRoute('app_doctor_list');
    }
    return $this->render('doctor/add-doctor.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/doctor/edit/{id}', name: 'app_doctor_edit')]
  public function editClub(Request $request, EntityManagerInterface $entityManager, $id)
  {
    $doctor = $entityManager->getRepository(Doctor::class)->find($id);
    $form = $this->createForm(type: doctorType::class, data: $doctor);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($doctor);
      $entityManager->flush();
      return $this->redirectToRoute('app_doctor_list');
    }
    return $this->render('doctor/edit-doctor.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/doctor/details/{id}', name: 'app_doctor_details')]
  public function authorDetails($id, doctorRepository $doctorRepository)
  {
    $doctor = $doctorRepository->find($id);
    //dd($doctor);
    return $this->render('doctor/details-doctor.html.twig', [
      'doctor' => $doctor
    ]);
  }

  #[Route('/doctor/search-by-date', name: 'app_doctor_search_by_date')]
  public function searchByDate(Request $request, DoctorRepository $doctorRepository): Response
  {
    // Récupère les dates depuis les paramètres de la requête
    $startDate = new \DateTime($request->query->get('startDate'));
    $endDate = new \DateTime($request->query->get('endDate'));

    // Appelle la méthode de recherche par date dans le DoctorRepository
    $doctors = $doctorRepository->findDoctorsByDateRange($startDate, $endDate);

    return $this->render('doctor/search-by-date.html.twig', [
      'doctors' => $doctors,
      'startDate' => $startDate,
      'endDate' => $endDate,
    ]);
  }
}

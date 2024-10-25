<?php

namespace App\Controller;

use App\Entity\Hospital;
use App\Form\HospitalType;
use App\Repository\HospitalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HospitalController extends AbstractController
{
  #[Route('/hospital', name: 'app_hospital')]
  public function index(): Response
  {
    return $this->render('hospital/index.html.twig', [
      'controller_name' => 'HospitalController',
    ]);
  }

  #[Route('/hospital/list', name: 'app_hospital_list')]
  public function hospitalList(HospitalRepository $hospitalRepository)
  {
    $hospitalsDB = $hospitalRepository->findAll();
    return $this->render('hospital/list-hospital.html.twig', [
      'hospitals' => $hospitalsDB
    ]);
  }

  #[Route('/hospital/add', name: 'app_hospital_add')]
  public function addHospital(Request $request, EntityManagerInterface $entityManager, HospitalRepository $hosiptalRepository)
  {

    $hospital = new \App\Entity\Hospital();
    $form = $this->createForm(type: HospitalType::class, data: $hospital);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($hospital);
      $entityManager->flush();
      return $this->redirectToRoute('app_hospital_list');
    }
    return $this->render('hospital/add-hospital.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/hospital/edit/{id}', name: 'app_hospital_edit')]
  public function editClub(Request $request, EntityManagerInterface $entityManager, $id)
  {
    $hospital = $entityManager->getRepository(Hospital::class)->find($id);
    $form = $this->createForm(type: HospitalType::class, data: $hospital);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($hospital);
      $entityManager->flush();
      return $this->redirectToRoute('app_hospital_list');
    }
    return $this->render('hospital/edit-hospital.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/hospital/details/{id}', name: 'app_hospital_details')]
  public function authorDetails($id, HospitalRepository $hospitalRepository)
  {
    $hospital = $hospitalRepository->find($id);
    //dd($hospital);
    return $this->render('hospital/details-hospital.html.twig', [
      'hospital' => $hospital
    ]);
  }

  #[Route('/hospital/search/{name}', name: 'app_hospital_search')]
  public function search($name, EntityManagerInterface $entityManager, Request $request)
  {
    if (empty($name)) {
      return $this->redirectToRoute('app_hospital_list');
    }
    $name = $request->query->get('name');


    // Rechercher des clubs par nom
    $hospitals = $entityManager->getRepository(Hospital::class)
      ->createQueryBuilder('c')
      ->where('c.name LIKE :name')
      ->setParameter('name', '%' . $name . '%')
      ->getQuery()
      ->getResult();

    //dd($name);
    return $this->render('hospital/hospital-search.html.twig', [
      'hospitals' => $hospitals,
      'name' => $name,
    ]);
  }

  #[Route('/hospital/remove/{id}', name: 'app_hospital_remove')]
    public function removehospital(EntityManagerInterface $entityManager , $id){
      $hospital = $entityManager->getRepository(Hospital::class)->find($id);
      
          $entityManager->remove($hospital);
          $entityManager->flush();

          return $this->redirectToRoute('app_hospital_list');

      }
      
      
}


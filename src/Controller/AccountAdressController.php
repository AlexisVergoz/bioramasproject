<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AccountAdressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/adresses", name="account_adress")
     */
    public function index(): Response
    {
        return $this->render('account/adress.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-adresse", name="account_adress_add")
     */
    public function add(Request $request): Response
    {
        $address = new Adress();
        $form = $this->createForm(AdressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            return $this->redirectToRoute('account_adress');
        }
        return $this->render('account/adress_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse{id}", name="account_adress_edit")
     */
    public function edit(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Adress::class)->findOneById($id);
        if (!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('account_adress');
        }
        $form = $this->createForm(AdressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            return $this->redirectToRoute('account_adress');
        }
        return $this->render('account/adress_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/suprimer-une-adresse{id}", name="account_adress_delete")
     */
    public function delete($id): Response
    {
        $address = $this->entityManager->getRepository(Adress::class)->findOneById($id);

        if ($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        
            return $this->redirectToRoute('account_adress');
        
    }
}

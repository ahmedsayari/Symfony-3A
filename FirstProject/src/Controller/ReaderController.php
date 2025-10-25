<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Form\ReaderType;
use App\Repository\ReaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReaderController extends AbstractController
{
    #[Route('/readers', name: 'app_reader_index')]
    public function index(ReaderRepository $readerRepository, Request $request): Response
    {
        $q = $request->query->get('q');

        if ($q) {
            // search by username using DQL
            $readers = $readerRepository->findByUsernameLikeDql($q);
        } else {
            // ordered list via DQL
            $readers = $readerRepository->findAllOrderedByUsernameDql();
        }

        return $this->render('reader/index.html.twig', [
            'readers' => $readers,
        ]);
    }

    #[Route('/reader/new', name: 'app_reader_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $reader = new Reader();

        $form = $this->createForm(ReaderType::class, $reader);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($reader);
            $em->flush();

            return $this->redirectToRoute('app_reader_index');
        }

        return $this->render('reader/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reader/{id}', name: 'app_reader_show', requirements: ['id' => '\\d+'])]
    public function show(Reader $reader): Response
    {
        return $this->render('reader/show.html.twig', [
            'reader' => $reader,
        ]);
    }

    #[Route('/reader/{id}/edit', name: 'app_reader_edit', requirements: ['id' => '\\d+'])]
    public function edit(Request $request, Reader $reader, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReaderType::class, $reader);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_reader_index');
        }

        return $this->render('reader/edit.html.twig', [
            'form' => $form->createView(),
            'reader' => $reader,
        ]);
    }

    #[Route('/reader/{id}/delete', name: 'app_reader_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function delete(Request $request, Reader $reader, EntityManagerInterface $em): Response
    {
        // Delete without CSRF protection as requested (simple flow)
        $em->remove($reader);
        $em->flush();

        return $this->redirectToRoute('app_reader_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/authors', name: 'app_author_index')]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/new', name: 'app_author_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('app_author_index');
        }

        return $this->render('author/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/author/{id}', name: 'app_author_show', requirements: ['id' => '\\d+'])]
    public function show(Author $author, BookRepository $bookRepository): Response
    {
        // Use DQL-based repository method to fetch enabled books for this author
        $books = $bookRepository->findEnabledByAuthorDql($author);

        return $this->render('author/show.html.twig', [
            'author' => $author,
            'books' => $books,
        ]);
    }

    #[Route('/author/{id}/edit', name: 'app_author_edit', requirements: ['id' => '\\d+'])]
    public function edit(Request $request, Author $author, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_author_index');
        }

        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }

    #[Route('/author/{id}/delete', name: 'app_author_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function delete(Request $request, Author $author, EntityManagerInterface $em): Response
    {
        // Delete without CSRF protection as requested (simple flow)
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('app_author_index');
    }
}
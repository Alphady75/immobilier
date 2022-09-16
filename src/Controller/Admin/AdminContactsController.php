<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contacts')]
class AdminContactsController extends AbstractController
{
    #[Route('/', name: 'admin_contacts_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $contacts = $paginator->paginate(
            $contactRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_contacts/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/{id}', name: 'admin_contacts_show', methods: ['GET'])]
    public function show(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if($contact->getLu() == 0){
            $contact->setLu(true);
            $entityManager->flush();
        }

        return $this->render('admin/admin_contacts/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}', name: 'admin_contacts_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Le message a bien été supprimer');
        }

        return $this->redirectToRoute('admin_contacts_index', [], Response::HTTP_SEE_OTHER);
    }
}

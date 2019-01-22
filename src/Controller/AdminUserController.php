<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Tests\Node\Obj;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_users_index")
     *
     * @param $page integer
     * @param PaginationService $pagination
     * @return Response
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(User::class)
            ->setCurrentPage($page)
            ->setLimit(5);

        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Delete an user
     *
     * @Route("/admin/users/{id}/delete", name="admin_user_delete")
     *
     * @param User $user
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(User $user, ObjectManager $manager)
    {
        if(count($user->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'utilisateur <strong>{$user->getFullName()}</strong> car il a des réservations en cours !"
            );
        } else {
            $manager->remove($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur a bien été supprimé"
            );
        }
        return $this->redirectToRoute('admin_users_index');
    }
}

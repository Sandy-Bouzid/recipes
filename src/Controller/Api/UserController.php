<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/users')]
class UserController extends AbstractController
{
    #[Route('/show')]
    #[IsGranted('ROLE_USER')]
    public function show()
    {
        return $this->json(
            $this->getUser(),
            200,
            [],
            ['groups' => ['users.show']]
        );
    }
}

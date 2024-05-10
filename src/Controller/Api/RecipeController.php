<?php

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api/recipes', methods: ['GET'])]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return $this->json(
            $recipes,
            200,
            [],
            ['groups' => ['recipes.index']]
        );
    }

    #[Route('/{id}', requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe): Response
    {
        return $this->json(
            $recipe,
            Response::HTTP_OK,
            [],
            ['groups' => ['recipes.show']]
        );
    }

    #[Route('/', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $recipe = new Recipe();

        $serializer->deserialize($request->getContent(), Recipe::class, 'json', [
            // pour hydrater directement avec les données désérialisées (sans faire les setter)
            AbstractNormalizer::OBJECT_TO_POPULATE => $recipe,
            // éléments  modifiables
            'groups' => 'recipes.create'
        ]);

        $recipe->setSlug(strtolower($slugger->slug($recipe->getTitle())));
        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($recipe);
        $em->flush();

        return $this->json(
            $recipe,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['recipes.show']]
        );
    }
}

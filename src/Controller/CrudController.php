<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ExerciseConfirmationType;
use App\Form\ExerciseDeleteType;


class CrudController extends AbstractController
{
    /**
     * @Route("/exercise/create", name="exercise_create")
     */
    public function create(Request $request): Response
    {
        $exercise = new Exercise();

        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exercise);
            $entityManager->flush();

            return $this->redirectToRoute('exercise_view', ['id' => $exercise->getId()]);
        }

        return $this->render('exercise/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/exercise/{id}", name="exercise_view")
     */
    public function view($id): Response
    {
        // Get the Exercise entity by ID from the database
        $exercise = $this->getDoctrine()->getRepository(Exercise::class)->find($id);

        // Check if the entity exists
        if (!$exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        return $this->render('exercise/view.html.twig', [
            'exercise' => $exercise,
        ]);
    }

    /**
     * @Route("/exercise/{id}/edit", name="exercise_edit")
     */
    public function edit(Request $request, $id): Response
    {
        // Retrieve the Exercise entity by ID from the database
        $entityManager = $this->getDoctrine()->getManager();
        $exercise = $entityManager->getRepository(Exercise::class)->find($id);

        // Check if the entity exists
        if (!$exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        // Create the form to edit the exercise entity
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the edited exercise entity to the database
            $entityManager->flush();

            // Redirect to the view page with the correct exercise ID
            return $this->redirectToRoute('exercise_view', ['id' => $exercise->getId()]);
        }

        return $this->render('exercise/edit.html.twig', [
            'form' => $form->createView(),
            'exercise' => $exercise, // Pass the exercise entity to the template
        ]);
    }

    /**
    * @Route("/exercise/{id}/delete", name="exercise_delete", methods={"POST"})
    */
    public function delete(Request $request, $id): Response
    {
        // Retrieve the Exercise entity by ID from the database
        $entityManager = $this->getDoctrine()->getManager();
        $exercise = $entityManager->getRepository(Exercise::class)->find($id);

        // Check if the entity exists
        if (!$exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        // Create the delete form
        $form = $this->createForm(ExerciseDeleteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($exercise);
            $entityManager->flush();
            $this->addFlash('success', 'Exercise deleted successfully.');
            return $this->redirectToRoute('exercise_list');
        }

        return $this->render('exercise/delete.html.twig', [
            'form' => $form->createView(),
            'exercise' => $exercise,
        ]);
    }

    /**
    * @Route("/exercises", name="exercise_list", methods={"GET"})
    */
    public function list(): Response
    {
        // Fetch the list of exercises from the database
        $exercises = $this->getDoctrine()->getRepository(Exercise::class)->findAll();

        return $this->render('exercise/list.html.twig', [
            'exercises' => $exercises,
        ]);
    }

}

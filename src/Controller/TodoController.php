<?php
namespace App\Controller;

use App\Form\TaskType;
use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TodoController extends AbstractController{
	
	/**
	 * @Route("/", name="home_page")
	 * @Method({"GET"})
	 */

	public function index()
	{
		$tasks = $this->getDoctrine()
			->getRepository(Task::class)
			->findAll();

		if (!$tasks) {
			return $this->render('no-task.html.twig');
		}
		
		return $this->render('homepage.html.twig', ['tasks'=> $tasks]);
	}
	
	/**
	 * @Route("/add", name="add")
	 * @Method({"GET", "POST"})
	 */

	public function add(Request $request) {
		$task = new Task();
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createForm(TaskType::class, $task);
	
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$task = $form->getData();

			$entityManager = $this->getDoctrine()->getManager();

			$entityManager->persist($task);
			$entityManager->flush();
			return $this->redirectToRoute('home_page');

		}
        return $this->render('add.html.twig', array(
            'form' => $form->createView(),
        ));
	}
	
	/**
	 * @Route("/remove/{id}", name="remove")
	 */
	
	public function remove(Request $request, $id) {
		$entityManager = $this->getDoctrine()->getManager();

		$task = $entityManager->getRepository(Task::class)->find($id);

		if (!$task) {
        	throw $this->createNotFoundException(
            	'No product found for id '.$id
        	);
    	}
		
		$entityManager->remove($task);
		$entityManager->flush();

		return $this->redirectToRoute('home_page');
	}
}

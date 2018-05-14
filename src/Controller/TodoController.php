<?php
namespace App\Controller;

use App\Form\TaskType;
use App\Entity\Task;
use App\Entity\CompletedTask;
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

		$completedTasks = $this->getDoctrine()
			->getRepository(CompletedTask::class)
			->findAll();

		$tasksNum = count($tasks);
		$cTasksNum = count($completedTasks);
		$totalTasks = $tasksNum + $cTasksNum;
		$barWidth = 0;
		if ($totalTasks != 0)
			$barWidth = ($cTasksNum / $totalTasks) * 100;

		if (!$tasks) {
			return $this->render('no-task.html.twig',[
					'completedTasks'=> $completedTasks,
					'tasksNum'=> $tasksNum,
					'barWidth'=> $barWidth,
					'totalTasks'=> $totalTasks
				]
			);
		}
		
		return $this->render('homepage.html.twig',[
				'tasks'=> $tasks,
				'completedTasks'=> $completedTasks,
				'tasksNum'=> $tasksNum,
				'barWidth'=> $barWidth,
				'totalTasks'=> $totalTasks
			]
		);
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
	 * @Route("/complete/clear", name="clear")
	 * @Method({"POST", "GET"})
	 */

	public function clear()
	{
		$entityManager = $this->getDoctrine()->getManager();

		$completedTasks = $this->getDoctrine()
			->getRepository(CompletedTask::class)
			->findAll();

		foreach ($completedTasks as $task) {

			$entityManager->remove($task);
			$entityManager->flush();
		
		}

		return $this->redirectToRoute('home_page');
	}

	/**
	 * @Route("/complete/{id}", name="complete")
	 * @Method({"POST", "GET"})
	 */

	public function complete($id) {
		
		$entityManager = $this->getDoctrine()->getManager();

		$completedTask = new CompletedTask();
		$completedTask->setDateCompleted(new \DateTime());

		$task = $entityManager->getRepository(Task::class)->find($id);

		if (!$task) {
			throw $this->createNotFoundException(
				'No task found with id'.$id
			);
		}

		$completedTask->setTask($task->getTask());

		$entityManager->persist($completedTask);
		$entityManager->flush();
		
		$entityManager->remove($task);
		$entityManager->flush();

		return $this->redirectToRoute('home_page');
	}

	/**
	 * @Route("/remove/{id}", name="remove")
	 * @Method({"DELETE", "GET"})
	 */
	
	public function remove(Request $request, $id) {
		
		$entityManager = $this->getDoctrine()->getManager();

		$task = $entityManager->getRepository(Task::class)->find($id);

		if (!$task) {
        	throw $this->createNotFoundException(
            	'No task found with id '.$id
        	);
    	}
		
		$entityManager->remove($task);
		$entityManager->flush();

		return $this->redirectToRoute('home_page');
	}
}

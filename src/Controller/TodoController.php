<?php
namespace App\Controller;

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
			/*throw $this->createNotFoundException(
				'No tasks in list'
			);*/
			return $this->render('base.html.twig');
		}
		
		return $this->render('homepage.html.twig', ['tasks'=> $tasks]);
	}
	
	/**
	 * @Route("/add", name="add")
	 * @Method({"GET", "POST"})
	 */

	public function add() {
		$task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        return $this->render('add.html.twig', array(
            'form' => $form->createView(),
        ));
	}
}

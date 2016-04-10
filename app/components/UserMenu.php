<?php
namespace App\Components;

use App\Model\UserRepositoryInterface;
use Nette\Application\UI\Control;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class UserMenu extends Control
{

	/**
	 *
	 * @var UserRepositoryInterface
	 */
	protected $userRepository;

	/**
	 *
	 * @param UserRepositoryInterface $userRepository
	 */
	public function __construct(UserRepositoryInterface $userRepository)
	{
		parent::__construct();

		$this->userRepository = $userRepository;
	}

	public function render($event)
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/userMenu.latte');
		$template->event = $event;
		$template->items = $this->userRepository->getUsers();
		$template->render();
	}
}

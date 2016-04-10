<?php
namespace App\Presenters;

use App\Components\EventMenu;
use App\Components\UserMenu;
use App\Model\EventRepositoryInterface;
use App\Model\UserRepositoryInterface;
use Nette\Application\UI\Presenter;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class ViewPresenter extends Presenter
{

	/**
	 *
	 * @var UserRepositoryInterface
	 */
	protected $userRepository;

	/**
	 *
	 * @var EventRepositoryInterface
	 */
	protected $eventRepository;

	/**
	 *
	 * @param UserRepositoryInterface $userRepository
	 */
	public function injectUserRepository(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 *
	 * @param EventRepositoryInterface $eventRepository
	 */
	public function injectEventRepository(EventRepositoryInterface $eventRepository)
	{
		$this->eventRepository = $eventRepository;
	}

	public function renderEvent($event)
	{
		$this->template->event = $event;
	}

	public function renderUser($event, $user)
	{
		$this->template->event = $event;
		$this->template->user = $user;
	}

	/**
	 *
	 * @return EventMenu
	 */
	protected function createComponentEventMenu()
	{
		return new EventMenu($this->eventRepository);
	}

	/**
	 *
	 * @return UserMenu
	 */
	protected function createComponentUserMenu()
	{
		return new UserMenu($this->userRepository);
	}
}

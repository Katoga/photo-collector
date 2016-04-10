<?php
namespace App\Presenters;

use App\Components\Menu;
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

	protected function createComponentMenu($level)
	{
		return new Menu($this->userRepository, $this->eventRepository);
	}
}

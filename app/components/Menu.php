<?php
namespace App\Components;

use App\Model\EventRepositoryInterface;
use App\Model\UserRepositoryInterface;
use Nette\Application\UI\Control;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class Menu extends Control
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
	 * @param EventRepositoryInterface $eventRepository
	 */
	public function __construct(UserRepositoryInterface $userRepository, EventRepositoryInterface $eventRepository)
	{
		parent::__construct();

		$this->userRepository = $userRepository;
		$this->eventRepository = $eventRepository;
	}

	/**
	 *
	 * @param string $section
	 */
	public function render($section)
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/menu.latte');
		$template->items = $this->getItems($section);
		$template->render();
	}

	/**
	 *
	 * @param string $section
	 * @return string[]
	 * @throws \InvalidArgumentException
	 */
	protected function getItems($section)
	{
		$repositoryName = sprintf('%sRepository', $section);
		if (!isset($this->{$repositoryName})) {
			throw new \InvalidArgumentException();
		}

		$methodName = sprintf('get%ss', $section);

		return $this->{$repositoryName}->{$methodName}();
	}
}

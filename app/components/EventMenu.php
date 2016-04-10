<?php
namespace App\Components;

use App\Model\EventRepositoryInterface;
use Nette\Application\UI\Control;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class EventMenu extends Control
{

	/**
	 *
	 * @var EventRepositoryInterface
	 */
	protected $eventRepository;

	/**
	 *
	 * @param EventRepositoryInterface $eventRepository
	 */
	public function __construct(EventRepositoryInterface $eventRepository)
	{
		parent::__construct();

		$this->eventRepository = $eventRepository;
	}

	public function render()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/eventMenu.latte');
		$template->items = $this->eventRepository->getEvents();
		$template->render();
	}
}

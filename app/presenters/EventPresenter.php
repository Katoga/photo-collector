<?php
namespace App\Presenters;

use App\Model\EventRepositoryInterface;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class EventPresenter extends Presenter
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
	public function injectEventRepository(EventRepositoryInterface $eventRepository)
	{
		$this->eventRepository = $eventRepository;
	}

	public function renderDefault()
	{
		$this->template->events = $this->eventRepository->getEvents();
	}

	/**
	 *
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function newEventFormSuccess(Form $form, ArrayHash $values)
	{
		try {
			$this->eventRepository->addEvent($values->name);
			$this->flashMessage('New event created.');
		} catch (\RuntimeException $e) {
			$this->flashMessage('Failed to create new event.');
		}

		$this->redirect('Event:');
	}

	protected function createComponentNewEventForm()
	{
		$form = new Form();

		$form->addText('name', 'Name');

		$form->setMethod(Form::POST);

		$form->addSubmit('submit');

		$form->onSuccess[] = [
			$this,
			'newEventFormSuccess'
		];

		return $form;
	}
}

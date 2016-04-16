<?php
namespace App\Presenters;

use App\Model\EventRepositoryInterface;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class EventPresenter extends BasePresenter
{

	/**
	 *
	 * @var EventRepositoryInterface
	 */
	protected $eventRepository;

	protected function startup()
	{
		parent::startup();

		if (!$this->user->isInRole('admin')) {
			$this->redirect('Homepage:');
		}
	}

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
		} catch (UniqueConstraintViolationException $e) {
			$this->flashMessage(sprintf('Event "%s" already exists!', $values->name));
		} catch (\Exception $e) {
			$this->flashMessage('Failed to create new event.');
		}

		$this->redirect('Event:');
	}

	/**
	 *
	 * @return Form
	 */
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

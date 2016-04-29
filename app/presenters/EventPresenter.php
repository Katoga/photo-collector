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
class EventPresenter extends AuthedPresenter
{

	/**
	 *
	 * @var EventRepositoryInterface
	 */
	protected $eventRepository;

	protected function startup()
	{
		parent::startup();

		if (!$this->getUser()->isInRole('admin')) {
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
			$this->flashMessage($this->translator->translate('txt.event.newCreated'));
		} catch (UniqueConstraintViolationException $e) {
			$this->flashMessage($this->translator->translate('txt.event.newDupe', ['name' => $values->name]));
		} catch (\Exception $e) {
			$this->flashMessage($this->translator->translate('txt.event.newFailed'));
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

		$form->addText('name', $this->translator->translate('txt.event.nameLabel'));

		$form->setMethod(Form::POST);

		$form->addSubmit('submit');

		$form->onSuccess[] = [
			$this,
			'newEventFormSuccess'
		];

		return $form;
	}
}

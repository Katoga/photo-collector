<?php
namespace App\Presenters;

use App\Model\EventRepositoryInterface;
use App\Model\File;
use App\Model\FileRepositoryInterface;
use App\Model\UserRepositoryInterface;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class UploadPresenter extends BasePresenter
{

	const ALLOWED_MIME_TYPES = [
		'image/jpeg',
		'application/zip'
	];

	/**
	 *
	 * @var FileRepositoryInterface
	 */
	protected $fileRepository;

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
	 * @param FileRepositoryInterface $fileRepository
	 */
	public function injectFileRepository(FileRepositoryInterface $fileRepository)
	{
		$this->fileRepository = $fileRepository;
	}

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

	protected function createComponentUploadForm()
	{
		$form = new Form();

		$form->addHidden('user', $this->getUser()->getId());
		$form->addSelect('event', 'Event', $this->getEventOptions())
			->setRequired();
		$form->addMultiUpload('photos', 'File')
			->addRule(Form::MIN_LENGTH, 'Add %d or more files!', 1)
			->addRule(Form::MIME_TYPE, 'File has to be JPEG or ZIP!', self::ALLOWED_MIME_TYPES);
		$form->addSubmit('submit');

		$form->setMethod(Form::POST);

		$form->onSuccess[] = [
			$this,
			'uploadFormSuccess'
		];

		return $form;
	}

	public function uploadFormSuccess(Form $form, ArrayHash $values)
	{
		$this->fileRepository->upload($values->user, $values->event, $values->photos);
		$this->flashMessage('Successfuly uploaded files.');
		$this->redirect('Upload:');
	}

	/**
	 *
	 * @return array
	 */
	protected function getUserOptions()
	{
		return $this->userRepository->getUsers();
	}

	/**
	 *
	 * @return array
	 */
	protected function getEventOptions()
	{
		$events = [
			'' => ''
		];
		$events += $this->eventRepository->getEvents();

		return $events;
	}
}

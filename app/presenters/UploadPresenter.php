<?php
namespace App\Presenters;

use App\Model\EventRepositoryInterface;
use App\Model\Uploader;
use App\Model\UserRepositoryInterface;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\ArrayHash;
use App\Model\UploaderInterface;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class UploadPresenter extends Presenter
{

	const ALLOWED_MIME_TYPES = [
		'image/jpeg',
		'application/zip'
	];

	/**
	 *
	 * @var UploaderInterface
	 */
	protected $uploader;

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
	 * @param UploaderInterface $uploader
	 */
	public function injectUploader(UploaderInterface $uploader)
	{
		$this->uploader = $uploader;
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

		$form->addSelect('user', 'User', $this->userRepository->getUsers())->setRequired();
		$form->addSelect('event', 'Event', $this->eventRepository->getEvents())->setRequired();
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
		$this->uploader->process($values->user, $values->event, $values->photos);
		$this->flashMessage('Successfuly uploaded files.');
		$this->redirect('Upload:');
	}
}
<?php
namespace App\Presenters;

use App\Model\EventRepositoryInterface;
use App\Model\FileRepositoryInterface;
use App\Model\AuthorRepositoryInterface;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class UploadPresenter extends AuthedPresenter
{
	const MIN_FILES = 1;

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
	 * @var AuthorRepositoryInterface
	 */
	protected $authorRepository;

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
	 * @param AuthorRepositoryInterface $authorRepository
	 */
	public function injectAuthorRepository(AuthorRepositoryInterface $authorRepository)
	{
		$this->authorRepository = $authorRepository;
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

		$form->addHidden('author', $this->getUser()->getId());
		$form->addSelect('event', $this->translator->translate('txt.upload.eventLabel'), $this->getEventOptions())
			->setRequired();
		$form->addMultiUpload('photos', $this->translator->translate('txt.upload.fileLabel'))
			->addRule(Form::MIN_LENGTH, $this->translator->translate('txt.upload.photosRuleLength', ['min_files' => self::MIN_FILES]), self::MIN_FILES)
			->addRule(Form::MIME_TYPE, $this->translator->translate('txt.upload.photosRuleType'), self::ALLOWED_MIME_TYPES);
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
		$this->fileRepository->upload($values->author, $values->event, $values->photos);
		$this->flashMessage($this->translator->translate('txt.upload.success'));
		$this->redirect('Upload:');
	}

	/**
	 *
	 * @return array
	 */
	protected function getAuthorOptions()
	{
		return $this->authorRepository->getAuthors();
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

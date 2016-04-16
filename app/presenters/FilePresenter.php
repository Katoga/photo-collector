<?php
namespace App\Presenters;

use App\Model\EventRepositoryInterface;
use App\Model\FileRepositoryInterface;
use App\Model\AuthorRepositoryInterface;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-11
 */
class FilePresenter extends BasePresenter
{

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
	 * @var FileRepositoryInterface
	 */
	protected $fileRepository;

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
	 * @param string $event
	 * @param string $author
	 * @param string $filename
	 */
	public function renderDefault($event, $author, $filename)
	{
		$this->template->event = $event;
		$this->template->author = $author;
		$this->template->filename = $filename;
	}
}

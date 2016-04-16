<?php
namespace App\Presenters;

use App\Components\EventMenu;
use App\Components\AuthorMenu;
use App\Model\EventRepositoryInterface;
use App\Model\FileRepositoryInterface;
use App\Model\AuthorRepositoryInterface;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class BrowsePresenter extends BasePresenter
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
		$this->fileRepository= $fileRepository;
	}

	/**
	 *
	 * @param string $event
	 */
	public function renderEvent($event)
	{
		$this->template->event = $event;
	}

	/**
	 *
	 * @param string $event
	 * @param string $author
	 */
	public function renderAuthor($event, $author)
	{
		$this->template->event = $event;
		$this->template->author = $author;

		$this->template->files = $this->fileRepository->get($event, $author);
	}

	/**
	 *
	 * @return EventMenu
	 */
	protected function createComponentEventMenu()
	{
		return new EventMenu($this->eventRepository);
	}

	/**
	 *
	 * @return AuthorMenu
	 */
	protected function createComponentAuthorMenu()
	{
		return new AuthorMenu($this->authorRepository);
	}
}

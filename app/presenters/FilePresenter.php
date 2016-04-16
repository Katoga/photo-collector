<?php
namespace App\Presenters;

use App\Model\EventRepositoryInterface;
use App\Model\FileRepositoryInterface;
use App\Model\UserRepositoryInterface;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-11
 */
class FilePresenter extends BasePresenter
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
	 * @var FileRepositoryInterface
	 */
	protected $fileRepository;

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
	 * @param string $user
	 * @param string $filename
	 */
	public function renderDefault($event, $user, $filename)
	{
		$this->template->event = $event;
		$this->template->user = $user;
		$this->template->filename = $filename;
	}
}

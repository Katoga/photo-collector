<?php
namespace App\Presenters;

use App\Model\FileRepositoryInterface;
use Nette\Application\Responses\FileResponse;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-11
 */
class FilePresenter extends AuthedPresenter
{

	/**
	 *
	 * @var FileRepositoryInterface
	 */
	protected $fileRepository;

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

		$data = $this->fileRepository->getFileInfo($event, $author, $filename);

		$this->sendResponse(new FileResponse($data['fullPath'], $filename, $data['mimeType'], false));
	}
}

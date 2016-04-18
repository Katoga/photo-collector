<?php
namespace App\Model;

use Nette\Http\FileUpload;
/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
interface FileRepositoryInterface
{
	/**
	 *
	 * @param string $author
	 * @param string $event
	 * @param FileUpload[] $fileUploads
	 * @throws \InvalidArgumentException
	 */
	public function upload($author, $event, array $fileUploads);

	/**
	 *
	 * @param string $event
	 * @param string $author
	 * @return array
	 */
	public function getList($event = '', $author = '');

	/**
	 *
	 * @param string $event
	 * @param string $author
	 * @param string $filename
	 * @return array
	 */
	public function getFileInfo($event, $author, $filename);
}

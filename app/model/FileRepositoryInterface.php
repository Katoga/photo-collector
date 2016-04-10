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
	 * @param string $user
	 * @param string $event
	 * @param FileUpload[] $fileUploads
	 * @throws \InvalidArgumentException
	 */
	public function upload($user, $event, array $fileUploads);

	/**
	 *
	 * @param string $event
	 * @param string $user
	 * @return string[]
	 */
	public function get($event = '', $user = '');
}

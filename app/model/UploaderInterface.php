<?php
namespace App\Model;

use Nette\Http\FileUpload;
/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
interface UploaderInterface
{
	/**
	 *
	 * @param string $user
	 * @param string $event
	 * @param FileUpload[] $fileUploads
	 * @throws \InvalidArgumentException
	 */
	public function process($user, $event, array $fileUploads);
}

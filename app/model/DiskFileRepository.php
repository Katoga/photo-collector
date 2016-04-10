<?php
namespace App\Model;

use Nette\Http\FileUpload;
use Nette\Utils\Finder;
use Nette\Utils\Strings;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class DiskFileRepository implements FileRepositoryInterface
{

	/**
	 *
	 * @var string
	 */
	protected $dataRootDir;

	/**
	 *
	 * @param string $dataRootDir
	 */
	public function __construct($dataRootDir)
	{
		$this->dataRootDir = realpath($dataRootDir);
	}

	/**
	 *
	 * {@inheritDoc}
	 * @see \App\Model\FileRepositoryInterface::upload()
	 */
	public function upload($user, $event, array $fileUploads)
	{
		foreach ($fileUploads as $fileUpload) {
			/* @var $fileUpload \Nette\Http\FileUpload */
			switch ($fileUpload->contentType) {
				case 'image/jpeg':
					$this->save($user, $event, $fileUpload);
					break;
				case 'application/zip':
					// TODO
					break;
				default:
					throw new \InvalidArgumentException(sprintf('Unsupported type of file "%s": %s"!', $fileUpload->name, $fileUpload->contentType));
			}
		}
	}

	/**
	 *
	 * {@inheritDoc}
	 * @see \App\Model\FileRepositoryInterface::get()
	 */
	public function get($event = '', $user = '')
	{
		$files = [];
		$directory = sprintf('%s/%s/%s', $this->dataRootDir, $event, $user);

		try {
			foreach (Finder::findFiles('*.jpeg', '*.jpg')->in($directory) as $path => $file) {
				$files[] = $path;
			}
		} catch (\UnexpectedValueException $e) {
			dump($e->getMessage());
		}

		return $files;
	}

	/**
	 *
	 * @param string $user
	 * @param string $event
	 * @param FileUpload $fileUpload
	 */
	protected function save($user, $event, FileUpload $fileUpload)
	{
		$fileUpload->move($this->getDestinationFilename($user, $event, $fileUpload->name));
	}

	/**
	 *
	 * @param string $user
	 * @param string $event
	 * @param string $name
	 * @return string
	 */
	protected function getDestinationFilename($user, $event, $name)
	{
		return sprintf('%s/%s', $this->getDestinationDirectory($user, $event), $name);
	}

	/**
	 *
	 * @param string $user
	 * @param string $event
	 * @return string
	 */
	protected function getDestinationDirectory($user, $event)
	{
		return sprintf('%s/%s/%s', $this->dataRootDir, Strings::webalize($event), Strings::webalize($user));
	}
}

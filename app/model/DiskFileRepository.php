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
		$this->dataRootDir = $dataRootDir;
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
				$filePath = explode('/', trim(str_replace($this->dataRootDir, '', $path), '/'));
				$pathParts = [
					'event',
					'user',
					'filename'
				];
				$filePath = array_combine($pathParts, $filePath);
				$files[] = $filePath;
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
		$pathInfo = pathinfo($name);

		$filename = sprintf('%s.%s', Strings::webalize($pathInfo['filename']), $pathInfo['extension']);
		return sprintf('%s/%s', $this->getDestinationDirectory($user, $event), $filename);
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

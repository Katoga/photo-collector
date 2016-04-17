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
	 * @see \App\Model\FileRepositoryInterface::upload()
	 */
	public function upload($author, $event, array $fileUploads)
	{
		foreach ($fileUploads as $fileUpload) {
			/* @var $fileUpload \Nette\Http\FileUpload */
			switch ($fileUpload->contentType) {
				case 'image/jpeg':
					$this->save($author, $event, $fileUpload);
					break;
				case 'application/zip':
					$zip = new \ZipArchive();
					$opened = $zip->open($fileUpload->temporaryFile, \ZipArchive::CHECKCONS);
					if ($opened !== true) {
						// TODO
						throw new \RuntimeException('zip failed ' . $opened);
					}

					for ($i = 0; $i < $zip->numFiles; $i++) {
						$name = $zip->getNameIndex($i);
						if (Strings::endsWith($name, '.jpg') || Strings::endsWith($name, '.jpeg')) {
							$fileInZip = sprintf('zip://%s#%s', $fileUpload->temporaryFile, $name);

							// taken from \Nette\Http\FileUpload::getSanitizedName()
							$sanitizedName = trim(Strings::webalize($name, '.', false), '.-');
							$destination = sprintf('%s/%s/%s/%s', $this->dataRootDir, $event, $author, $sanitizedName);

							copy($fileInZip, $destination);
						}
					}

					$zip->close();
					break;
				default:
					throw new \InvalidArgumentException(sprintf('Unsupported type of file "%s": %s"!', $fileUpload->name, $fileUpload->contentType));
			}
		}
	}

	/**
	 *
	 * @see \App\Model\FileRepositoryInterface::get()
	 */
	public function get($event = '', $author = '')
	{
		$files = [];
		$directory = sprintf('%s/%s/%s', $this->dataRootDir, $event, $author);

		try {
			foreach (Finder::findFiles('*.jpeg', '*.jpg')->in($directory) as $path => $file) {
				$filePath = explode('/', trim(str_replace($this->dataRootDir, '', $path), '/'));
				$pathParts = [
					'event',
					'author',
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
	 * @param string $author
	 * @param string $event
	 * @param FileUpload $fileUpload
	 */
	protected function save($author, $event, FileUpload $fileUpload)
	{
		$fileUpload->move($this->getDestinationFilename($author, $event, $fileUpload->getSanitizedName()));
	}

	/**
	 *
	 * @param string $author
	 * @param string $event
	 * @param string $name
	 * @return string
	 */
	protected function getDestinationFilename($author, $event, $name)
	{
		$pathInfo = pathinfo($name);

		$filename = sprintf('%s.%s', Strings::webalize($pathInfo['filename']), $pathInfo['extension']);
		return sprintf('%s/%s', $this->getDestinationDirectory($author, $event), $filename);
	}

	/**
	 *
	 * @param string $author
	 * @param string $event
	 * @return string
	 */
	protected function getDestinationDirectory($author, $event)
	{
		return sprintf('%s/%s/%s', $this->dataRootDir, Strings::webalize($event), Strings::webalize($author));
	}
}

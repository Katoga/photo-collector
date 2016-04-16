<?php
namespace App\Components;

use App\Model\AuthorRepositoryInterface;
use Nette\Application\UI\Control;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class AuthorMenu extends Control
{

	/**
	 *
	 * @var AuthorRepositoryInterface
	 */
	protected $authorRepository;

	/**
	 *
	 * @param AuthorRepositoryInterface $authorRepository
	 */
	public function __construct(AuthorRepositoryInterface $authorRepository)
	{
		parent::__construct();

		$this->authorRepository = $authorRepository;
	}

	public function render($event)
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/authorMenu.latte');
		$template->event = $event;
		$template->items = $this->authorRepository->getAuthors();
		$template->render();
	}
}

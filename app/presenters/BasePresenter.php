<?php
namespace App\Presenters;

use App\Components\MainMenu;
use Nette\Application\UI\Presenter;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class BasePresenter extends Presenter
{

	protected function startup()
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Auth:');
		}
	}

	/**
	 *
	 * @return MainMenu
	 */
	protected function createComponentMainMenu()
	{
		return new MainMenu();
	}
}

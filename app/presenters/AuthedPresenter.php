<?php
namespace App\Presenters;

use App\Components\MainMenu;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-29
 */
class AuthedPresenter extends BasePresenter
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

<?php
namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class AuthPresenter extends Presenter
{

	public function renderDefault()
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
	}

	public function actionLogout()
	{
		$this->user->logout();
		$this->redirect('Auth:');
	}

	/**
	 *
	 * @return Form
	 */
	protected function createComponentLoginForm()
	{
		$form = new Form();

		$form->addText('login', 'Login')->setRequired('Enter login.');
		$form->addPassword('password', 'Password')->setRequired('Enter password.');
		$form->addSubmit('submit', 'Log in');

		$form->setMethod(Form::POST);

		$form->onSuccess[] = [
			$this,
			'loginFormSuccess'
		];

		return $form;
	}

	/**
	 *
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function loginFormSuccess(Form $form, ArrayHash $values)
	{
		try {
			$this->user->login($values->login, $values->password);
		} catch (AuthenticationException $e) {
			$this->flashMessage('Invalid login/password.');
			$this->redirect('Auth:');
		}

		$this->redirect('Homepage:');
	}
}
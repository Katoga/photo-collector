<?php
namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class AuthPresenter extends BasePresenter
{

	public function renderDefault()
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
	}

	public function actionLogout()
	{
		$this->getUser()->logout();
		$this->redirect('Auth:');
	}

	/**
	 *
	 * @return Form
	 */
	protected function createComponentLoginForm()
	{
		$form = new Form();

		$form->addText('login', $this->translator->translate('txt.auth.loginLabel'))->setRequired($this->translator->translate('txt.auth.loginRequired'));
		$form->addPassword('password', $this->translator->translate('txt.auth.passwordLabel'))->setRequired($this->translator->translate('txt.auth.passwordRequired'));
		$form->addSubmit('submit', $this->translator->translate('txt.auth.loginButton'));

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
			$this->getUser()->login($values->login, $values->password);
		} catch (AuthenticationException $e) {
			$this->flashMessage($this->translator->translate('txt.auth.loginFailed'));
			$this->redirect('Auth:');
		}

		$this->redirect('Homepage:');
	}
}

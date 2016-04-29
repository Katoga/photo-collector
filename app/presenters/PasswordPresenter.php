<?php
namespace App\Presenters;

use App\Model\AuthorRepositoryInterface;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-17
 */
class PasswordPresenter extends AuthedPresenter
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
	public function injectAuthorRepository(AuthorRepositoryInterface $authorRepository)
	{
		$this->authorRepository = $authorRepository;
	}

	/**
	 *
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function changePasswordFormSuccess(Form $form, ArrayHash $values)
	{
		try {
			// validate old password
			$credentials = [
				$this->getUser()->getId(),
				$values->password
			];
			$this->getUser()->getAuthenticator()->authenticate($credentials);

			// old password OK, change password
			$this->authorRepository->changePassword($this->getUser()->getId(), $values->new_password);
			$this->flashMessage($this->translator->translate('txt.password.changed'));
		} catch (AuthenticationException $e) {
			$this->flashMessage($this->translator->translate('txt.password.oldIsWrong'));
		} catch (\Exception $e) {
			$this->flashMessage($this->translator->translate('txt.password.failed'));
		}

		$this->redirect('Password:');
	}

	/**
	 * @return Form
	 */
	protected function createComponentChangePasswordForm()
	{
		$form = new Form();

		$form->addGroup($this->translator->translate('txt.password.formLabel'));
		$form->addPassword('password', $this->translator->translate('txt.password.currentPasswordLabel'))
			->setRequired($this->translator->translate('txt.password.currentPasswordRequired'));
		$form->addPassword('new_password', $this->translator->translate('txt.password.newPasswordLabel'))
			->setRequired($this->translator->translate('txt.password.newPasswordRequired'))
			->addRule(Form::MIN_LENGTH, $this->translator->translate('txt.password.newPasswordRule', ['length' => AuthorPresenter::PASSWORD_MIN_LENGTH]), AuthorPresenter::PASSWORD_MIN_LENGTH);
		$form->addPassword('new_password2', $this->translator->translate('txt.password.newPasswordVerificationLabel'))
			->setRequired($this->translator->translate('txt.password.newPasswordVerificationRequired'))
			->addRule(Form::EQUAL, $this->translator->translate('txt.password.newPasswordVerificationRule'), $form['new_password']);

		$form->setMethod(Form::POST);

		$form->addSubmit('submit');

		$form->onSuccess[] = [
			$this,
			'changePasswordFormSuccess'
		];

		return $form;
	}
}

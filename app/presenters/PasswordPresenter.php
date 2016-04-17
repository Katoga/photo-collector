<?php
namespace App\Presenters;

use App\Components\ChangePasswordForm;
use App\Model\AuthorRepositoryInterface;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-17
 */
class PasswordPresenter extends BasePresenter
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
			$this->flashMessage('Password changed.');
		} catch (AuthenticationException $e) {
			$this->flashMessage('Old password is wrong.');
		} catch (\Exception $e) {
			$this->flashMessage('Failed to change password.');
		}

		$this->redirect('Password:');
	}

	/**
	 *
	 * @return ChangePasswordForm
	 */
	protected function createComponentChangePasswordForm()
	{
		$form = new Form();

		$form->addGroup('Change password');
		$form->addPassword('password', 'Current password')
			->setRequired('Enter password.');
		$form->addPassword('new_password', 'New password')
			->setRequired('Enter new password.')
			->addRule(Form::MIN_LENGTH, sprintf('Password has to be at least %d chars long.', AuthorPresenter::PASSWORD_MIN_LENGTH), AuthorPresenter::PASSWORD_MIN_LENGTH);
		$form->addPassword('new_password2', 'New password verification')
			->setRequired('Enter new password verification.')
			->addRule(Form::EQUAL, 'Passwords does not match!', $form['new_password']);

		$form->setMethod(Form::POST);

		$form->addSubmit('submit');

		$form->onSuccess[] = [
			$this,
			'changePasswordFormSuccess'
		];

		return $form;
	}
}

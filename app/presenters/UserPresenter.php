<?php
namespace App\Presenters;

use App\Model\UserRepositoryInterface;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class UserPresenter extends BasePresenter
{

	const NAME_MIN_LENGTH = 3;

	const PASSWORD_MIN_LENGTH = 5;

	/**
	 *
	 * @var UserRepositoryInterface
	 */
	protected $userRepository;

	/**
	 *
	 * @param UserRepositoryInterface $userRepository
	 */
	public function injectUserRepository(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function renderDefault()
	{
		$this->template->users = $this->userRepository->getUsers();
	}

	/**
	 *
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function newUserFormSuccess(Form $form, ArrayHash $values)
	{
		try {
			$this->userRepository->addUser($values->name, $values->password);
			$this->flashMessage('New user created.');
		} catch (UniqueConstraintViolationException $e) {
			$this->flashMessage(sprintf('User "%s" already exists!', $values->name));
		} catch (\Exception $e) {
			$this->flashMessage('Failed to create new user.');
		}

		$this->redirect('User:');
	}

	/**
	 *
	 * @return Form
	 */
	protected function createComponentNewUserForm()
	{
		$form = new Form();

		$form->addGroup('New user');
		$form->addText('name', 'Name')
			->setRequired('Enter name.')
			->addRule(Form::MIN_LENGTH, sprintf('Name has to be at least %d chars long.', self::NAME_MIN_LENGTH), self::NAME_MIN_LENGTH);
		$form->addPassword('password', 'Password')
			->setRequired('Enter password.')
			->addRule(Form::MIN_LENGTH, sprintf('Password has to be at least %d chars long.', self::PASSWORD_MIN_LENGTH), self::PASSWORD_MIN_LENGTH);
		$form->addPassword('password2', 'Password verification')
			->setRequired('Enter password verification.')
			->addRule(Form::EQUAL, 'Passwords does not match!', $form['password']);

		$form->setMethod(Form::POST);

		$form->addSubmit('submit');

		$form->onSuccess[] = [
			$this,
			'newUserFormSuccess'
		];

		return $form;
	}
}

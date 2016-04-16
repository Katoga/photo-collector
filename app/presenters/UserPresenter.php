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
			$this->userRepository->addUser($values->name);
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

		$form->addText('name', 'Name');

		$form->setMethod(Form::POST);

		$form->addSubmit('submit');

		$form->onSuccess[] = [
			$this,
			'newUserFormSuccess'
		];

		return $form;
	}
}

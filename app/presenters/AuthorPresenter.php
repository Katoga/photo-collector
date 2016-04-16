<?php
namespace App\Presenters;

use App\Model\AuthorRepositoryInterface;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class AuthorPresenter extends BasePresenter
{

	const NAME_MIN_LENGTH = 3;

	const PASSWORD_MIN_LENGTH = 5;

	/**
	 *
	 * @var AuthorRepositoryInterface
	 */
	protected $authorRepository;

	protected function startup()
	{
		parent::startup();

		if (!$this->getUser()->isInRole('admin')) {
			$this->redirect('Homepage:');
		}
	}

	/**
	 *
	 * @param AuthorRepositoryInterface $authorRepository
	 */
	public function injectAuthorRepository(AuthorRepositoryInterface $authorRepository)
	{
		$this->authorRepository = $authorRepository;
	}

	public function renderDefault()
	{
		$this->template->authors = $this->authorRepository->getAuthors();
	}

	/**
	 *
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function newAuthorFormSuccess(Form $form, ArrayHash $values)
	{
		try {
			$this->authorRepository->addAuthor($values->name, $values->password, $values->roles);
			$this->flashMessage('New author created.');
		} catch (UniqueConstraintViolationException $e) {
			$this->flashMessage(sprintf('Author "%s" already exists!', $values->name));
		} catch (\Exception $e) {
			$this->flashMessage('Failed to create new author.');
		}

		$this->redirect('Author:');
	}

	/**
	 *
	 * @return Form
	 */
	protected function createComponentNewAuthorForm()
	{
		$form = new Form();

		$form->addGroup('New author');
		$form->addText('name', 'Name')
			->setRequired('Enter name.')
			->addRule(Form::MIN_LENGTH, sprintf('Name has to be at least %d chars long.', self::NAME_MIN_LENGTH), self::NAME_MIN_LENGTH);
		$form->addPassword('password', 'Password')
			->setRequired('Enter password.')
			->addRule(Form::MIN_LENGTH, sprintf('Password has to be at least %d chars long.', self::PASSWORD_MIN_LENGTH), self::PASSWORD_MIN_LENGTH);
		$form->addPassword('password2', 'Password verification')
			->setRequired('Enter password verification.')
			->addRule(Form::EQUAL, 'Passwords does not match!', $form['password']);

		$roles = [
			'admin' => 'Admin'
		];
		$form->addCheckboxList('roles', 'Roles', $roles);

		$form->setMethod(Form::POST);

		$form->addSubmit('submit');

		$form->onSuccess[] = [
			$this,
			'newAuthorFormSuccess'
		];

		return $form;
	}
}

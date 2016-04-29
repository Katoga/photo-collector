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
class AuthorPresenter extends AuthedPresenter
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
			$this->flashMessage($this->translator->translate('txt.author.newAuthorCreated'));
		} catch (UniqueConstraintViolationException $e) {
			$this->flashMessage($this->translator->translate('txt.author.newAuthorDuplicite', ['name' => $values->name]));
		} catch (\Exception $e) {
			$this->flashMessage($this->translator->translate('txt.author.newAuthorFailed'));
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

		$form->addGroup($this->translator->translate('txt.author.formLabel'));
		$form->addText('name', $this->translator->translate('txt.author.nameLabel'))
			->setRequired($this->translator->translate('txt.author.nameRequired'))
			->addRule(Form::MIN_LENGTH, $this->translator->translate('txt.author.nameRule', ['min_length' => self::NAME_MIN_LENGTH]), self::NAME_MIN_LENGTH);
		$form->addPassword('password', $this->translator->translate('txt.author.passwordLabel'))
			->setRequired($this->translator->translate('txt.author.passwordRequired'))
			->addRule(Form::MIN_LENGTH, $this->translator->translate('txt.author.passwordRule', ['min_length' => self::PASSWORD_MIN_LENGTH]), self::PASSWORD_MIN_LENGTH);
		$form->addPassword('password2', $this->translator->translate('txt.author.passwordVerificationLabel'))
			->setRequired($this->translator->translate('txt.author.passwordVerificationRequired'))
			->addRule(Form::EQUAL, $this->translator->translate('txt.author.passwordVerificationRule'), $form['password']);

		$roles = [
			'admin' => 'Admin'
		];
		$form->addCheckboxList('roles', $this->translator->translate('txt.author.rolesLabel'), $roles);

		$form->setMethod(Form::POST);

		$form->addSubmit('submit');

		$form->onSuccess[] = [
			$this,
			'newAuthorFormSuccess'
		];

		return $form;
	}
}

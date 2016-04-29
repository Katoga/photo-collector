<?php
namespace App\Presenters;

use Kdyby\Translation\ITranslator;
use Nette\Application\UI\Presenter;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class BasePresenter extends Presenter
{
	/**
	 * @var ITranslator
	 */
	protected $translator;

	/**
	 * @param ITranslator $translator
	 */
	public function injectTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
	}
}

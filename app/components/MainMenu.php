<?php
namespace App\Components;

use Nette\Application\UI\Control;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class MainMenu extends Control
{

	public function render()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/mainMenu.latte');
		$template->render();
	}
}

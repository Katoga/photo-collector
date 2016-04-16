<?php
namespace App\Model;

use Nette\Utils\Strings;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class DummyEventRepository implements EventRepositoryInterface
{

	/**
	 *
	 * @see \App\Model\EventRepositoryInterface::getEvents()
	 */
	public function getEvents()
	{
		$events = [
			Strings::webalize('ciaf_2015') => 'CIAF 2015',
			Strings::webalize('silver_a_2015') => 'Silver A 1025'
		];

		return $events;
	}

	/**
	 *
	 * @see \App\Model\EventRepositoryInterface::addEvent()
	 */
	public function addEvent($name)
	{
		return 0;
	}
}

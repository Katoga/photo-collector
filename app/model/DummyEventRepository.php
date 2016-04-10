<?php
namespace App\Model;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class DummyEventRepository implements EventRepositoryInterface
{

	/**
	 *
	 * {@inheritDoc}
	 * @see \App\Model\EventRepositoryInterface::getEvents()
	 */
	public function getEvents()
	{
		$events = [
			'' => '',
			'ciaf_2015' => 'CIAF 2015',
			'silver_a_2015' => 'Silver A 1025'
		];

		return $events;
	}
}

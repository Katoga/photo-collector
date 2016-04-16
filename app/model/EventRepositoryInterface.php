<?php
namespace App\Model;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
interface EventRepositoryInterface
{
	/**
	 *
	 * @return array
	 */
	public function getEvents();

	/**
	 *
	 * @param string $name
	 * @return int ID of new event
	 */
	public function addEvent($name);
}

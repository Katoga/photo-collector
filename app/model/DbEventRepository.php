<?php
namespace App\Model;

use Nette\Utils\Strings;
use Nette\Database\Context;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class DbEventRepository implements EventRepositoryInterface
{

	const TABLE = 'events';

	/**
	 *
	 * @var Context
	 */
	protected $db;

	/**
	 *
	 * @param Context $db
	 */
	public function __construct(Context $db)
	{
		$this->db = $db;
	}

	/**
	 *
	 * @see \App\Model\EventRepositoryInterface::getEvents()
	 */
	public function getEvents()
	{
		$events = [];

		$res = $this->db->table('events')
			->select('name')
			->select('url')
			->order('name');
		foreach ($res as $row) {
			$events[$row->url] = $row->name;
		}

		return $events;
	}

	/**
	 *
	 * @see \App\Model\EventRepositoryInterface::addEvent()
	 */
	public function addEvent($name)
	{
		$data = [
			'name' => $name,
			'url' => Strings::webalize($name)
		];
		$this->db->table(self::TABLE)->insert($data);

		$eventId = $this->db->getInsertId(self::TABLE);

		return $eventId;
	}
}

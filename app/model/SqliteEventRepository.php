<?php
namespace App\Model;

use Nette\Utils\Strings;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-15
 */
class SqliteEventRepository implements EventRepositoryInterface
{

	const ERROR_HANDLER = [
		__CLASS__,
		'handleErrors'
	];

	/**
	 *
	 * @var \SQLite3
	 */
	protected $db;

	/**
	 *
	 * @param string $dbFile
	 */
	public function __construct($dbFile)
	{
		$this->db = new \SQLite3($dbFile, SQLITE3_OPEN_READWRITE);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \App\Model\EventRepositoryInterface::getEvents()
	 */
	public function getEvents()
	{
		$events = [];

		$query = "
			SELECT name,
				url
			FROM events
			ORDER BY name ASC
		";
		$res = $this->db->query($query);
		while (($row = $res->fetchArray(SQLITE3_ASSOC)) !== false) {
			$events[$row['url']] = $row['name'];
		}

		return $events;
	}

	/**
	 *
	 * @see \App\Model\EventRepositoryInterface::addEvent()
	 */
	public function addEvent($name)
	{
		$query = "
			INSERT INTO events (
				name,
				url
			)
			VALUES (
				:name,
				:url
			)
		";

		try {
			set_error_handler(self::ERROR_HANDLER);

			$statement = $this->db->prepare($query);
			$statement->bindValue(':name', $name, SQLITE3_TEXT);
			$statement->bindValue(':url', Strings::webalize($name), SQLITE3_TEXT);
			$statement->execute();
			$eventId = $this->db->lastInsertRowID();
		} catch (\RuntimeException $e) {
			throw $e;
		} finally {
			restore_error_handler();
		}

		return $eventId;
	}

	static public function handleErrors($errno, $errstr)
	{
		throw new \RuntimeException($errstr, $errno);
	}
}

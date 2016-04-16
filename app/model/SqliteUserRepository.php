<?php
namespace App\Model;

use Nette\Utils\Strings;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class SqliteUserRepository implements UserRepositoryInterface
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
	 * @see \App\Model\UserRepositoryInterface::getUsers()
	 */
	public function getUsers()
	{
		$users = [];

		$query = "
			SELECT name,
				url
			FROM users
			ORDER BY name ASC
		";
		$res = $this->db->query($query);
		while (($row = $res->fetchArray(SQLITE3_ASSOC)) !== false) {
			$users[$row['url']] = $row['name'];
		}

		return $users;
	}

	/**
	 *
	 * @see \App\Model\UserRepositoryInterface::addUser()
	 */
	public function addUser($name)
	{
		$query = "
			INSERT INTO users (
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
			$userId = $this->db->lastInsertRowID();
		} catch (\RuntimeException $e) {
			throw $e;
		} finally {
			restore_error_handler();
		}

		return $userId;
	}

	/**
	 *
	 * @param int $errno
	 * @param string $errstr
	 * @throws \RuntimeException
	 */
	static public function handleErrors($errno, $errstr)
	{
		throw new \RuntimeException($errstr, $errno);
	}
}

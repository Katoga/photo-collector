<?php
namespace App\Model;

use Nette\Utils\Strings;
use Nette\Database\Context;
use Nette\Security\Passwords;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class SqliteUserRepository implements UserRepositoryInterface
{

	const TABLE = 'users';

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
	 * @see \App\Model\UserRepositoryInterface::getUsers()
	 */
	public function getUsers()
	{
		$users = [];

		$res = $this->db->table('users')
			->select('user_id')
			->select('name')
			->select('login')
			->order('name');
		foreach ($res as $row) {
			$users[$row->login] = $row->name;
		}

		return $users;
	}

	/**
	 *
	 * @see \App\Model\UserRepositoryInterface::addUser()
	 */
	public function addUser($name, $password)
	{
		$data = [
			'name' => $name,
			'login' => Strings::webalize($name),
			'password' => Passwords::hash($password)
		];
		$this->db->table(self::TABLE)->insert($data);

		$userId = $this->db->getInsertId(self::TABLE);

		return $userId;
	}
}

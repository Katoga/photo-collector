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
			->select('roles')
			->order('name');
		foreach ($res as $row) {
			$roles = [];
			foreach (explode('|', $row->roles) as $role) {
				$role = trim($role);
				if (!empty($role)) {
					$roles[] = $role;
				}
			}
			$users[$row->user_id] = [
				'name' => $row->name,
				'login' => $row->login,
				'roles' => $roles
			];
		}

		return $users;
	}

	/**
	 *
	 * @see \App\Model\UserRepositoryInterface::addUser()
	 */
	public function addUser($name, $password, array $roles)
	{
		$data = [
			'name' => $name,
			'login' => Strings::webalize($name),
			'password' => Passwords::hash($password),
			'roles' => implode('|', $roles)
		];
		$this->db->table(self::TABLE)->insert($data);

		$userId = $this->db->getInsertId(self::TABLE);

		return $userId;
	}
}

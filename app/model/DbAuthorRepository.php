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
class DbAuthorRepository implements AuthorRepositoryInterface
{

	const TABLE = 'authors';

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
	 * @see \App\Model\AuthorRepositoryInterface::getAuthors()
	 */
	public function getAuthors()
	{
		$authors = [];

		$res = $this->db->table('authors')
			->select('author_id')
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
			$authors[$row->author_id] = [
				'name' => $row->name,
				'login' => $row->login,
				'roles' => $roles
			];
		}

		return $authors;
	}

	/**
	 *
	 * @see \App\Model\AuthorRepositoryInterface::addAuthor()
	 */
	public function addAuthor($name, $password, array $roles)
	{
		$data = [
			'name' => $name,
			'login' => Strings::webalize($name),
			'password' => Passwords::hash($password),
			'roles' => implode('|', $roles)
		];
		$this->db->table(self::TABLE)->insert($data);

		$authorId = $this->db->getInsertId(self::TABLE);

		return $authorId;
	}

	/**
	 *
	 * @see \App\Model\AuthorRepositoryInterface::changePassword()
	 */
	public function changePassword($login, $password)
	{
		$data = [
			'password' => Passwords::hash($password)
		];

		$condition = [
			'login' => $login
		];
		$rowsUpdated = $this->db->table(self::TABLE)->where($condition)->update($data);

		if ($rowsUpdated != 1) {
			throw new \RuntimeException('updated: ' . $rowsUpdated);
		}
	}
}

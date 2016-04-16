<?php
namespace App\Model;

use Nette\Database\Context;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
class SqliteAuthenticator extends Object implements IAuthenticator
{

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
	 * @see \Nette\Security\IAuthenticator::authenticate()
	 */
	public function authenticate(array $credentials)
	{
		list ($username, $password) = $credentials;

		$row = $this->db->table(SqliteUserRepository::TABLE)
			->where('login', $username)
			->fetch();

		if (!$row) {
			throw new AuthenticationException('User not found.');
		}

		if (!Passwords::verify($password, $row->password)) {
			throw new AuthenticationException('Invalid password.');
		}

		$data = [
			'name' => $row->name
		];

		return new Identity($row->login, null, $data);
	}
}

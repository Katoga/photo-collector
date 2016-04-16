<?php
namespace App\Model;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class DummyUserRepository implements UserRepositoryInterface
{

	/**
	 *
	 * @see \App\Model\UserRepositoryInterface::getUsers()
	 */
	public function getUsers()
	{
		$users = [
			'pepa' => 'Josef',
			'lojza' => 'Alois'
		];

		return $users;
	}

	/**
	 *
	 * @see \App\Model\UserRepositoryInterface::addUser()
	 */
	public function addUser($name, $password, array $roles)
	{
		return 0;
	}
}

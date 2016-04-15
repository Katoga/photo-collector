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
	 * {@inheritDoc}
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
}

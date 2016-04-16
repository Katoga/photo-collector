<?php
namespace App\Model;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
interface UserRepositoryInterface
{

	/**
	 *
	 * @return array
	 */
	public function getUsers();

	/**
	 *
	 * @param string $name
	 * @param string $password
	 * @return int ID of new user
	 */
	public function addUser($name, $password);
}

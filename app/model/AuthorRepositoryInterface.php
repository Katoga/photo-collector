<?php
namespace App\Model;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-16
 */
interface AuthorRepositoryInterface
{

	/**
	 *
	 * @return array
	 */
	public function getAuthors();

	/**
	 *
	 * @param string $name
	 * @param string $password
	 * @param array $roles
	 * @return int ID of new author
	 */
	public function addAuthor($name, $password, array $roles);
}

<?php
namespace App\Model;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class DummyAuthorRepository implements AuthorRepositoryInterface
{

	/**
	 *
	 * @see \App\Model\AuthorRepositoryInterface::getAuthors()
	 */
	public function getAuthors()
	{
		$authors = [
			'pepa' => 'Josef',
			'lojza' => 'Alois'
		];

		return $authors;
	}

	/**
	 *
	 * @see \App\Model\AuthorRepositoryInterface::addAuthor()
	 */
	public function addAuthor($name, $password, array $roles)
	{
		return 0;
	}
}

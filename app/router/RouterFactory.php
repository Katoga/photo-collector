<?php
namespace App;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

/**
 *
 * @author Katoga <katoga.cz@hotmail.com>
 * @since 2016-04-10
 */
class RouterFactory
{

	/**
	 *
	 * @return IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('view/<event>', 'View:event');
		$router[] = new Route('view/<event>/<user>', 'View:user');
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}
}

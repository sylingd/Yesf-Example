<?php
/**
 * 配置
 * 
 * @author ShuangYa
 * @package Example
 * @category Configutation
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
namespace App;

use Yesf\Http\Router;
use Yesf\Http\Dispatcher;
use App\Interceptor\ApiInterceptor;

class Configuration {
	public function setRouter(Router $router) {
		$router->get('/api/user/me', 'api.user.me');
		$router->post('/api/user/login', 'api.user.login');
		$router->post('/api/user/register', 'api.user.register');
		$router->get('/me', 'index.index.me');
		$router->get('/login', 'index.index.loginPage');
		$router->post('/login', 'index.index.login');
		$router->get('/register', 'index.index.registerPage');
		$router->post('/register', 'index.index.register');
		$router->get('/', 'index.index.index');
	}
	public function setInterceptor(Dispatcher $dispatcher, ApiInterceptor $api) {
		$dispatcher->addInterceptor('/api/**', $api);
	}
}
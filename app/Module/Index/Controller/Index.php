<?php
/**
 * 用户
 * 
 * @author ShuangYa
 * @package Example
 * @category Controller
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
namespace App\Module\Index\Controller;

use Yesf\Http\Cookie;
use Yesf\Http\Request;
use Yesf\Http\Response;
use App\Library\Utils;
use App\Model\User as UserModel;
use App\Service\Token;
use Respect\Validation\Validator;

class Index {
	private $user;
	private $token;
	public function __construct(UserModel $user, Token $token) {
		$this->user = $user;
		$this->token = $token;
	}

	public function indexAction(Request $request, Response $response) {
		$response->display('Index/Index');
	}

	public function meAction(Request $request, Response $response) {
		$token = $request->cookie['token'];
		$user = $this->token->validate($token);
		if ($user === null) {
			$response->assign('message', 'Not login');
			$response->display('Index/Notice');
			return;
		}
		$response->assign('user', $user);
		$response->display('Index/Me');
	}

	public function loginPageAction(Request $request, Response $response) {
		$response->display('Index/Login');
	}

	public function loginAction(Request $request, Response $response) {
		$user = $this->user->get(['name' => $request->post['name']]);
		if ($user === null) {
			$response->assign('message', 'User not exists');
			$response->display('Index/Notice');
			return;
		}
		if (!password_verify($request->post['password'], $user['password'])) {
			$response->assign('message', 'Wrong password');
			$response->display('Index/Notice');
			return;
		}
		$token = $this->token->create($user['id']);
		$response->cookie([
			'name' => 'token',
			'value' => $token,
			'path' => '/',
			'expire' => 7 * 24 * 3600
		]);
		unset($user['password']);
		$response->assign('message', 'Login success');
		$response->display('Index/Notice');
	}

	public function registerPageAction(Request $request, Response $response) {
		$response->display('Index/Register');
	}

	public function registerAction(Request $request, Response $response) {
		if (!preg_match('/^(\w{1,20})$/', $request->post['name'])) {
			$response->assign('message', 'Invalid username');
			$response->display('Index/Notice');
			return;
		}
		if (!Validator::email()->validate($request->post['email'])) {
			$response->assign('message', 'Invalid email');
			$response->display('Index/Notice');
			return;
		}
		$request->post['password'] = password_hash($request->post['password'], PASSWORD_DEFAULT);
		// ok
		try {
			$result = $this->user->add($request->post, ['name', 'password', 'email']);
		} catch (\Throwable $e) {
			$response->assign('message', 'Register failed');
			$response->display('Index/Notice');
			return;
		}
		$response->assign('message', 'Register success');
		$response->display('Index/Notice');
	}
}
<?php
/**
 * ApiInterceptor
 * 
 * @author ShuangYa
 * @package Example
 * @category Interceptor
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
namespace App\Interceptor;

use Yesf\Http\Request;
use Yesf\Http\Response;
use Yesf\Http\Interceptor\BeforeInterface;
use Yesf\Http\Interceptor\AfterInterface;
use App\Library\Utils;

class ApiInterceptor implements BeforeInterface, AfterInterface {
	public function before(Request $request, Response $response) {
		$response->mimeType('json');
	}
	public function after(Request $request, Response $response) {
		if ($request->status !== null) {
			if (is_int($request->status)) {
				$response->write(Utils::getResult([
					'errno' => 404,
					'error' => 'not found'
				]));
			} else {
				$response->write(Utils::getResult([
					'errno' => 500,
					'error' => 'internal server error'
				]));
			}
			return true;
		}
	}

}
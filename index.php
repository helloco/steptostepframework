<?php
/**
step to step of a framework
since :2015-9-20
死去活来法
*/
$uri = $_SERVER['REQUEST_URI'];
$url = parse_url($uri);
define('MY_APP_PATH', dirname(__FILE__));
define('MY_CONTROLLER_NAME', 'c');
define('MY_METHOD_NAME', 'm');
define('MY_CONTROLLER_DIR', MY_APP_PATH . '\controller');
define('METHO_SUFFFIX', 'Action');
$germination = new Mygermination($url);
$germination->run();


class Mygermination {
	public $controller = 'default';
	public $method = 'default';
	// 请求参数
	public $requestParameters = array();

	public function __construct($url) {

		if (!empty($url['query'])) {
			$queryStrings = explode('&', $url['query']);
			//var_dump($queryStrings);
			foreach ($queryStrings as $key => $queryString) {
				$query = explode("=", $queryString);
				if ($query[0] == MY_CONTROLLER_NAME && isset($query[1])) {
					$this->setController($query[1]);
				}
				if ($query[0] == MY_METHOD_NAME && isset($query[1])) {
					$this->setMethod($query[1]);
				}elseif ($query[0] != MY_CONTROLLER_NAME && $query[0] != MY_METHOD_NAME) {
					$this->setParameter($query[0] , $query[1]);
				}
			}
		}
	}

	private function setController($controllerName) {
		$this->controller = $controllerName;
	}
	private function setMethod($methodName) {
		$this->method = $methodName;
	}
	public function getController() {
		return $this->controller;
	}
	public function getMethod() {
		return $this->method;	
	}
	private function setParameter($name, $value) {
		$this->requestParameters[] = array(
			$name => $value,
		);
	}
	public  function getParameter() {
		return $this->requestParameters;
	}

	public function formatController () {
		$controller = $this->getController();
		return $controller . 'Controller.class.php';
	}
	public function formatAction() {
		return $this->getMethod() . METHO_SUFFFIX;
	}
	
	public function run() {
		$fileName = MY_CONTROLLER_DIR . '\\' . $this->formatController();
		require_once $fileName;
		$className = ucfirst($this->getController());
		$currenctClass = new $className;
		$actionName = $this->formatAction();
		call_user_func(array($currenctClass , $actionName));
	}
}
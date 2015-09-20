<?php
/**
step to step of a framework
since :2015-9-20
死去活来法
*/
$uri = $_SERVER['REQUEST_URI'];
$url = parse_url($uri);
define('APP_PATH' ,$url['path']);
define('CONTROLLER_NAME', 'c');
define('METHOD_NAME', 'm');

$queryStrings = explode('&', $url['query']);
// var_dump($queryStrings);exit;

$germination = new Germination($queryStrings);
$germination->run();


class Germination {
	public $controller;
	public $method;

	public $requestParameters = array();

	public function __construct($queryStrings) {
		foreach ($queryStrings as $key => $queryString) {
			$query = explode("=", $queryString);
			if ($query[0] == CONTROLLER_NAME && isset($query[1])) {
				$this->setController($query[1]);
			}
			if ($query[0] == METHOD_NAME && isset($query[1])) {
				$this->setMethod($query[1]);
			}elseif ($query[0] != CONTROLLER_NAME && $query[0] != METHOD_NAME) {
				$this->setParameter($query[0] , $query[1]);
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

	public function run() {
		echo "<pre>";
		var_dump($this->getController() , '-' , $this->getMethod(),'+',$this->getParameter());
	}
}
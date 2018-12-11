<?php
//基础模型类
namespace Core;
class Model {
	protected $mypdo;
	public function __construct() {
		$this->initMyPDO();
	}
	//初始化MyPDO
	private function initMyPDO(){
		$this->mypdo=MyPDO::getInstance($GLOBALS['config']['database']);
	}
}
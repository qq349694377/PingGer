<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Charts extends Controller
{
	public function charts1()
	{
		return $this->fetch();
	}
}
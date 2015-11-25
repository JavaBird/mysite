<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_app_func('admin');

/**
* 
*/
class article extends admin
{
	
	function __construct()
	{
		parent::__construct();
		$this->db = pc_base::load_model('admin_model');
	}

	

      public function getArticleList()
     {
     	include $this -> load_tpl('article');
     }

    

}

?>
<?php
class Create
{
	function __construct(&$base)
	{
		$this->base = $base;
		$this->db = $base->mydb();
	}
}

?>
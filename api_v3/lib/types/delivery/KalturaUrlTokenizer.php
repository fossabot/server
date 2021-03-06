<?php
/**
 * @package api
 * @subpackage objects
 */
class KalturaUrlTokenizer extends KalturaObject
{

	/**
	 * Window
	 *
	 * @var int
	 */
	public $window;
	
	/**
	 * key
	 *
	 * @var string
	 */
	public $key;
	
	private static $map_between_objects = array
	(
			"key",
			"window",
	);
	
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}

}

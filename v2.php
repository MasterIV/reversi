<?php

class V2 {
	public $x;
	public $y;
	
	function __construct($x, $y) {
		$this->x = $x;
		$this->y = $y;
	}
	
	function add(V2 $other) {
		$this->x += $other->x;
		$this->y += $other->y;
	}
	
	function sum(V2 $other) {
		return new V2(
			$this->x + $other->x,
			$this->y + $other->y
		);
	}
	
	function valid() {
		return $this->x >= 0 
			&& $this->y >= 0
			&& $this->x < 8
			&& $this->y < 8;
	}
}

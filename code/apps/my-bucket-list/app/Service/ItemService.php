<?php namespace App\Service;

use App\State;
use App\Item;
use Carbon\Carbon;

class ItemService {

	public static function createNewItem(string $content): Item {
		
		return Item::create(
			[
				'content'      => $content,
				'complete_by' => Carbon::today()->addYear(),
				'state_id'     => State::of('new')->id 
			]);	
	}
}
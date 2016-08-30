<?php namespace App\Service;

use P;
use App\State;
use App\Item;
use Carbon\Carbon;
use App\Util\Tuple;

class ItemService {

	/**
	 * Create a new item with the next content
	 * @return Item
	 */
	public static function createNewItem(string $content): Item {
		
		return Item::create(
			[
				'content'     => $content,
				'complete_by' => Carbon::today()->addYear(),
				'state_id'    => State::of('new')->id 
			]);	
	}

	/**
	 * Get counts of items that are new and which ones are completed
	 * @return Tuple(int, int, int)
	 */
	public static function countNewAndDoneItems(): Tuple {
		
        $countNewAndPast = P::partition(function ($item) {
        	$name = $item->state->getShortname();
        	return $name === 'expired' || $name === 'completed';
        }, Item::all());

        list($done, $new) = $countNewAndPast;
        $counts = Tuple::create('integer', 'integer');
        return $counts(count($done), count($new));        
	}
}
<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use function Functional\memoize;

class State extends Model {
	
	protected $table = 'states';    
    protected $fillable = ['short_name', 'display_name'];

    public function getShortName(): string {
        return $this->shortName;
    }
    
    public function setShortName(string $shortName): State {
        $this->shortName = $shortName;
        return $this;
    }

    public function getDisplayName(): string {
        return $this->displayName;
    }
    
    public function setDisplayName(string $displayName): State {
        $this->displayName = $displayName;
        return $this;
    }

    public static function of(string $shortName): State {
    	return memoize(function ($n) { 			
    		return static::query()->where('short_name', '=', $n)->first();	
    	}, [$shortName]);
    }
}
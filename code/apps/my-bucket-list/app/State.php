<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use function Functional\memoize;

class State extends Model {
	
	protected $table = 'states';    
    protected $fillable = ['short_name', 'display_name'];

    public function getShortName(): string {
        return $this->short_name;
    }
    
    public function setShortName(string $shortName): State {
        $this->short_name = $shortName;
        return $this;
    }

    public function getDisplayName(): string {
        return $this->display_name;
    }
    
    public function setDisplayName(string $displayName): State {
        $this->display_name = $displayName;
        return $this;
    }

    public static function of(string $shortName): State {
    	return memoize(function ($n) { 			
    		return static::query()->where('short_name', '=', $n)->first();	
    	}, [$shortName]);
    }
}
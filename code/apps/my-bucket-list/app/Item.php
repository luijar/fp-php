<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\State;

class Item extends Model {
    
    protected $table = 'items';    
    protected $fillable = ['content', 'complete_by', 'state_id'];

    public function getContent(): string {
    	return $this->content;
    }

    public function setContent(string $content): Item {
    	$this->content = $content;
    	return $this;
    }

    public function getCompletedBy() {
    	return $this->complete_by;
    }

    public function setCompletedBy(\DateTime $completeBy): Item {
    	$this->complete_by = $completeBy;
    	return $this;
    } 

    public function getStateId() {
        return $this->state_id;
    }

    public function setStateId(int $id): Item {
        $this->state_id = $id;
        return $this;
    }

    public function state() {
    	return $this->hasOne(State::class);
    }

    public static function instance() {
    	return new Item();
    }
}
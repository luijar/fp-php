<?php namespace App;

use PhpOption\Option as Nullable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function state(): HasOne {
    	return $this->hasOne(State::class, 'id', 'state_id');
    }

    public static function findNullable(int $id): Nullable {
        return Nullable::fromValue(parent::find($id));
    }

    public static function instance() {
    	return new Item();
    }
}
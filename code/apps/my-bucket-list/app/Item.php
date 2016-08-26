<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    
    protected $table = 'items';    
    protected $fillable = ['content'];

    public function getContent(): string {
    	return $this->content;
    } 
}

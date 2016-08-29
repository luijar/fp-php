<?php namespace App\Util;

/**
 * @see http://eddmann.com/posts/tuples-in-php/
 */
class Tuple extends \SplFixedArray {

    protected $prototype;

    public function __construct(array $prototype, array $data = []) {
        parent::__construct(count($prototype));

        $this->prototype = $prototype;

        foreach ($data as $offset => $value) {
            $this->offsetSet($offset, $value);
        }
    }

    public function offsetSet($offset, $value) {
        if (!$this->isValid($offset, $value)) {
            throw new \RuntimeException();
        }

        return parent::offsetSet($offset, $value);
    }

    protected function isValid($offset, $value) {
        $type = $this->prototype[$offset];

        if ($type === 'mixed' || gettype($value) === $type || $value instanceof $type) {
            return true;
        }

        return false;
    }

    public function __toString() {
        return 'Tuple (' . implode(', ', $this->toArray()) . ')';
    }

    public static function create(/* $prototype... */) {
        $prototype = func_get_args();

        return function() use ($prototype) {
            return new static($prototype, func_get_args());
        };
    }
}
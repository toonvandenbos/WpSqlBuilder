<?php

namespace WpSqlBuilder\Components;

class Limit
{
      public $offset;
      public $count;

      public function __construct($offset, $count)
      {
            $this->offset = $offset;
            $this->count = $count;
      }

      public function __toString()
      {
            if($this->offset) return $this->offset . ', ' . $this->count;
            return (string) $this->count;
      }
}
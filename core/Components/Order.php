<?php

namespace WpSqlBuilder\Components;

class Order
{
      public $object;
      public $flag;

      public function __construct($object, $flag)
      {
            $this->object = $object;
            $this->flag = $this->getFlag($flag);
      }

      public function __toString()
      {
            return $this->object . ($this->flag ? ' ' . $this->flag : '');
      }

      protected function getFlag($f)
      {
            if($f === true) return 'DESC';
            if(is_string($f) && strtoupper(trim($f)) == 'DESC') return 'DESC';
            return false;
      }
}
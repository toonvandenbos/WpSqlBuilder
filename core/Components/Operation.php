<?php

namespace WpSqlBuilder\Components;

/**
* Dynamic query container
*/
class Operation
{
      public $type;
      protected $grammar = [];
      protected $options = [];

      public function __construct($options = [])
      {
            $this->options = $options;
      }

      public function getBase()
      {
            return strtoupper($this->type);
      }

      public function getGrammar()
      {
            return $this->grammar;
      }
}
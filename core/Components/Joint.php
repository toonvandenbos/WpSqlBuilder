<?php

namespace WpSqlBuilder\Components;

class Joint
{
      const SYNTAX = 'JOIN';

      public $table;
      public $condition;

      public function __construct($table, $condition)
      {
            $this->table = $table;
            $this->condition = $condition;
      }

      public function __toString()
      {
            return static::SYNTAX . ' ' . $this->table . ' ON ' . $this->condition;
      }
}
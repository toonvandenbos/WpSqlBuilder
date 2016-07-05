<?php

namespace WpSqlBuilder\Components;

class Joint
{
      const SYNTAX = 'JOIN';

      public $table;
      public $left;
      public $right;

      public function __construct($table, $left, $right)
      {
            $this->table = $table;
            $this->left = $left;
            $this->right = $right;
      }

      public function __toString()
      {
            return static::SYNTAX . ' ' . $this->table . ' ON ' . $this->left . '=' . $this->right;
      }
}
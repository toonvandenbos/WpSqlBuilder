<?php

namespace WpSqlBuilder\Components;

class Joint
{
      public $type = 'join';
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
            return $this->getTypeString() . ' ' . $this->table . ' ON ' . $this->left . '=' . $this->right;
      }

      protected function getTypeString()
      {
            return 'JOIN';
      }
}
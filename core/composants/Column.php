<?php

namespace WpSqlBuilder\Composants;

class Column
{
      public $name;
      public $alias;
      public $table;

      public function __construct($name, $alias, $table)
      {
            $this->name = $name;
            $this->alias = $alias;
            $this->table = $table;
      }

      public function __toString()
      {
            return (strlen($this->table->alias) ? $this->table->alias : $this->table->name) . '.' . $this->name;
      }

      public function toSelect()
      {
            $s = $this->__toString();
            if($this->alias) $s .= ' AS ' . $this->alias;
            return $s;
      }

}
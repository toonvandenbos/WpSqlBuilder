<?php

namespace WpSqlBuilder\Components\Conditions;

use WpSqlBuilder\Components\Column;

class Simple
{
      public $chain;
      public $column;
      public $condition;
      public $original;
      public $value;
      public $plain;

      public function __construct($chain, $args, &$query)
      {
            $this->chain = strtoupper($chain);
            $this->setArguments($args, $query);
      }

      public function __toString()
      {
            if($this->plain) return $this->plain;
            return $this->column . $this->condition . $this->value;
      }

      protected function setArguments($a, $query)
      {
            switch (count($a)) {
                  case 1:
                        $this->plain = trim($a[0]);
                  case 2:
                        $this->column = $this->getColumn($a[0], $query);
                        $this->condition = '=';
                        $this->original = $a[1];
                        $this->value = $this->getValue('=');
                        break;
                  case 3:
                        $this->column = $this->getColumn($a[0], $query);
                        $this->condition = $this->getCondition($a[1]);
                        $this->original = $a[2];
                        $this->value = $this->getValue($a[1]);
                        break;
                  default:
                        throw new Exception('WpSqlBuilder - "where", "andWhere", "orWhere" functions only accept 2 or 3 arguments, but ' . count($a) . ' were passed.', 1);
                        break;
            }
      }

      protected function getColumn($val, $query)
      {
            if(is_string($val)) return $query->getConditionColumn($val);
            elseif(is_object($val) && $val instanceof Column) return $val;
            else throw new \Exception('WpSqlBuilder - Condition needs first argument to be a valid column (string or class).', 1);
      }

      protected function getCondition($condition)
      {
            if (in_array($condition, ['=','<=>','<>','!=','>','>=','<','<='])) return $condition;
            return ' ' . strtoupper($condition) . ' ';
      }

      protected function getValue($condition)
      {
            switch (strtoupper($condition)) {
                  case 'NOT BETWEEN':
                  case 'BETWEEN':
                        return $this->getBetweenValue();
                        break;
                  case 'NOT IN':
                  case 'IN':
                        return $this->getInValue();
                        break;
                  case 'NOT EXISTS':
                  case 'EXISTS':
                        return $this->getExistsValue();
                        break;
                  default:
                        return $this->getParsedValue($this->original);
                        break;
            }
      }

      protected function getParsedValue($object)
      {
            switch (gettype($object)) {
                  case 'string':
                        return $this->evalStringValue($object);
                        break;
                  case 'NULL':
                        return 'NULL';
                        break;
                  case 'boolean':
                        return $this->evalBoolValue($object);
                        break;
                  default:
                        return $object;
                        break;
            }
      }

      protected function evalStringValue($s)
      {
            if(is_numeric($s)) return $s;
            $su = strtoupper($s);
            if($su == 'NOT NULL' || $su == 'NULL') return $su;
            return '\'' . $s . '\'';
      }

      protected function evalBoolValue($b)
      {
            if($b) return 'NOT NULL';
            return 'NULL';
      }

      protected function getBetweenValue()
      {
            if(is_string($this->original) && strpos($this->original, ' AND ') > 0) return $this->original;
            if(is_array($this->original) && count($this->original) == 2) return $this->original[0] . ' AND ' . $this->original[1];
            throw new Exception('WpSqlBuilder - (Not) Between condition expects value to be string containing "AND" or an array with exactly 2 items', 1);
      }

      protected function getInValue()
      {
            if(is_array($this->original)){
                  $s = '(';
                  foreach ($this->original as $i => $object) {
                        if($i) $s .= ',';
                        $s .= $this->getParsedValue($object);
                  }
                  $s .= ')';
                  return $s;
            }
            throw new Exception('WpSqlBuilder - (Not) In condition expects value to be array.', 1);
      }

      protected function getExistsValue()
      {
            if($this->original instanceof WpSqlBuilder\Query) return '(' . $this->original->generate() . ')';
            throw new Exception('WpSqlBuilder - (Not) Exists condition expects value to be an instance of WpSqlBuilder\Query Class.', 1);
      }

}
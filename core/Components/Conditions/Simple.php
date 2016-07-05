<?php

namespace WpSqlBuilder\Components\Conditions;

class Simple
{
      public $chain;
      public $column;
      public $condition;
      public $original;
      public $value;

      public function __construct($chain, $args, &$query)
      {
            $this->chain = strtoupper($chain);
            $this->setArguments($args, $query);
      }

      public function __toString()
      {
            return $this->column . $this->condition . $this->value;
      }

      protected function setArguments($a, $query)
      {
            switch (count($a)) {
                  case 2:
                        $this->column = $query->getConditionColumn($a[0]);
                        $this->condition = '=';
                        $this->original = $a[1];
                        $this->value = $this->getValue('=');
                        break;
                  case 3:
                        $this->column = $query->getConditionColumn($a[0]);
                        $this->condition = $this->getCondition($a[1]);
                        $this->original = $a[2];
                        $this->value = $this->getValue($a[1]);
                        break;
                  default:
                        throw new Exception('WpSqlBuilder - "where", "andWhere", "orWhere" functions only accept 2 or 3 arguments, but ' . count($a) . ' were passed.', 1);
                        break;
            }
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
                        return $this->getDefaultValue();
                        break;
            }
      }

      protected function getDefaultValue()
      {
            switch (gettype($this->original)) {
                  case 'string':
                        return $this->evalStringValue($this->original);
                        break;
                  case 'NULL':
                        return 'NULL';
                        break;
                  case 'boolean':
                        return $this->evalBoolValue($this->original);
                        break;
                  default:
                        return $this->original;
                        break;
            }
      }

      protected function evalStringValue($s)
      {
            $su = strtoupper($s);
            if($su == 'NOT NULL' || $su == 'NULL') return $su;
            return '\'' . $this->original . '\'';
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
            if(is_array($this->original)) return '(' . implode(',', $this->original) . ')';
            throw new Exception('WpSqlBuilder - (Not) In condition expects value to be array.', 1);
      }

      protected function getExistsValue()
      {
            if($this->original instanceof WpSqlBuilder\Query) return '(' . $this->original->generate() . ')';
            throw new Exception('WpSqlBuilder - (Not) Exists condition expects value to be an instance of WpSqlBuilder\Query Class.', 1);
      }

}
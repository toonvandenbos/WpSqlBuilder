<?php

namespace WpSqlBuilder\Components\Conditions;

class Complex
{
      public $chain;
      public $conditions = [];
      protected $query;

      public function __construct($chain, &$query)
      {
            $this->chain = strtoupper($chain);
            $this->query = $query;
      }

      public function __toString()
      {
            $s = '(';
            foreach ($this->conditions as $i => $condition) {
                  if($i) $s .= ' ' . $condition->chain . ' ';
                  $s .= $condition;
            }
            $s .= ')';
            return $s;
      }

      /**
       * Adds a where condition to the query. Can be called multiple times
       * @param  string $column
       * @param  (optional) string $condition
       * @param  mixed $value
       * @return object $this
       */

      public function where(...$args)
      {
            $this->query->addCondition('and', $args, $this->conditions);
            return $this;
      }


      /**
       * Alias of "where".
       * @param  string $column
       * @param  (optional) string $condition
       * @param  mixed $value
       * @return object $this
       */

      public function andWhere(...$args)
      {
            return call_user_func_array([$this, 'where'], $args);
      }


      /**
       * chains an "OR WHERE" condition to the query. Can be called multiple times, but should not be called as first condition.
       * @param  string $column
       * @param  (optional) string $condition
       * @param  mixed $value
       * @return object $this
       */

      public function orWhere(...$args)
      {
            $this->query->addCondition('or', $args, $this->conditions);
            return $this;
      }


      /**
       * Adds a complex where condition to the query.
       * @return WpSqlBuilder\Components\Conditions\Complex
       */

      public function whereComplex()
      {
            return $this->query->addComplexCondition('and', $this->conditions);
      }


      /**
       * Alias of "whereComplex".
       * @return WpSqlBuilder\Components\Conditions\Complex
       */

      public function andWhereComplex()
      {
            return $this->whereComplex();
      }


      /**
       * chains an "OR WHERE (...)" condition to the query. Should not be called as first condition.
       * @return WpSqlBuilder\Components\Conditions\Complex
       */

      public function orWhereComplex()
      {
            return $this->query->addComplexCondition('or', $this->conditions);
      }

}
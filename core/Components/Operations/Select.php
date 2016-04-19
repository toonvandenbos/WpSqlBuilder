<?php

namespace WpSqlBuilder\Components\Operations;

use WpSqlBuilder\Components\Operation;

class Select extends Operation
{
      public $type = 'select';
      protected $grammar = [
                  'getColumns' => ['columns'],
                  'getFrom' => ['base'],
                  'getJoints' => ['joints'],
                  'getWhere' => ['conditions'],
                  'getGroupBy' => ['groupBy'],
                  'getLimit' => ['limit']
            ];

      public function getBase()
      {
            $s = parent::getBase();
            if($this->options['isDistinct']) $s .= ' DISTINCT';
            return $s;
      }
}
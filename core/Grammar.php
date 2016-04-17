<?php

namespace WpSqlBuilder;

class Grammar
{

      public static function getColumns($cols)
      {
            $s = '';
            foreach($cols as $i => $column){
                  $s .= $i ? ', ' : ' ';
                  $s .= $column->toSelect();
            }
            return $s;
      }

      public static function getFrom($base)
      {
            return ' FROM ' . $base;
      }

      public static function getJoints($joints)
      {
            $s = '';
            foreach($joints as $i => $joint){
                  $s .= ' ' . $joint;
            }
            return $s;
      }

      public static function getWhere($where)
      {
            $s = '';
            if(count($where)){
                  $s .= ' WHERE ';
                  foreach ($where as $i => $condition) {
                        $s .= self::getCondition($condition, $i ? false : true);
                  }
            }
            return $s;
      }

      public static function getCondition($condition, $isFirst)
      {
            $s = $isFirst ? '' : ' ' . $condition->chain . ' ';
            if(get_class($condition) == 'WpSqlBuilder\Composants\Conditions\Complex'){
                  $s .= '(';
                  foreach ($condition->conditions as $i => $sub) {
                        $s .= self::getCondition($sub, $i ? false : true);
                  }
                  $s .= ')';
            }
            else{
                  $s .= $condition;
            }
            return $s;
      }

      public static function getGroupBy($groupBy)
      {
            $s = '';
            if($groupBy) $s .= ' GROUP BY ' . $groupBy;
            return $s;
      }

}
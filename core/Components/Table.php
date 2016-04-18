<?php

namespace WpSqlBuilder\Components;

class Table
{
      public $name;
      public $basename;
      public $alias;
      public $isWp;
      public $isRoot;
      public $isDefinedAlias;

      public function __construct($str, $isRoot)
      {
            $a = explode('.', $str);
            $this->isDefinedAlias = isset($a[1]) && strlen($a[1]) ? true : false;
            $this->isRoot = $this->isDefinedAlias ? false : $isRoot;
            $this->isWp = $this->checkWpTable($a[0]);
            $this->basename = $this->isWp ? $this->getBaseName($a[0]) : $a[0];
            $this->name = $this->getFullName();
            $this->alias = $this->isDefinedAlias ? '_' . $a[1] : $this->getShortName();
      }

      public function __toString()
      {
            $s = $this->name;
            if(strlen($this->alias)) $s .= ' ' . $this->alias;
            return $s;
      }

      public function setAlias($str, $isAuto = true)
      {
            if(!$isAuto) $this->alias = $str;
            else $this->alias = $this->getShortName() . $str;
      }

      protected function checkWpTable($str)
      {
            global $wpdb;
            return in_array($this->getBaseName($str), array_merge($wpdb->tables, ['users','usermeta']));
      }

      protected function getBaseName($str)
      {
            global $wpdb;
            if(strpos($str, $wpdb->prefix) === 0) return substr($str, strlen($wpdb->prefix));
            return $str;
      }

      protected function getFullName()
      {
            global $wpdb;
            if($this->isWp) return $wpdb->prefix . $this->basename;
            return $this->basename;
      }

      protected function getShortName()
      {
            if($this->isWp) return $this->getWpShortName();
            return substr( md5($this->basename), -4);
      }

      protected function getWpShortName()
      {
            switch ($this->basename) {
                  case 'commentmeta': return 'cm'; break;
                  case 'comments': return 'c'; break;
                  case 'links': return 'l'; break;
                  case 'options': return 'o'; break;
                  case 'postmeta': return 'pm'; break;
                  case 'posts': return 'p'; break;
                  case 'termmeta': return 'tm'; break;
                  case 'terms': return 't'; break;
                  case 'term_relationships': return 'tr'; break;
                  case 'term_taxonomy': return 'tt'; break;
                  case 'usermeta': return 'um'; break;
                  case 'users': return 'u'; break;
            }
      }

}
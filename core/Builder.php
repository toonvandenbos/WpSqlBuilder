<?php

namespace WpSqlBuilder;

/**
* Main Controller Class
*/
class Builder
{

      public static $version = '0.0.1';

      public static function __callStatic($method, $args)
      {
            array_unshift($args,$method);
            return forward_static_call_array('self::posts', $args);
      }

      public static function select(...$args)
      {
            if(self::checkDbConnector()) {
                  $query = new Query();
                  return call_user_func_array([$query, 'select'], $args);
            }
      }

      public static function posts($postType = 'post', ...$args)
      {
            array_unshift($args,'posts');
            $query = forward_static_call_array('self::select', $args);
            $query->where('posts.post_type', $postType);
            return $query;
      }

      public static function comments(...$args)
      {
            array_unshift($args,'comments');
            return forward_static_call_array('self::select', $args);
      }

      public static function links(...$args)
      {
            array_unshift($args,'links');
            return forward_static_call_array('self::select', $args);
      }

      public static function options(...$args)
      {
            array_unshift($args,'options');
            return forward_static_call_array('self::select', $args);
      }

      public static function terms(...$args)
      {
            array_unshift($args,'terms');
            return forward_static_call_array('self::select', $args);
      }

      public static function users(...$args)
      {
            array_unshift($args,'users');
            return forward_static_call_array('self::select', $args);
      }

      protected static function checkDbConnector()
      {
            global $wpdb;
            if(!is_object($wpdb)) throw new \Exception("WpSqlBuilder - No Wordpress database object was found.", 1);
            return true;
      }

}
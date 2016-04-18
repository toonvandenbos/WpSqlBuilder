# Wordpress SQL Builder

*Version 0.0.1*

This simple PHP library allows you to develop more efficiently and more easily with WordPress.

I created this library because we had serious server-side performance issues on a WordPress project, back at [WhiteCube](http://www.whitecube.be/), the digital agency where I work. We were building an archive template, with some extra fields on each post, using ACF. It turned out the page had to make over **300 SQL requests** in order to render the posts list completely. Using this library, we were able to reduce the amount of requests to **one single request**.

## Install

Download the library and include it by `require`-ing the autoload file:

```php
require_once('WpSqlBuilder\autoload.php');
```

Now you can use it where you want by `use`-ing the main class:

```php
use WpSqlBuilder\Builder as WpSqlBuilder;
```

## Retrieving data

There are several ways you can perform a `SELECT` query, depending on the flexibility you want to have.

In most cases, you'll simply want to get some posts from the database:

```php
use WpSqlBuilder\Builder as WpSqlBuilder;

$posts = WpSqlBuilder::posts()->get();
```

You could also specify a **custom post-type**:

```php
use WpSqlBuilder\Builder as WpSqlBuilder;

$planes = WpSqlBuilder::posts('plane')->get();

// Or, even better:

$planes = WpSqlBuilder::plane()->get();

// Only get a few fields:

$fruits = WpSqlBuilder::fruit(['ID', 'post_title' => 'name', 'post_name' => 'slug'])->get();

// perform a DISTINCT query:

$projects = WpSqlBuilder::project(['post_title' => 'title'], true)->get();
```

It is possible to select from other WordPress tables directly with these functions:

```php
use WpSqlBuilder\Builder as WpSqlBuilder;

$comments = WpSqlBuilder::comments()->get();
$links = WpSqlBuilder::links()->get();
$options = WpSqlBuilder::options()->get();
$terms = WpSqlBuilder::terms()->get();
$users = WpSqlBuilder::users()->get();
```

Or from any table you want with:

```php
use WpSqlBuilder\Builder as WpSqlBuilder;

$custom = WpSqlBuilder::select('my_custom_table')->get();
$customTitles = WpSqlBuilder::select('my_custom_table', ['id','title'])->get();
$customDistinctNames = WpSqlBuilder::select('my_custom_table', ['last_name' => 'name'], true)->get();
```

## Chaining

The SQL query is built once you call `get()`. Before that, you can chain all of the following functions, without any specific order. This means you can build very complex queries in a very flexible way.

```php
use WpSqlBuilder\Builder as WpSqlBuilder;

$query = WpSqlBuilder::posts('posts', ['post_title' => 'title', 'post_content' => 'content'])->where('ID','in',[12,25,34,57])->where('post_status','publish')->groupBy('ID');

if( isset($_POST['add_term']) ){
	// when using a dot ('.') separator in a table name, you can create an alias for the table, and use it everywhere afterwards.
	$query->join( 'term_relationships.tr', 'posts.ID', 'tr.object_id' )
    	  ->join( 'term_taxonomy.tt', 'tr.term_taxonomy_id', 'tt.term_taxonomy_id' )
          ->join( 'terms.t', 'tt.term_id', 't.term_id' )
          ->select( 't', ['term_id' => 'category_id', 'name' => 'category_name', 'slug' => 'category_slug'] )
		  ->whereComplex()->where('tt.taxonomy','category')->where('t.slug','like', $_POST['add_term'] .'%');
}

$posts = $query->get();
```

**Coming soon: description of all available methods.** Or, you could take a look at the source code if you want to know them now.

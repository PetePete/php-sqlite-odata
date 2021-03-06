<?php

  error_reporting(E_ALL);
  ini_set( 'display_errors','1'); 

  require 'Config.class.php';
  require 'AltoRouter.php';
  require 'Controller.class.php';

  
  $router = new AltoRouter();
  $router->setBasePath(Config::get_subdir());
  
  $controller = new Controller(Config::get_host(), Config::get_subdir(), Config::get_model_name(), Config::get_database_path());
  
  $router->map( 'GET', '/', function() use ($controller) { $controller->service_description(); });
  $router->map( 'GET', '/[\$metadata:cmd]', function() use ($controller) { $controller->service_metadata(); });
  $router->map( 'GET', '/[a:collection]', function($collection, $query_string_parameters = array()) use ($controller) { $controller->serve_collection($collection, $query_string_parameters); });
  $router->map( 'GET', '/[a:collection]/', function($collection, $query_string_parameters = array()) use ($controller) { $controller->serve_collection($collection, $query_string_parameters); });
  $router->map( 'GET', '/[a:collection]\([a:id]\)', function($collection, $id) use ($controller) { $controller->serve_entry($collection, $id); });
  $router->map( 'GET', '/[a:collection]/[\$count:count]', function($collection) use ($controller) { $controller->count_collection($collection); });
  
  $router->map( 'PUT', '/[a:collection]\([a:id]\)', function($collection, $id) use ($controller) { $controller->update_entry($collection, $id); });
  $router->map( 'POST', '/[a:collection]', function($collection) use ($controller) { $controller->create_entry($collection); });
  $router->map( 'POST', '/[a:collection]/', function($collection) use ($controller) { $controller->create_entry($collection); });
  $router->map( 'DELETE', '/[a:collection]\([a:id]\)', function($collection, $id) use ($controller) { $controller->delete_entry($collection, $id); });
  
  $match = $router->match();
  
  if( $match && is_callable( $match['target'] ) ) {
      call_user_func_array( $match['target'], $match['params'] ); 
  }
?>

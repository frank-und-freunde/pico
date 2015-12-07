<?php

/**
 * pico framework
 *
 * @method Pico get($route, callable $controller)
 * @method Pico post($route, callable $controller)
 * @method Pico put($route, callable $controller)
 * @method Pico delete($route, callable $controller)
 * @method Pico any($route, callable $controller)
 */
class Pico {
  function __call($verb, $controller) {
    $this->{($verb == 'any' ? '' : $verb) . $controller[0]} = $controller[1];
    return $this;
  }

  function run() {
    $path = array_key_exists('PATH_INFO', $_SERVER) ? $_SERVER['PATH_INFO'] : '/';
    $requested = strtolower($_SERVER['REQUEST_METHOD']).$path;
    foreach ($this as $route => $controller) {
      if (preg_match('~'.$route.'~i', $requested, $params)) {
        die(call_user_func_array($controller, array_slice($params, 1)));
      }
    }
    header('HTTP/1.0 404 Not Found');
    die("<h1>404 Not Found</h1>\nThe page that you have requested could not be found.");
  }
}
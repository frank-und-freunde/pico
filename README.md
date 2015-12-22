# pico


    require_once 'pico.php';
    
    $app = new Pico;
    
    $app->get('/', function() {
      return 'hello world!';
    });
    
    $app->run();



pico is a fun project written in php and for developers who know what they do.
!!! use it at your own risk !!! :-)


## features + examples

### ... uses regex for routing


    $app->get('/profile/(\d+)', function($id) {
      return "profile #$id";
    });
    
    $app->get('/([a-zA-Z]+)?', function($name = 'world') {
      return "hello $name!";
    });

### ... supports all http verbs

    $app->get('/user/(\d+)', function() { /* ... */ });
    $app->put('/user/(\d+)', function() { /* ... */ });
    $app->post('/users', function() { /* ... */ });
    $app->delete('/user/(\d+)', function() { /* ... */ });

and anything that the client/browser sends as request method, for instance:

    $app->foo('/bar', function() {
      return 'yes';
    });


    $ curl --insecure -X FOO https://localhost:8080/app.php/bar
    yes

### ... is fat free and comes without any helper


    function redirect($url, $statusCode = 303, $message = null) {
      header('Location: ' . $url, true, $statusCode); die($message);
    };
    
    $app->get('/', function() {
      redirect('/foo');
    });

### ... debug handling


if (php_sapi_name() == 'cli-server') {
  ini_set('display', true);
  error_reporting(-1);
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

### ... db connections


    function connectToDb() {
      $host = "localhost";
      $db = $username = $password = "pico";
    
      $db = new mysqli($host, $username, $password, $db);
    
      if ($db->connect_errno) {
        die("failed to connect to db: (" . $db->connect_errno . ") " . $db->connect_error);
      }
    
      return $db;
    }
    
    // app initialization
    
    $app->get('/(a-zA-Z0-9){3,}', function($username) {
      $db = connectToDb();
    
      if ($stmt = $db->prepare("SELECT name, email FROM be_sessions WHERE username = ? LIMIT 1")) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($name, $email);
        $stmt->fetch();
    
        $response = "$name <$email>";
    
        $stmt->close();
      } else {
        $response = 'unknown user';
      }
    
      return $response;
    });
    
    // other routes
    

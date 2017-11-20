<?php


//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);


//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage {
    public static function autoload($class) {
        //you can put any file name or directory here
        include $class . '.php';
    }
}

spl_autoload_register(array('Manage', 'autoload'));

//instantiate the program object
//$obj = new main();





//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);
define('DATABASE', 'dt36');
define('USERNAME', 'dt36');
define('PASSWORD', 'f3hxZqRK');
define('CONNECTION', 'sql2.njit.edu');
class dbConn{



protected static $db;

private function __construct() {
try {
// assign PDO object to db variable
self::$db = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );
self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $e) {
//Output error - would normally log this to error file rather than output to user.
echo "Connection Error: " . $e->getMessage();
}
}
// get connection function. Static method - accessible without instantiation
public static function getConnection() {
//Guarantees single instance, if no connection object exists then create one.
if (!self::$db) {
//new connection object.
new dbConn();
}
//return connection.
return self::$db;
    }
}
class collection {
static public function create() {
$model = new static::$modelName;
return $model;
}
static public function findAll() {
$db = dbConn::getConnection();
$tableName = get_called_class();
$sql = 'SELECT * FROM ' . $tableName;
$statement = $db->prepare($sql);
$statement->execute();
$class = static::$modelName;
$statement->setFetchMode(PDO::FETCH_CLASS, $class);

$recordsSet =  $statement->fetchAll();
return $recordsSet;
}
static public function findOne($id) {
$db = dbConn::getConnection();
$tableName = get_called_class();
$sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
$statement = $db->prepare($sql);
$statement->execute();
$class = static::$modelName;
$statement->setFetchMode(PDO::FETCH_CLASS, $class);
$recordsSet =  $statement->fetchAll();
return $recordsSet[0];
}

    public function delete($id) {
        $db = dbConn::getConnection();
        $tablename = get_called_class();
        $sql = 'DELETE' . $tablename . 'WHERE id=' . $this->id;
        $statement = $db->prepare($sql);
        $statement->execute();
    }



}
class accounts extends collection {
protected static $modelName = 'account';
}
class todos extends collection {
protected static $modelName = 'todo';
}
class model {
protected $tableName;
public function save()
{
if ($this->id = '') {
$sql = $this->insert();
} else {
$sql = $this->update();
}
$db = dbConn::getConnection();
$statement = $db->prepare($sql);
$statement->execute();
$tableName = get_called_class();
$array = get_object_vars($this);
$columnString = implode(',', $array);
$valueString = ":".implode(',:', $array);
// echo "INSERT INTO $tableName (" . $columnString . ") VALUES (" . $valueString . ")</br>";
echo 'I just saved record: ' . $this->id;
}
private function insert() {
$sql = '';
return $sql;
}
private function update() {
$sql = '';
return $sql;
echo 'updated record' . $this->id;
}

}
class account extends model {
}
class todo extends model {
public $id;
public $owneremail;
public $ownerid;
public $createddate;
public $duedate;
public $message;
public $isdone;
public function __construct()
{
$this->tableName = 'todos';

}
}
// this would be the method to put in the index page for accounts
$records = accounts::findAll();
//print_r($records);
// this would be the method to put in the index page for todos
$records = todos::findAll();

//print_r($records);

if( isset($_POST['insert'])) {
    $record = accounts::insert();

}

if( isset($_POST['findall'])) {
    $record = accounts::findAll();

    echo '<table style="width:100%">
  <tr>
    <th>Id</th>
    <th>email</th>
    <th>owner id</th>
  </tr>
  <tr>';
    foreach ($records as $r) {
        echo '  <td>'.$r->id.'</td>
                        <td>'.$r->owneremail.'</td>
                        <td>'.$r->ownerid.'</td>
                      </tr>';
    }

    echo '</table>';
}
if( isset($_POST['findone'])){
    $record = accounts::findOne($_POST['onerecord']);
    //$record->delete();
    echo '<table style="width:100%">
  <tr>
    <th>Id</th>
    <th>email</th>
    <th>name</th>
  </tr>
  <tr>';

       echo '  <td>'.$record->id.'</td>
                        <td>'.$record->email.'</td>
                        <td>'.$record->fname.'</td>
                      </tr>';





    echo '</table>';



}
//this code is used to get one record and is used for showing one record or updating one record
//$record1 = todos::findOne(1);
//print_r($record1);
//this is used to save the record or update it (if you know how to make update work and insert)
// $record->save();
//$record = accounts::findOne(1);
//This is how you would save a new todo item
$record = new todo();
$record->message = 'some task';
$record->isdone = 0;
//$record->save();



//print_r($record);
$record = todos::create();
//print_r($record);

?>

<html>
<body>

<form method="post">
    <!--    search_all: <input type="text" name="all records"><br>-->
    search_one: <input type="text" name="onerecord"><br>
    <input type="hidden" name="findone" value="true"/>
    <input type="submit">
</form>

<form method="post">
    search_all: <input type="hidden" name="findall" value="true"/>
    <input type="submit">
</form>


<form method="post">
    Insert: <input type="hidden" name="insert" value="true"/>
    <input type="submit">
</form>

<form method="post">
    Update: <input type="hidden" name="update" value="true"/>
    <input type="submit">
</form>


<form method="post">
    <!--    search_all: <input type="text" name="all records"><br>-->
    delete: <input type="text" name="delete"><br>
    <input type="hidden" name="delete" value="true"/>
    <input type="submit">
</form>


</body>
</html>
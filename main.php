
class main
{


public function __construct()
{
//print_r($_REQUEST);
//set default page request when no parameters are in URL
$pageRequest = 'upload';
//check if there are parameters
if (isset($_REQUEST['page'])) {
//load the type of page the request wants into page request
$pageRequest = $_REQUEST['page'];
}
//instantiate the class that is being requested
$page = new $pageRequest;


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
$page->get();
} else {
$page->post();
}

}

}
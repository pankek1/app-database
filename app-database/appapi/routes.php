<?php   
    /**
 * API Endpoint Router
 *
 * This PHP script serves as a simple API endpoint router, handling GET and POST requests for specific resources.
 *
 *
 * Usage:
 * 1. Include this script in your project.
 * 2. Define resource-specific logic in the 'get.php' and 'post.php' modules.
 * 3. Send requests to the appropriate endpoints defined in the 'switch' cases below.
 *
 * Example Usage:
 * - API_URL: http://localhost/demoproject/api
 * - GET request for employees: API_URL/employees
 * - GET request for jobs: API_URL/jobs
 * - POST request for adding employees: API_URL/addemployee (with JSON data in the request body)
 * - POST request for adding jobs: API_URL/addjob (with JSON data in the request body)
 *
 */

    header("Access-Control-Allow-Origin: *");

    // Include required modules
    require_once "./modules/get.php";
    require_once "./modules/post.php";
    require_once "./config/database.php";

    $con = new Connection();
    $pdo = $con->connect();

    // Initialize Get and Post objects
    $get = new Get($pdo);
    $post = new Post($pdo);

    // Check if 'request' parameter is set in the request
    if(isset($_REQUEST['request'])){
         // Split the request into an array based on '/'
        $request = explode('/', $_REQUEST['request']);
    }
    else{
         // If 'request' parameter is not set, return a 404 response
        echo "Not Found";
        http_response_code(404);
    }

    // Handle requests based on HTTP method
    switch($_SERVER['REQUEST_METHOD']){
        // Handle GET requests
        case 'GET':
            switch($request[0]){
                case 'getprojects':                   
                    $param = null;
                    if(count($request) > 1){
                        $param = $request[1];
                    }
                    $response = $get->get_projects($param);
                    echo json_encode($response);
                break;

                case 'getorders':
                    $param = null;
                    if(count($request) > 1){
                        $param = $request[1];
                    }
                    $response = $get->get_orders($param);
                    echo json_encode($response);
                break;

                case 'getsomething':
                    //some implementaiton
                break;

                default:
                    // Return a 403 response for unsupported requests
                    echo "This is forbidden";
                    http_response_code(403);
                break;
            }
        break;
        // Handle POST requests    
        case 'POST':
            // Retrieves JSON-decoded data from php://input using file_get_contents
            $data = json_decode(file_get_contents("php://input"));
            switch($request[0]){
                
                case 'addprojects':
                    $response = $post->add_projects($data);
                    echo json_encode($response);
                break;

                case 'editproducts':
                    $response = $post->edit_products($data);
                    echo json_encode($response);
                break;

                case 'deleteproducts':
                    $response = $post->delete_products($data);
                    echo json_encode($response);
                break;


                case 'addorders':
                    $response = $post->add_orders($data);
                    echo json_encode($response);
                break;


                default:
                    // Return a 403 response for unsupported requests
                    echo "This is forbidden";
                    http_response_code(403);
                    break;
            }
            break;
        default:
            // Return a 404 response for unsupported HTTP methods
            echo "Method not available";
            http_response_code(404);
        break;
    }

?>
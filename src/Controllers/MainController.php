<?php
/**
 * @package ${PACKAGE}
 * @copyright 2017
 * @author
 */


namespace Copernicus\Controllers;


use Copernicus\Managers\UsersManager;

class MainController extends Controller
{
    private $arrayWithColumns = array();
    private $arrayWithMarkers = array();
    private $arrayWithGroupColumns = array();

    public function insertUser()
    {
        if (!empty($_POST) && array_key_exists('data', $_POST)) {
            $json = $_POST['data'];
            $data = json_decode($json);
            var_dump($data);
            die;
            if (is_array($data)) {
                $user = new UsersManager();
                if ($user->insert($data)) {
                    $response = [
                        'status' => 200,
                        'response' => 'Data inserted',
                    ];
                } else {
                    $response = [
                        'status' => 500,
                        'response' => 'Server error',
                    ];
                }

                return json_encode($response);
            }
        }

    }

    public function updateUser()
    {
        $putdata = fopen("php://input", "r");
        $data = json_decode($putdata);
        if (is_array($data)) {
            //TODO: create method for updating user
        }

        $response = [
            'status' => 404,
            'response' => 'Not found',
        ];

        return json_encode($response);
    }

    public function deleteUser()
    {
        $putdata = fopen("php://input", "r");
        $data = json_decode($putdata);
        if (is_array($data)) {
            //TODO: create method for deleting user
        }

        $response = [
            'status' => 404,
            'response' => 'Not found',
        ];

        return json_encode($response);
    }

    public function selectUser()
    {
        if (!empty($_GET) && array_key_exists('data', $_GET)) {
            $json = $_GET['data'];
            $data = json_decode($json);
            if (is_array($data)) {
                //TODO: create method for selecting user 
            }
        }

        $response = [
            'status' => 404,
            'response' => 'Not found',
        ];

        return json_encode($response);
    }

    public function getNumberOfArticlesPerDay()
    {
        $user = new UsersManager();
        $this->arrayWithGroupColumns = array('CREATED');
        $result = $user->count($this->arrayWithMarkers, $this->arrayWithColumns, $this->arrayWithGroupColumns);

        $response = [
            'status' => 200,
            'response' => $result,
        ];
    
        return json_encode($response);

    }

    public function getNumberOfArticlesPerDayBetween()
    {
        $response = [
            'status' => 404,
            'response' => 'Not found',
        ];

        if (!empty($_POST) && array_key_exists('data', $_POST)) {
            $json = $_POST['data'];
            $data = json_decode($json);

            if (is_array($data)) {
                $user = new UsersManager();
                $this->arrayWithGroupColumns = array('CREATED');
                $this->arrayWithMarkers[] = array('CREATED', '>=', $data['needed_date']);
                $this->arrayWithMarkers[] = array('CREATED', '<=', date("Y-m-d H:i:s"));
                $result = $user->count($this->arrayWithMarkers, $this->arrayWithColumns, $this->arrayWithGroupColumns);
                $response = [
                    'status' => 200,
                    'response' => $result,
                ];
            }
        }

        return json_encode($response);
    }

}
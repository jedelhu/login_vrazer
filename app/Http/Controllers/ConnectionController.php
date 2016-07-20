<?php

namespace App\Http\Controllers;

use App\Connections;
use App\Http\Requests;
use App\Http\Requests\CreateConnectionRequest;
use App\Http\Requests\CreateMysqlConnectionRequest;
use Crypt;
use Net_SFTP;
use Net_SSH2;
use Request;
use Session;

class ConnectionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.connection.index');
    }

    /**
     * Display a listing of the resource for MYSQL.
     *
     * @return Response
     */

    public function mysql()
    {

        return view('admin.connection.mysql');
    }

    public function test()
    {
        return view('admin.connection.test');
    }

    public function testt()
    {
        return view('admin.connection.testt');
    }

    /**
     * Validate FTP/Sftp/SSH connections and return directory listings.
     *
     * @return Response
     */
    public function mysqlcreate(CreateMysqlConnectionRequest $request)
    {

        $input_data = $request->all();
        $sql = "SHOW DATABASES";
        $link = mysqli_connect($input_data['host'], $input_data['username'], $input_data['password']) or die ('Error connecting to mysql: ' . mysqli_error($link) . '\r\n');

        if (!($result = mysqli_query($link, $sql))) {
            printf("Error: %s\n", mysqli_error($link));
        }
        $list = array();
        while ($row = mysqli_fetch_row($result)) {
            if (($row[0] != "information_schema") && ($row[0] != "mysql")) {
                $list[] = $row[0];
            }
        }
        return (['status' => 'success', 'list' => $list]);
    }


    /**
     * Validate FTP/Sftp/SSH connections and return directory listings.
     *
     * @return Response
     */
    public function create(CreateConnectionRequest $request)
    {


        $data = $request->all();
        $dir = $request->dir;

        $conn = new Connections();
        $folders = array();

        if ($data['type'] == 'ftp') {

            try {

                $connection = $conn->validate_connection($data['type'], $data['host'], $data['username'], $data['password'], $data['port']);

                if ($connection === false) {

                    return (['status' => 'error', 'message' => 'Login failed']);
                } else {

                    // turn on passive mode transfers
                    // ftp_pasv($connection, true);

                    if (!empty($dir)) {

                        $list = ftp_rawlist($connection, $request->dir);

                        foreach ($list as $key => $folder) {
                            $info = array();
                            $current = preg_split("/[\s]+/", $folder, 9, PREG_SPLIT_NO_EMPTY);
                            $info['perms'] = $current[0];
                            $info['name'] = str_replace('//', '', $current[8]);
                            sort($list);
                            if ($info['name'] != '.' && $info['name'] != '..') {
                                if ($this->get_type($info['perms']) == "folder") {
                                    $folders[$key . $info['name']]['filename'] = $info['name'];
                                    $folders[$key . $info['name']]['type'] = 2;
                                } else {
                                    $files[$key . $info['name']]['filename'] = $info['name'];
                                    $files[$key . $info['name']]['type'] = 1;
                                }
                            }
                        }
                        if (isset($folders)) {
                            uasort($folders, array($this, 'cmp'));
                        } else {
                            $folders = array();
                        }

                        if (isset($files)) {
                            uasort($files, array($this, 'cmp'));
                        } else {
                            $files = array();
                        }
                        $result = $folders + $files;

                        $list = json_encode($result);

                        return (['status' => 'success', 'type' => 'ftp', 'list' => $list]);
                    } else {
                        $data['username'] = Crypt::encrypt($data['username']);
                        $data['password'] = Crypt::encrypt($data['password']);

                        Connections::create($data);
                        $list = ftp_rawlist($connection, ".");
                        sort($list);
                        foreach ($list as $key => $folder) {
                            $info = array();
                            $current = preg_split("/[\s]+/", $folder, 9, PREG_SPLIT_NO_EMPTY);
                            $info['perms'] = $current[0];
                            $info['name'] = str_replace('//', '', $current[8]);

                            if ($info['name'] != '.' && $info['name'] != '..') {
                                if ($this->get_type($info['perms']) == "folder") {
                                    $folders[$key]['filename'] = $info['name'];
                                    $folders[$key]['type'] = 2;
                                } else {
                                    $files[$key]['filename'] = $info['name'];
                                    $files[$key]['type'] = 1;
                                }
                            }
                        }

                        if (isset($folders)) {
                            uasort($folders, array($this, 'cmp'));
                        } else {
                            $folders = array();
                        }

                        if (isset($files)) {
                            uasort($files, array($this, 'cmp'));
                        } else {
                            $files = array();
                        }
                        $result = $folders + $files;
                        $list = json_encode($result);

                        return (['status' => 'success', 'type' => 'ftp', 'list' => $list]);
                    }


                }
            } catch (Exception $e) {

                return (['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else if ($data['type'] == 'sftp') {

            $connection = $conn->validate_connection($data['type'], $data['host'], $data['username'], $data['password'], $data['port']);

            if ($connection === false) {

                return (['status' => 'error', 'message' => 'Login failed']);
            } else {
                if (!empty($dir)) {

                    $result = $connection->rawlist($request->dir);


                    foreach ($result as $key => $value) {
                        if ($value['type'] == 2) {
                            $array1[$key]['filename'] = $value['filename'];
                            $array1[$key]['type'] = 2;
                        }
                        if ($value['type'] == 1) {
                            $array2[$key]['filename'] = $value['filename'];
                            $array2[$key]['type'] = 1;
                        }
                    }
//                        print_r($result);exit();
                    if (isset($array1)) {
                        uasort($array1, array($this, 'cmp'));
                    } else {
                        $array1 = array();
                    }
                    if (isset($array2)) {
                        uasort($array2, array($this, 'cmp'));
                    } else {
                        $array2 = array();
                    }
                    $result = $array1 + $array2;

//                        uasort($result, array($this,'type'));
                    $list = json_encode($result);

                } else {
                    $data['username'] = Crypt::encrypt($data['username']);
                    $data['password'] = Crypt::encrypt($data['password']);

                    Connections::create($data);
                    $result = $connection->rawlist();

                    foreach ($result as $key => $value) {
                        if ($value['type'] == 2) {
                            $array1[$key]['filename'] = $value['filename'];
                            $array1[$key]['type'] = 2;
                        }
                        if ($value['type'] == 1) {
                            $array2[$key]['filename'] = $value['filename'];
                            $array2[$key]['type'] = 1;
                        }
                    }


                    if (isset($array1)) {
                        uasort($array1, array($this, 'cmp'));
                    } else {
                        $array1 = array();
                    }
                    if (isset($array2)) {
                        uasort($array2, array($this, 'cmp'));
                    } else {
                        $array2 = array();
                    }
                    $result = $array1 + $array2;
                    $list = json_encode($result);

                }

                return (['status' => 'success', 'list' => $list]);
            }
        } else if ($data['type'] == 'ssh') {

            $connection = $conn->validate_connection($data['type'], $data['host'], $data['username'], $data['password'], $data['port']);

            if ($connection === false) {

                return (['status' => 'error', 'message' => 'Login failed']);
            } else {
                if (!empty($dir)) {


                    $path_folders = 'ls ' . trim($dir) . ' -F | grep /';
                    $path_files = 'ls ' . trim($dir) . ' -F | grep -v /';
                    $list = $connection->exec($path_folders);
                    $list2 = $connection->exec($path_files);

                    $list = str_replace("stdin: is not a tty", "", $list);
                    $list2 = str_replace("stdin: is not a tty", "", $list2);

                    $list = preg_split('/\s+/', trim($list));
                    $list2 = preg_split('/\s+/', trim($list2));

                    $inc = 0;
                    foreach ($list as $key => $dirs) {
                        $dirs = explode('/', $dirs);
                        if (!empty(trim(trim($dirs[0])))) {

                            $array[$inc . $dirs[0]]['filename'] = trim($dirs[0]);
                            $array[$inc . $dirs[0]]['type'] = 2;
                            $inc = $inc + 1;
                        }
                    }
                    if (isset($array)) {
                        uasort($array, array($this, 'cmp'));
                    } else {
                        $array = array();
                    }


                    foreach ($list2 as $key => $dirs) {
                        if (!empty(trim($dirs))) {

                            $array2[$inc . $dirs]['filename'] = trim($dirs);
                            $array2[$inc . $dirs]['type'] = 1;
                            $inc = $inc + 1;
                        }
                    }
                    if (isset($array2)) {
                        uasort($array2, array($this, 'cmp'));
                    } else {
                        $array2 = array();
                    }
//                        $array = array_filter($array);
//                        $array2 = array_filter($array2);
                    if (empty($array)) {
                        $result = $array2;
                    } else if (empty($array2)) {
                        $result = $array;
                    } else {
                        $result = $array + $array2;
                    }

//                        $result=$array + $array2;


                    $list = json_encode($result);

                    return (['status' => 'success', 'list' => $list]);
                } else {
                    $data['username'] = Crypt::encrypt($data['username']);
                    $data['password'] = Crypt::encrypt($data['password']);

                    Connections::create($data);
                    $list = $connection->exec('ls -F | grep /');
                    $list2 = $connection->exec('ls -F | grep -v /');
                    $var = 0;
                    if (strpos($list, 'Shell access is not enabled') !== false) {

                        return (['status' => 'error', 'message' => $list]);
                    } else {

                        $list = explode('/', $list);
                        $list[0] = trim(str_replace('stdin: is not a tty', '', $list[0]));

                        unset($list[count($list) - 1]);

                        foreach ($list as $key => $dir) {
                            if (!empty(trim($dir))) {
                                $array[$var]['filename'] = trim($dir);
                                $array[$var]['type'] = 2;
                                $var = $var + 1;
                            }
                        }
                        if (isset($array)) {
                            uasort($array, array($this, 'cmp'));
                        } else {
                            $array = array();
                        }


                        $output = str_replace("stdin: is not a tty", "", $list2);

                        $output = preg_split('/\s+/', trim($output));
                        $output = array_filter($output, function ($e) {
                            if (stripos($e, "@") === false)
                                return true;
                            else
                                return false;
                        });

                        foreach ($output as $key => $dir) {
                            if (!empty(trim($dir))) {

                                $array2[$var]['filename'] = trim($dir);
                                $array2[$var]['type'] = 1;
                                $var = $var + 1;
                            }
                        }
                        if (isset($array2)) {
                            uasort($array2, array($this, 'cmp'));
                        } else {
                            $array2 = array();
                        }
//                            $array = array_filter($array);
//                            $array2 = array_filter($array2);
                        if (empty($array)) {
                            $result = $array2;
                        } else if (empty($array2)) {
                            $result = $array;
                        } else {
                            $result = $array + $array2;
                        }


                        $list = json_encode($result);


                        return (['status' => 'success', 'list' => $list]);
                    }
                }
            }
        }
    }

    public function cmp($a, $b)
    {
        return strcmp($a['filename'], $b['filename']);

//            if(strcmp($a['filename'], $b['filename']) && $a['type'] < $b['type']){
//                return 1;
//            }else{
//                return 0;
//            }

    }

    public function type($a, $b)
    {
        if ($a['type'] == $b['type']) {
            return 0;
        }
        return ($a['type'] > $b['type']) ? -1 : 1;

    }

    /**
     * Validate ftp/sftp/ssh connection
     *
     * @return array
     */
    public function validate_conenctions($type, $host, $username, $password, $port = 21)
    {

        // validating ftp connection
        if ($type == 'ftp') {

            $connection = ftp_connect($host, $port);
            $login = ftp_login($connection, $username, $password);

            if ((!$connection) || (!$login)) {
                return false;
            } else {
                return $connection;
            }
        } else if ($type == 'sftp') { // validating SFTP connections
            $connection = new Net_SFTP($host);
            var_dump($connection->login($username, $password, $port));
            if (!$connection->login($username, $password, $port)) {
                return false;
            } else {
                return $connection;
            }
        } else if ($type == 'ssh') { //validating SSH connections
            $connection = new Net_SSH2($host);

            if (!$connection->login($username, $password, $port)) {
                return false;
            } else {
                return $connection;
            }
        }
    }

    /**
     * check item type.
     *
     * @return string
     */
    public function get_type($perms)
    {
        if (substr($perms, 0, 1) == "d") {
            return 'folder';
        } elseif (substr($perms, 0, 1) == "l") {
            return 'link';
        } else {
            return 'file';
        }
    }

}
    
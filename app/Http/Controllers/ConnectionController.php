<?php

namespace App\Http\Controllers;

use App\Connections;
use App\Http\Requests;
use App\Http\Requests\CreateConnectionRequest;
use App\Http\Requests\CreateMysqlConnectionRequest;
use Crypt;
use Exception;
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

    public function rootPath()
    {
//        $remote_path = $_GET['remote_path'];
//
//        Session::put('remotepath', $remote_path);
//
//        $host = trim(Session::get('host'));
//        $username = trim(Session::get('username'));
//        $password = trim(Session::get('password'));
//        $port = trim(Session::get('port'));
//        $protocol = trim(Session::get('protocol'));
//        $sitename = trim(Session::get('sitename'));
//        $account = trim(Session::get('account'));
//        $localpath = trim(Session::get('localpath'));
//
//        $host = filter_var($host, FILTER_SANITIZE_STRING);
//        $username = filter_var($username, FILTER_SANITIZE_STRING);
//        $port = filter_var($port, FILTER_SANITIZE_STRING);
//        $protocol = filter_var($protocol, FILTER_SANITIZE_STRING);
//        $sitename = filter_var($sitename, FILTER_SANITIZE_STRING);
//        $account = filter_var($account, FILTER_SANITIZE_STRING);
//        $localpath = filter_var($localpath, FILTER_SANITIZE_STRING);
//        $remote_path = filter_var($remote_path, FILTER_SANITIZE_STRING);
//
//        $command = 'php /root/resources/backups/backup.php --username=' . $username . ' --password="' . $password . '" --host=' . $host . ' --sitename=' . $sitename . ' --account=' . $account . ' --remotepath=' . $remote_path . ' --localpath=/root/resources/backup_data --verbose --protocol=' . $protocol . ' --port=' . $port . '';
//        echo $command;
//
//        $conn = new Connections();
//
//        $connection = $conn->validate_connection("sftp", "45.33.9.136", "root", "cU9dMgu3xQcyXgmP", 2222);
//
//        if ($connection === false) {
//
//            return (['status' => 'error', 'message' => 'Login failed']);
//        } else {
//            $result = $connection->exec($command);
//            return (['status' => 'success', 'result' => $result]);
//        }
//
        return (['status' => 'success', 'result' => '']);
    }

    /**
     * To connect to MySQL over the SSH connection.
     *
     * @return Response
     */
    public function mysqlcreate(CreateMysqlConnectionRequest $request)
    {
        $input_data = $request->all();
        if ($input_data['type'] != "mysql") {

            $ssh = new Net_SSH2(trim($input_data['sshhost']), trim($input_data['sshport']));
            try {
                if (!$ssh->login(trim($input_data['sshusername']), trim($input_data['sshpassword']))) {
                    return (['status' => 'error', 'message' => "Login failed. Please provide valid credentials."]);
                }
            } catch (\Exception $e) {
                return (['status' => 'error', 'message' => "Login failed. Please provide valid credentials."]);
            }

            $output = $ssh->exec('mysql -u ' . trim($input_data['username']) . ' -p"' . trim($input_data['password']) . '" -e "SHOW DATABASES"');
            $output = str_replace("stdin: is not a tty", "", $output);
            $output = str_replace("Database", "", $output);
            if (strpos(strtolower($output), 'error') != false) {
                return (['status' => 'error', 'message' => $output]);
            } else {
                $list = preg_split('/\s+/', trim($output));
                return (['status' => 'success', 'list' => $list]);
            }


        } else {

            $sql = "SHOW DATABASES";
            try {
                $link = mysqli_connect($input_data['host'], $input_data['username'], $input_data['password']) or die ('Error connecting to mysql: ' . mysqli_error($link) . '\r\n');

                if (!($result = mysqli_query($link, $sql))) {
                    return (['status' => 'error', 'message' => mysqli_error($link)]);
                }
            } catch (\Exception $e) {
                return (['status' => 'error', 'message' => "Login Failed"]);
            }
            $list = array();
            while ($row = mysqli_fetch_row($result)) {
                if (($row[0] != "information_schema") && ($row[0] != "mysql")) {
                    $list[] = $row[0];
                }
            }
            return (['status' => 'success', 'list' => $list]);

        }


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

        Session::put('host', $data['host']);
        Session::put('username', $data['username']);
        Session::put('password', $data['password']);
        Session::put('port', $data['port']);
        Session::put('protocol', $data['type']);
        Session::put('sitename', $data['username']);
        Session::put('account', 'root');
        Session::put('localpath', '/root/resources/backup_data');
        Session::put('remotepath', '/root/resources/backup_data');


        $conn = new Connections();
        $folders = array();

        if ($data['type'] == 'ftp') {

            try {

                $connection = $conn->validate_connection($data['type'], $data['host'], $data['username'], $data['password'], $data['port']);


                if ($connection === false) {

                    return (['status' => 'error', 'message' => 'Login failed']);
                } else {

                    if (!empty($dir)) {

                        $list = ftp_rawlist($connection, $request->dir);
                        $pwd = ftp_pwd($connection);
                        $pwd = trim($pwd) . trim($request->dir);


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

                        return (['status' => 'success', 'type' => 'ftp', 'list' => $list, 'pwd' => $pwd]);
                    } else {
                        $data['username'] = Crypt::encrypt($data['username']);
                        $data['password'] = Crypt::encrypt($data['password']);
                        $pwd = ftp_pwd($connection);

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

                        return (['status' => 'success', 'type' => 'ftp', 'list' => $list, 'pwd' => $pwd]);
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

                    $pwd = $connection->exec('pwd');
                    $pwd = trim($pwd) . '/' . trim($request->dir);
                    $pwd = trim(str_replace('stdin: is not a tty', '', $pwd));


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

                } else {
                    $data['username'] = Crypt::encrypt($data['username']);
                    $data['password'] = Crypt::encrypt($data['password']);

                    Connections::create($data);
                    $result = $connection->rawlist();
                    $pwd = $connection->exec('pwd');
                    $pwd = trim(str_replace('stdin: is not a tty', '', $pwd));

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

                return (['status' => 'success', 'list' => $list, 'pwd' => $pwd]);
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
                    $pwd = $connection->exec('pwd');
                    $pwd = trim(str_replace('stdin: is not a tty', '', $pwd));
                    $pwd = trim($pwd) . '/' . trim($request->dir);

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

                    if (empty($array)) {
                        $result = $array2;
                    } else if (empty($array2)) {
                        $result = $array;
                    } else {
                        $result = $array + $array2;
                    }


                    $list = json_encode($result);

                    return (['status' => 'success', 'list' => $list, 'pwd' => $pwd]);
                } else {
                    $data['username'] = Crypt::encrypt($data['username']);
                    $data['password'] = Crypt::encrypt($data['password']);

                    Connections::create($data);
                    $list = $connection->exec('ls -F | grep /');
                    $list2 = $connection->exec('ls -F | grep -v /');
                    $var = 0;
                    $pwd = $connection->exec('pwd');
                    $pwd = trim(str_replace('stdin: is not a tty', '', $pwd));
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
                        if (empty($array)) {
                            $result = $array2;
                        } else if (empty($array2)) {
                            $result = $array;
                        } else {
                            $result = $array + $array2;
                        }


                        $list = json_encode($result);


                        return (['status' => 'success', 'list' => $list, 'pwd' => $pwd]);
                    }
                }
            }
        }
    }

    public function cmp($a, $b)
    {
        return strcmp($a['filename'], $b['filename']);

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
    
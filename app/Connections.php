<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use Net_SFTP;
    use Net_SSH2;

    class Connections extends Model {

        protected $connection;
        protected $fillable = ['sites_id', 'username', 'password', 'type', 'settings'];

        /**
         * Validate ftp/sftp/ssh connection 
         *
         * @return array
         */
        public function validate_connection($type, $host, $username, $password, $port = 21) {

            try {
                $this->type = $type;
                // validating ftp connection
                if ($type == 'ftp') {

                    $this->connection = ftp_connect($host, $port);

                    if (!$this->connection) {
                        return false;
                    }

                    $login = ftp_login($this->connection, $username, $password);
                   
                    if (!$login) {
                        return false;
                    } else {
                        @ftp_pasv($this->connection, true);
                        return $this->connection;
                    }
                } else if ($type == 'sftp') { // validating SFTP connections
                    $this->connection = new Net_SFTP($host, $port);

                    if (!$this->connection->login($username, $password, $port)) {
                        return false;
                    } else {
                        return $this->connection;
                    }
                } else if ($type == 'ssh') { //validating SSH connections
                    $this->connection = new Net_SSH2($host, $port);

                    if (!$this->connection->login($username, $password, $port)) {
                        return false;
                    } else {
                        return $this->connection;
                    }
                }
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }
        }

        /**
         * upload_file function to upload files
         *
         * @return boolean
         */
        public function upload_file($remote_file, $content) {

            if ($this->type == 'ftp') {

                $handle = fopen('php://temp', 'r+');
                fwrite($handle, $content);
                rewind($handle);

                if (ftp_fput($this->connection, $remote_file, $handle, FTP_ASCII)) {
                    return true;
                } else {
                    return false;
                }
            } else if ($this->type == 'sftp') {

                return $this->connection->put($remote_file, $content);
            } else if ($this->type == 'ssh') {

                return $this->connection->put($remote_file, $content);
            }
        }

        /**
         * download_file function to download files
         *
         * @return boolean
         */
        public function download_file($local_file, $remote_file) {
            if ($this->type == 'ftp') {

                // download server file
                if (ftp_get($this->connection, trim($local_file), trim($remote_file), FTP_ASCII)) {
                    ftp_close($this->connection);

                    return true;
                } else {
                    return false;
                }
            } else if ($this->type == 'sftp') {
                $contents = $this->connection->get($remote_file);
                file_put_contents($local_file, $contents);
            } else if ($this->type == 'ssh') {
                $contents = $this->connection->get($remote_file);
                file_put_contents($local_file, $contents);
            }
            return true;
        }

        /**
         * current_dir function to return current dir path
         *
         * @return string
         */
        public function current_dir() {

            if ($this->type == 'ftp') {

                return ftp_pwd($this->connection);
            } else if ($this->type == 'sftp') {

                return $this->connection->pwd();
            } else if ($this->type == 'ssh') {

                return $this->connection->pwd();
            }
        }

    }
    
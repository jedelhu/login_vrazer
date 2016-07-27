#!/usr/bin/env php
<?php

/*  
*	This file, its contents, its methods, and all associated resources
*	- whether included inline within this code or remotely loaded from another file or server
*	are copyrighted intellectual property of VRAZER, LLC.
*	They are intended for use only by active employees, agents, and clients of VRAZER, LLC and
* 	are only permitted for use in the aid of VRAZER, LLC customers with the permission of an 
* 	authorized agent of VRAZER, LLC. Redistribution, use for purpose s other than those listed here, 
* 	use outside the limits presented by the terms of service, or use without
* 	express written permission from VRAZER, LLC is expressly forbidden and
* 	subject to the full penalties of law.
* 	Copyright 2016, VRAZER, LLC
*
*
* 	Required packages
* 	phpseclib
* 	lftp
* 	zip
* 	unzip
* 	jgit-cli
*
*/


@ignore_user_abort(true);
@ini_set('max_execution_time', 0);
@set_time_limit(0);
@ini_set('memory_limit', '512M');
error_reporting(E_ALL ^ E_DEPRECATED);
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/includes/phpseclib');

require 'includes/functions.php';
require 'Net/SFTP.php';


/**
 * setup_runtime function.
 *
 * @access public
 * @return void
 */
function setup_runtime() {
	global $params, $config, $resources, $logs, $requirements, $excludes, $includes, $globexcludes, $globincludes;

	//Params
	$params = array(
		'username' => null,
		'password' => null,
		'host' => null,
		'remotepath' => '.',
		'localpath' => './s3',
		'logpath' => '/var/log/backups',
		'protocol' => 'ftp',
		'sitename' => null,
		'verbose' => false,
		'account' => null,
		'port' => null,
		'parallel' => 1,
		'help' => false,
		'incremental' => false,
		'sshkey' => false,
	);

	//Config for params
	$config = array(
		'supported' => array(
			'--username',
			'--password',
			'--host',
			'--remotepath',
			'--localpath',
			'--logpath',
			'--protocol',
			'--sitename',
			'--verbose',
			'--account',
			'--port',
			'--parallel',
			'--help',
			'--incremental',
			'--sshkey'
		),
		'formats' => array(
			'--remotepath' => 'path',
			'--localpath' => 'path',
			'--logpath' => 'path',
			'--protocol' => 'array',
			'--host' => 'hostname'
		),
		'arrays' => array(
			'--protocol' => array(
				'ftp',
				'ftps',
				'sftp'
			)
		)
	);

	$reremotepaths = array();

	$includes = array();

	$excludes = array(
		'\.git/',
		'\.cvs/',
		'\.svn/',
		'_vti_cnf',
		'_vti_pvt',
		'_vti_script',
		'_vti_txt',
		'error_log',
		'php_errorlog',
		'wp-content/managewp/backups/',
		'wp-content/cache/',
		'wp-content/uploads/backupbuddy_backups/',
		'wp-content/uploads/pb_backupbuddy/',
		'.ftpquota',
		'stats/.*\.log',
		'stats/.*\.gz',
		'logs/.*\.gz',
		'logs/.*\.log',
		'debug/.*\.txt',
		'debug/.*\.log',
		'backupwordpress.*-backups/.*\.gz',
		'backupwordpress.*-backups/.*\.sql',
		'backupwordpress.*-backups/.*\.zip'
	);

	$globincludes = array();

	$globexcludes = array();

	$logs = array(
		'main' => $params['logpath'] . '/' . $params['sitename'] . '.backup.log',
		'errors' => $params['logpath'] . '/' . $params['sitename'] . '.backup.error.log'
	);
}


/**
 * validate_args function.
 *
 * @access public
 * @return void
 */
function validate_args() {
	global $params, $logs;

	if ($params['incremental'] !== true) {
		if (is_null($params['host']) ||
			is_null($params['username']) ||
			is_null($params['password']) ||
			is_null($params['protocol']) ||
			is_null($params['account'])
		) {
			log_message('Missing Required Parameters' . BR);
			exit(1);
		}

		if(is_null($params['sitename']) && is_null($params['host'])) {
			log_message('Missing Required Parameters' . BR);
			exit(1);
		}

		if ($params['host'] === false) {
			log_message('Invalid Hostname Value' . BR);
			exit(1);
		}

		if (empty($params['sitename']) && !empty($params['host'])) {
			$params['sitename'] = $params['host'];
		}

		if (empty($params['port'])) {
			switch ($params['protocol']) {
			case 'ftp':
				$params['port'] = 21;
				break;
			case 'ftps':
				$params['port'] = 990;
				break;
			case 'sftp':
				$params['port'] = 22;
				break;
			default:
				log_message('Invalid Protocol' . BR);
				exit(1);
				break;
			}
		}
		elseif(!is_numeric($params['port'])) {
			log_message('Invalid Port Value' . BR);
			exit(1);
		}

		if(!is_numeric($params['parallel'])) {
			log_message('Invalid Parallel Value' . BR);
			exit(1);
		}
	}

	if(!is_bool($params['verbose'])) {
		log_message('Invalid Verbose Value' . BR);
		exit(1);
	}

	if(!is_bool($params['incremental'])) {
		log_message('Invalid Incremental Value' . BR);
		exit(1);
	}

	if(!is_bool($params['help'])) {
		log_message('Invalid Help Value' . BR);
		exit(1);
	}

	if(strpos($params['localpath'], './') !== false) {
		$params['localpath'] = realpath(dirname(__FILE__)) . '/' . str_replace('./', '', $params['localpath']);
	}

	if(strpos($params['logpath'], './') !== false) {
		$params['logpath'] = realpath(dirname(__FILE__)) . '/' . str_replace('./', '', $params['logpath']) . '/' . $params['account'];
	}

	if(!file_exists($params['logpath'])) {
		if(is_writeable($params['logpath'])) {
			mkdir($params['logpath'], 0755, true);
		}
		else {
			$params['logpath'] = dirname(__FILE__) . $params['logpath'];
			if(!file_exists($params['logpath'])) {
				mkdir($params['logpath'], 0755, true);
			}
		}
	}
}


/**
 * run_lftp function.
 *
 * @access public
 * @return void
 */
function run_lftp() {
	global $params, $excludes, $includes, $globexcludes, $globincludes;

	/*$command = '(lftp ' . $params['protocol'] . '://' . $params['username'] . ':' . $params['password'];
	$command .= '@' . $params['host'] . ' -p ' . $params['port'];
	$command .=  ' -e \'set ssl:verify-certificate off; ls; bye\'';
	//$command = escapeshellcmd($command);*/

	
	/*$exclusions = '';
	foreach($excludes as $exclude) {
		$exclusions .= '--exclude=' . $exclude . ' ';
	}*/

	$exclusions .= '--include-rx-from=' . '/excludelist ';

	$globexclusions = '';
	foreach($globexcludes as $globexclude) {
		$globexclusions .= '--exclude-glob=' . $exclude . ' ';
	}

	$command =  '(lftp ' . $params['protocol'] . '://' . $params['username'] . ':' . urlencode($params['password']);
	$command .= '@' . $params['host'] . ' -p ' . $params['port'];
	$command .= ' -e " set ftp:list-options -a; set ssl:verify-certificate off; set mirror:parallel-directories true; mirror --delete --only-newer --verbose -P ';
	$command .= $params['parallel'] . ' ' . $exclusions . ' ' . $params['remotepath'] . ' ';
	$command .= $params['localpath'];

	if(substr($command, -1) != '/') {
		$command .= '/';
	}
	
	 $command .= $params['account'] . '/' . $params['sitename'] . '; bye" ';

	if($params['verbose'] === true) {
		$command .= ' | tee -a ' . $params['logpath'] . '/' . $params['sitename'] . '.backup.log)';
		$command .= ' 3>&1 1>&2 2>&3 | tee -a ' . $params['logpath'] . '/' . $params['sitename'] . '.backup.error.log ';
	}
	else {
		$command .= ') >> ' . $params['logpath'] . '/' . $params['sitename'] . '.backup.log';
		$command .= ' 2>> ' . $params['logpath'] . '/' . $params['sitename'] . '.backup.error.log ';
	}

	log_message($command);
	//passthru($command);
}


/**
 * run_rsync function.
 *
 * @access public
 * @return void
 */
function run_rsync() {
	global $params;

	$cwd = dirname(__FILE__);
	$command = '(';
	
	if($params['sshkey'] === true) {
		$command .= "sshpass '" . $params['may_be_some_new_parameter_needed_here_for_sshkey'] . "' ";
	}
	else if (!empty($params['password'])) {
		$command .= "sshpass -p '" . $params['password'] . "' ";		
	} else {
		log_message('SSH key or password is requred for SFTP');
		exit(1);
	}
	
	if($params['port'] != 22) {
		$command .= "rsync -avz --delete -e ";
		$command .= "'ssh -p " . $params['port'] . "' ";
		$command .= "--exclude-from='" . $cwd . "/excludelist' ";
	}
	else {
		$command .= "rsync -avz --delete --exclude-from='" . $cwd . "/excludelist' ";	
	}
	
	$command .= $params['username'] . "@" .  $params['host'] . ":'";
	$command .= $params['remotepath'];
	
	if(substr($command, -1) != '/') {
		$command .= '/';
	}
		
	$command .= "' ";
	$command .= $params['localpath'];

	if(substr($command, -1) != '/') {
		$command .= '/';
	}
	
	$command .= $params['account'] . '/' . $params['sitename'];

	if($params['verbose'] === true) {
		$command .= ' | tee -a ' . $params['logpath'] . '/' . $params['sitename'] . '.backup.log)';
		$command .= ' 3>&1 1>&2 2>&3 | tee -a ' . $params['logpath'] . '/' . $params['sitename'] . '.backup.error.log ';
	}
	else {
		$command .= ') >> ' . $params['logpath'] . '/' . $params['sitename'] . '.backup.log';
		$command .= ' 2>> ' . $params['logpath'] . '/' . $params['sitename'] . '.backup.error.log ';
	}
	
	//log_message($command);
	passthru($command);
}


/**
 * create_repo function.
 *
 * @access public
 * @return void
 */
function create_repo() {
	global $params;

	chdir($params['localpath'] . '/' . $params['account'] . '/' . $params['sitename']);
	passthru('git init');
	passthru('git config --global user.name "VRAZER Backups"');
	passthru('git config --global user.email "backups@vrazer.com"');
	passthru('git remote add s3 amazon-s3://.s3_public@vrazer.backups/sites/' . $params['account'] . '/' . $params['sitename'] . '/.git');
}


/**
 * git_commit function.
 *
 * @access public
 * @return void
 */
function git_commit() {
	global $params;

	chdir($params['localpath'] . '/' . $params['account'] . '/' . $params['sitename']);
	passthru('git add *');
	passthru('git commit -a -m "' . date('m/d/Y') .'"');
	passthru('jgit push s3 refs/heads/master');
}


global $params, $argc, $argv;

if($argc === 1) {
	log_message('Missing Required Parameters' . "\n");
	exit(1);
}

initialize();

//Initialize the params available
setup_runtime();

//Process input arguments
process_args($argv);

validate_args();

if ($params['incremental'] !== true) {
	switch ($params['protocol']) {
	case 'ftp':
		$handle = ftp_connect($params['host'], $params['port'], 30);

		if (!$handle) {
			echo 'Incorrect FTP host/port' , BR;
			exit(1);
		}
		else {
			if (@ftp_login($handle, $params['username'], $params['password'])) {
				ftp_pasv($handle, true);

				$list = ftp_nlist($handle, $params['remotepath']);

				if (!$list) {
					echo 'Invalid FTP Path' , BR;
					exit(1);
				}
				/*else {
						foreach($list as $item) {
							echo $item , BR;
						}
					}*/

				ftp_close($handle);
			}
			else {
				ftp_close($handle);
				echo 'FTP couldn\'t connect with provided username/password' , BR;
				exit(1);
			}
		}
		break;
	case 'ftps':
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "ftps://" . $params['host'] . '/');
		curl_setopt($curl, CURLOPT_PORT, $params['port']);
		curl_setopt($curl, CURLOPT_USERPWD, $params['username'] . ':' . $params['password']);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_FTP_SSL, CURLFTPSSL_ALL);
		curl_setopt($curl, CURLOPT_FTPSSLAUTH, CURLFTPAUTH_DEFAULT);
		curl_setopt($curl, CURLOPT_FTPPORT, $params['port']);
		$result = curl_exec($curl);

		curl_close($curl);
		break;
	case 'sftp':
		$sftp = new Net_SFTP($params['host'], $params['port']);

		if($params['sshkey'] === true) {
			if (1) { //login with ssheky
				echo 'SFTP couldn\'t connect with provided username/sshkey/host/port' , BR;
				exit(1);
			}
		}
		else if (@!$sftp->login($params['username'], $params['password'])) {
			echo 'SFTP couldn\'t connect with provided username/password/host/port' , BR;
			exit(1);
		}
		else {
			$list = $sftp->nlist($params['remotepath']);

			if (!$list) {
				echo 'Invalid SFTP Path' , BR;
				exit(1);
			}
		}
		break;
	default:
		break;
	}
}
else {

}

//Run LFTP for backups
if($params['protocol'] === 'ftp' || $params['protocol'] === 'ftps') {
	run_lftp();
}
elseif($params['protocol'] === 'sftp') {
	run_rsync();
}

if($params['incremental'] === false) {
	if(!file_exists($params['localpath'] . '/' . $params['account'] . '/' . $params['sitename'] . '/.git')) {
		//create_repo();
	}
}

//Commit to Git
//git_commit();

//Clear shell history
//clear_history();

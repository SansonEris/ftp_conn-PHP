<?php
	$ftp_server = "192.168.1.1";
	$ftp_user_name = "ftpuser";
	$ftp_user_pass = "";	

	echo "welcome to the ftp user connection\n";
	echo "do you want to use default ftp_ settings?:";
	echo "\n\nserver: ". $ftp_server;
	echo "\nusername: " . $ftp_user_name . "\n\n";
	do{
		failCnn:
			$settings = readline("[y][n]");
			if($settings == "y"){
				$ftp_server = "192.168.1.1";
				$ftp_user_name = "ftpuser";
				$ftp_user_pass = "";	
			}
			else if ($settings == "n"){
				$ftp_server = readline("\nftp server ip:\n");
				$ftp_user_name = readline("ftp username:\n");
				$ftp_user_pass = readline("ftp user pass:\n");
			}
			else{echo "invalid charter"; goto failCnn;} 
		
	$conn_id = ftp_connect($ftp_server) or die ("could not connect to $ftp_server");
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

	}while(!$login_result);

	echo "-----------------------------------------------------------\n";
	echo "\nSuccess!";

	dir:
		echo "\nthis is your current directory:\n ". ftp_pwd($conn_id) . "\n\n";
		echo "\n\nfiles in the current directory:";
		$contents = ftp_nlist($conn_id, ".");
		var_dump($contents);

		$dir_input = readline("select directory:\n");
		$dir = ftp_chdir($conn_id, $dir_input);

		if($dir) echo "\nsucces\n";
		else echo "\n\n\n".$dir_input."  ----------is not a directory----------\n\n\n";

		echo ftp_pwd($conn_id);
		$contents = ftp_nlist($conn_id, ".");
		var_dump($contents);

		c:
		echo "\nmove to another directory?\n";
		echo "please select 'n' only when you are in the right directory\n";
			$check = readline("[y][n]");
			switch ($check) {
				case 'y':
					goto dir;
					break;
				case 'n':
					break;
				default:
					echo "\n\n\n----------invalid charter----------\n\n\n";
					goto c;
					break;
			}
	
	nofile:
		$remote_file = readline("enter the RemoteFileName:\n");
		nwname:
			echo "\ndo you want to rename the file(on local)?\n";
			$rename = readline("[y][n]");
			switch ($rename) {
				case 'y':
					$local_file = readline("\nlocal_file_name:\n");
					break;
				case 'n':
					$local_file = $remote_file;
					break;
				default:
					echo "\n\n\n----------invalid charters----------\n\n\n"; 
					goto nwname;
					break;
			}

		$mode = FTP_BINARY;

		if(ftp_get($conn_id, $local_file, $remote_file, $mode)){
			 echo "\nSuccessfully written to $local_file\n";
			 echo "\nthe file directory is:\n\n\n";
			 echo dirname(__FILE__)."/\n\n\n";
		} else {
			echo "\n\n\n\nDownload failed\n\n\n\n";
			goto nofile;
		}
		inv:
		$exit =readline("\nchange directory[c]\n|choose another file[f]\n|quit[q]\n");
		switch ($exit) {
			case 'c':
				goto dir;
				break;
			case 'f':
				goto nofile;
				break;
			case 'q':
				break;
			default:
				echo "invalid charter";
				goto inv;
				break;
		}
	ftp_close($conn_id);

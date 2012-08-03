<?php
$tmpDir = "/tmp/druptask/";
$workingDir = rtrim(shell_exec("pwd"))."/";

if(!count($params))
{
  help();
  exit(1);
}
switch($params[0])
{
  case 'help':
  help();
  exit(1);
}

if(file_exists($workingDir.$params[0])){
    echo "Directory ".$workingDir.$params[0]." allready exists, aborting...\n";
    exit(1);
}
if(! file_exists($workingDir.$configFileName)){
    echo "Ini file missing : ".$workingDir.$configFileName.", aborting...\n";
    echo "Run druptask inifile to generate a config ini file and edit it with your correct values\n";
    exit(1);
}

$confs = parse_ini_file($workingDir.$configFileName);

/*
 * DOWNLOAD SOURCES AND MOVE THEM INTO THE DIRECTORY ASKED
 */
$cmd = array();
$cmd[] = "rm -rf ".$tmpDir;
$cmd[] = "mkdir ".$tmpDir;
$cmd[] = "chmod -R 777 ".$tmpDir;
$cmd[] = "cd ".$tmpDir;
$cmd[] = "drush dl drupal";

echo taskExecute(implode(";",$cmd),"Prepare tmp env and run drush");


$drupDirName =  shell_exec("ls ".$tmpDir);
$drupDirName = explode("\n",$drupDirName);
$drupDirName = $drupDirName[0];


$cmd = array();
$cmd[] = "mv ".$tmpDir.$drupDirName." ".$workingDir.$params[0];
echo taskExecute(implode(";",$cmd),"Rename Drupal directory");

/*
 * INSTALL DRUPAL PROCESS
 */
$cmd = array();
$cmd[] = "cd ".$workingDir.$params[0];
$cmd[] = "drush -y si standard --db-url=mysql://".$confs["user"].":".$confs["pwd"]."@".$confs["ip"].":".$confs["port"]."/".$params[0]." --db-su=".$confs["suser"]." --db-su-pw=".$confs["spwd"]." --site-name='".$params[0]."'";

echo taskExecute(implode(";",$cmd),"Process Drupal install with drush");

/*
 * MODULES INSTALL
 */
if($confs["modules"] != ""){
    echo taskExecute("cd ".$workingDir.$params[0].";drush -y dl ".$confs["modules"],"Downloading modules with drush");
    echo taskExecute("cd ".$workingDir.$params[0].";drush -y en ".$confs["modules"],"Installing modules with drush");
}
if($confs["submodules"] != ""){
    echo taskExecute("cd ".$workingDir.$params[0].";drush -y en ".$confs["submodules"],"Installing submodules with drush");
}

$cmd = array();
$cmd[] = "chmod -R 775 ".$workingDir.$params[0]."/sites/default/";
$cmd[] = "chmod -R 555 ".$workingDir.$params[0]."/sites/default/settings.php";
$cmd[] = "chmod 755 ".$workingDir.$params[0]."/sites/default/";

echo taskExecute(implode(";",$cmd),"Set rights");

printf("\033[0;31m%s\033[0m\n", "Please run that command as root\nchown -R :".$apacheRunGroup." ".$workingDir.$params[0]."\n");

function help()
{
  echo "Argument should be the directory name within you want to install the drupal website.\n";
}
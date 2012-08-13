<?php

$workingDir = rtrim(shell_exec("pwd"))."/";


if(isset($params[0])){
    switch($params[0])
    {
      case 'help':
        help();
        exit(1);
    }
}

if(file_exists($workingDir.$configFileName)){
    taskExecute("rm ".$workingDir.$configFileName,"Deleting current config file".$workingDir.$configFileName);
}

printf("\033[0;33m%s\033[0m\n", "Example file being generated...\n");

$iniFileContent = "; Ini file for druptask
; Needed for automating DB install
[DB Daemon]
; ip or host of your database server
ip = 0.0.0.0
; port of your database server
port = 3306

[DB Super User]
; super database's user login, needed to create project database
suser = root
spwd =

[DB Application User]
; drupal database'user login
user = root
pwd =

[Admin account]
;
drupAdmUser = admin
drupAdmPwd = admin

[Modules]
; modules to install, separated by comma
modules = features,views,l10n_update,variable,i18n,token,entity,entityreference
; submodules to enable
submodules = views_ui,variable_store,variable_realm,variable_advanced,variable_admin,i18n_menu

[Post Install]
; post install shell commands to run comma separated, you can use %installDir% for targeting installation directory
postInstallShellCmd =
";
if(file_put_contents($workingDir.$configFileName,$iniFileContent)){
    printf("\033[0;33m%s\033[0m\n", "Config file ".$workingDir.$configFileName." generated successfully\n");
}
else{
    printf("\033[0;33m%s\033[0m\n", "Error while writing example file\n");
}


function help()
{
  echo "No Argument needed. A config file will be created here, fill it with your params\n";
}
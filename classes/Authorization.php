<?php

if (!defined('READFILE')) {
    exit('Error, you have not access to this file');
}

class Authorization
{

    protected $usersList;

    public function __construct($pathToUsersBase)
    {
        $this->usersList = $this->prepareUsersList($pathToUsersBase);
    }

    protected function prepareUsersList($pathToUsersBase)
    {
        $errors = array();
        $result = array();

        if (empty($pathToUsersBase)) {
            throw new Exception('Empty "pathToUsersBase" in config.php', 700);
        }

        if (file_exists($pathToUsersBase) && is_readable($pathToUsersBase)) {
            libxml_use_internal_errors(true);
            $xmlObject = simplexml_load_file($pathToUsersBase);

            if (empty($xmlObject)) {
                $errors[] = "Errors by parse '{$pathToUsersBase}':\n";

                foreach (libxml_get_errors() as $error) {
                    $errors[] = $error->message;
                }

                throw new Exception(implode("\r\n", $errors), 700);
            }
        } else {
            throw new Exception("The File '{$pathToUsersBase}' does not exist or not readable", 700);
        }

        foreach ($xmlObject->user as $user) {
            $login = reset($user->login);
            $password = reset($user->password);

            $result[$login] = $password;
        }

        return $result;
    }

    public function chechUser($login, $md5_password)
    {
        if (!array_key_exists($login, $this->usersList)) {
            throw new Exception("The login '{$login}' does not exist", 600);
        }

        if (strtoupper($md5_password) !== strtoupper(md5($this->usersList[$login]))) {
            throw new Exception("The password is wrong", 600);
        }
    }

}

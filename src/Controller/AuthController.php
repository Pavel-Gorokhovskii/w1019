<?php

namespace Controller;

use Core\Config;
use Model\AuthModel;
use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;
use View\View;

class AuthController extends AbstractController
{


    private $table;
    private $tableName = 'users';

    public function __construct(View $view)
    {
        parent::__construct($view);
        $this->table = new AuthModel(
            $this->tableName,
            DB::Link([
                'host' => Config::MYSQL_HOST,
                'username' => Config::MYSQL_USER_NAME,
                'password' => Config::MYSQL_PASSWORD,
                'dbname' => Config::MYSQL_DATABASE
            ])
        );
    }

    public function actionLoginForm()
    {
        $this
            ->view
            ->setFolder('login')
            ->setTemplate('loginForm')
            ->setLayout('planeLayout')
            ->addData(['action' => "?action=login&type=" . $this->getClassName()]);
    }

    public function actionLogin($httpData)
    {

        $kod = $this
            ->table
            ->checkUser(
                $httpData['post']['login'],
                md5($httpData['post']['password'] . Config::SALT)
            );

        if (empty($kod)) {
            $this->redirect("?action=loginform&type=" . $this->getClassName());
        } else {
            $_SESSION['user'] = $kod;
            $this->redirect('/');
        }
    }

    public function actionLogout($httpData)
    {
        unset($_SESSION['user']);
        $this->redirect('/');
    }
}

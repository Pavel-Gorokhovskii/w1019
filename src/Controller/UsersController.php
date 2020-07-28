<?php

namespace Controller;

use Core\Config;
use Model\UsersModel;
use TexLab\MyDB\DB;
use View\View;

class UsersController extends AbstractTableController
{

    protected $tableName = "users";

    public function __construct(View $view)
    {
        parent::__construct($view);
        $this->table = new UsersModel(
            $this->tableName,
            DB::Link([
                'host' => Config::MYSQL_HOST,
                'username' => Config::MYSQL_USER_NAME,
                'password' => Config::MYSQL_PASSWORD,
                'dbname' => Config::MYSQL_DATABASE
            ])
        );
    }

    public function actionShow(array $data)
    {
        parent::actionShow($data);
        $this
            ->view
            ->setFolder('users')
            ->addData([
                'groupNames' => $this->table->getGroupNames(),
                'table' => $this
                    ->table
                    ->reset()
                    ->setPageSize(Config::PAGE_SIZE)
                    ->getUsersPage($data['get']['page'] ?? 1)
            ]);
    }
    public function actionShowEdit(array $data)
    {
        parent::actionShowEdit($data);
        $this
            ->view
            ->setFolder('users')
            ->addData([
                'groupNames' => $this->table->getGroupNames()
            ]);
        ;
    }
    public function actionAdd(array $data)
    {
        $data['post']['password'] = md5($data['post']['password'] . Config::SALT);
        parent::actionAdd($data);
    }
    public function actionEdit(array $data)
    {
        $data['post']['password'] = md5($data['post']['password'] . Config::SALT);
        parent::actionEdit($data);
    }
}

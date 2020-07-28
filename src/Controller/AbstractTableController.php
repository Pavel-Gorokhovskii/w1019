<?php

namespace Controller;

use Core\Config;
use Model\CRUDInterface;
use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;
use View\View;
use Model\DbTable;
use mysqli;
use Model\DbConection;

abstract class AbstractTableController extends AbstractController implements CRUDControllerInterface
{
    protected $table; // CRUDInterface
    protected $view; // View
    protected $tableName;

    public function __construct(View $view)
    {
        $this->table = new DbEntity(
            $this->tableName,
            DB::Link([
                'host' => Config::MYSQL_HOST,
                'username' => Config::MYSQL_USER_NAME,
                'password' => Config::MYSQL_PASSWORD,
                'dbname' => Config::MYSQL_DATABASE
            ])
        );

        parent::__construct($view);
        $this->view->setFolder('table');
    }

    public function actionShow(array $data)
    {


        $this
            ->view
            ->setTemplate('show')
            ->setData([
                'table' => $this
                    ->table
                    ->reset()
                    ->setPageSize(Config::PAGE_SIZE)
                    ->getPage($data['get']['page'] ?? 1),
                'fields' => array_diff($this->table->getColumnsNames(), ['id']),
                'comments' => $this->table->getColumnsComments(),
                'type' => $this->getClassName(),
                'pageCount' => $this->table->pageCount()
            ]);
    }

    public function actionAdd(array $data)
    {

        $this->table->add($data['post']);

        $this->redirect('?action=show&type=' . $this->getClassName());
    }

    public function actionDel(array $data)
    {


        if (isset($data['get']['id'])) {
            $this->table->del(['id' => $data['get']['id']]);
        }
        $this->redirect('?action=show&type=' . $this->getClassName());
    }

    public function actionShowEdit(array $data)
    {

        $id = $data['get']['id'];

        $viewData = $this->table->get(['id' => $id])[0];

        unset($viewData['id']); // Del id

        $this
            ->view
            ->setTemplate('edit')
            ->setData([
                'fields' => $viewData,
                'id' => $id,
                'type' => $this->getClassName(),
                'comments' => $this->table->getColumnsComments()
            ]);
    }

    public function actionEdit(array $data)
    {


        $editData = $data['post'];
        unset($editData['id']);



        $this->table->edit(['id' => $data['post']['id']], $editData);
        $this->redirect('?action=show&type=' . $this->getClassName());
    }
}

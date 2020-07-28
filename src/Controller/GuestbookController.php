<?php

namespace Controller;

use Core\Config;

class GuestbookController extends AbstractTableController
{
    protected $tableName = "guestbook";

    public function actionShow(array $data)
    {
        $this
            ->view
            ->setFolder('guestbook')
            ->setTemplate('show')
            ->setData([
                'table' => $this->table->get(),
                'fields' => array_diff($this->table->getColumnsNames(), ['id']),
                'comments' => $this->table->getColumnsComments(),
                'type' => $this->getClassName()
            ]);
    }
}

<?php

namespace Controller;

interface CRUDControllerInterface
{
    public function actionShow(array $data);

    public function actionAdd(array $data);

    public function actionDel(array $data);

    public function actionShowEdit(array $data);

    public function actionEdit(array $data);
}

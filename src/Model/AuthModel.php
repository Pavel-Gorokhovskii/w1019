<?php

namespace Model;

use TexLab\MyDB\DbEntity;

class AuthModel extends DbEntity
{
    public function checkUser(string $login, string $password)
    {
        return $this
            ->reset()
            ->setSelect('`cod`, `FIO`, `group`.`name`')
            ->setFrom('`users`,`group`')
            ->setWhere('`users`.`group_id` = `group`.`id`')
            ->addWhere("BINARY `users`.`login`= '$login'")
            ->addWhere("BINARY `users`.`password`= '$password'")
            ->get()[0];
    }
}

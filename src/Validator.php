<?php

namespace Hexlet\Slim\Example;

class Validator
{
    const CANT_BE_BLANK = 'Can\'t be blank';

    public function validate($user)
    {
        $errors = [];
        if (empty($user['nickname'])) {
            $errors['nickname'] = self::CANT_BE_BLANK;
        }

        if (empty($user['email'])) {
            $errors['email'] = self::CANT_BE_BLANK;
        }

        return $errors;
    }
}

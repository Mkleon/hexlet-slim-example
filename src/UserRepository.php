<?php

namespace Hexlet\Slim\Example;

class UserRepository
{
    private $_dir = __DIR__ . "/../repositories";
    private $_filename = 'test.json';

    public function __construct($filename = null)
    {
        $this->_filename = $filename ?? $this->_filename;
    }

    private function getFullPath()
    {
        return "{$this->_dir}/{$this->_filename}";
    }

    public function save($data)
    {
        $content = $this->all();
        $data['id'] = uniqid();
        $content[] = $data;

        file_put_contents(
            $this->getFullPath(),
            json_encode($content)
        );
    }

    public function find(string $id)
    {
        $content = $this->all();
        $user = collect($content)->firstWhere('id', $id);

        return $user;
    }

    public function all()
    {
        $content = file_get_contents($this->getFullPath());

        return json_decode($content, true);
    }
}
<?php namespace Bmartel\Workshop\Builders;


class Console extends Base
{

    protected $builderType = 'Console';

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    protected function getTemplate($name, $data = [])
    {
        return 'console/console.stub';
    }
}
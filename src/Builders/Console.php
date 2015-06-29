<?php namespace Bmartel\Workshop\Builders;


class Console extends Base
{

    protected $pluralizeNamespace = false;

    protected $builderType = 'Console';

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    protected function getTemplate($name, $data = [])
    {
        return 'console.stub';
    }
}
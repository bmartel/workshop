<?php namespace Bmartel\Workshop\Builders;


class Middleware extends Base
{

    protected $pluralizeNamespace = false;

    protected $builderType = 'Middleware';

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    protected function getTemplate($name, $data = [])
    {
        return 'middleware.stub';
    }

}

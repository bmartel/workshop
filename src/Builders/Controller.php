<?php namespace Bmartel\Workshop\Builders;


class Controller extends Base
{

    protected $builderType = 'Controller';

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    protected function getTemplate($name, $data = [])
    {
        if(!empty($data['plain'])) {
            return 'controller/plain.stub';
        }

        return 'controller/resource.stub';
    }

}

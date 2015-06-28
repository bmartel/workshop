<?php namespace Bmartel\Workshop\Builders;


class Model extends Base
{

    protected $builderType = 'Model';

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    protected function getTemplate($name, $data = [])
    {
        return 'model/model.stub';
    }

}

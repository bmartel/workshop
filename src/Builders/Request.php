<?php namespace Bmartel\Workshop\Builders;


class Request extends Base
{

    protected $builderType = 'Request';

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    protected function getTemplate($name, $data = [])
    {
        return 'request.stub';
    }

}

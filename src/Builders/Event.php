<?php namespace Bmartel\Workshop\Builders;


class Event extends Base
{

    protected $builderType = 'Event';

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    protected function getTemplate($name, $data = [])
    {
        return 'event.stub';
    }

}

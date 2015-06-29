<?php namespace Bmartel\Workshop\Builders;


class Listener extends Base
{

    protected $builderType = 'Listener';

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    protected function getTemplate($name, $data = [])
    {
        if(!empty($data['queued'])) {
            return 'queued.stub';
        }

        return 'plain.stub';
    }

    public function getTemplatePath()
    {
        return parent::getTemplatePath() . '/listener';
    }


}

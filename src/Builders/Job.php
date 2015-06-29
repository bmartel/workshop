<?php namespace Bmartel\Workshop\Builders;


class Job extends Base
{

    protected $builderType = 'Job';

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
        return parent::getTemplatePath() . '/job';
    }


}

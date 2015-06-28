<?php namespace Bmartel\Workshop\Support;


class InputData
{

    /**
     * Formats a string into a data array
     *
     * @param $data
     * @return array
     */
    public function parse($data)
    {

        $results = [];

        // format "key1:value1,key2:value2..."
        foreach (explode(',', $data) as $set) {

            if (stristr($set, ':')) {

                $arrData = explode(':', $set);

                $results[$arrData[0]] = $arrData[1];
            }
        }

        return $results;
    }
}

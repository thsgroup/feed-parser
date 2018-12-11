<?php

namespace Thsgroup\FeedParser\Validator\Constraint;

class UrlExistsConstraint extends Constraint implements ConstraintInterface
{

    public function check($data)
    {
        $headers = @get_headers($data);
        if (strpos($headers[0], '200') !== false) {
            return true;
        }

        $this->setError(
            array(
                'code' => 'invalidInputURL',
                'message' => 'Input URL [' . $data . '] is not responding or is invalid'
            ));

        return false;
    }
}

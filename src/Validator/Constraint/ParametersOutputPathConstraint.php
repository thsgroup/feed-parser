<?php

namespace Thsgroup\FeedParser\Validator\Constraint;

class ParametersOutputPathConstraint extends Constraint implements ConstraintInterface
{
    public function check($data)
    {
        if (empty($data) || ctype_print($data) === false) {
            $this->setError(
                array(
                    'code' => 'invalidOutputPath',
                    'message' => 'Invalid output path [' . $data . ']'
                ));
            return false;
        }

        return true;
    }
}

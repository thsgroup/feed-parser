<?php

namespace Thsgroup\FeedParser\Validator\Constraint;

class FileExistsConstraint extends Constraint implements ConstraintInterface
{

    public function check($data)
    {
        if (!file_exists($data)) {
            $this->setError(
                array(
                    'code' => 'invalidInputFile',
                    'message' => 'Input file [' . $data . '] does not exist'
                ));

            return false;
        }

        return true;
    }
}

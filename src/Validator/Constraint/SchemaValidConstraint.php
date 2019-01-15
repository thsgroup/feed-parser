<?php

namespace Thsgroup\FeedParser\Validator\Constraint;

use JsonSchema\Validator;

class SchemaValidConstraint extends Constraint implements ConstraintInterface
{

    private $parameters;

    /**
     * SchemaValidConstraint constructor.
     * @param $parameters
     */
    public function __construct($parameters)
    {
        parent::__construct();
        $this->parameters = $parameters;
    }

    /**
     * @param $data
     * @return bool
     */
    public function check($data)
    {
        $validator = new Validator;
        $validator->validate($data, (object)['$ref' => 'file://' . realpath($this->parameters['validationSchema'])]);

        $errors = array();

        if (!$validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                $errors[] = $error['property'] . ' -> ' . $error['message'];
            }

            $this->setError(
                array(
                    'code' => 'notValidAgainstSchema',
                    'message' => 'Not valid against schema [' . $this->parameters['validationSchema'] . '], errors: ' . implode(', ', $errors)
                ));

            return false;
        }

        return true;
    }
}

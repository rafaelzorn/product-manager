<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param array $validations
     *
     * @return string $messages
     */
    public function validationMessages(array $validations): string
    {
        $messages = [];

        if (empty($validations)) {
            return $messages;
        }

        foreach ($validations as $key => $validation) {
            $attribute = str_replace('_', ' ', $key);
            $message   = [str_replace(':attribute', $attribute, trans($validation))];

            $messages[$key] = $message;
        }

        $messages = json_encode($messages);

        return $messages;
    }
}

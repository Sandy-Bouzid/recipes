<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;


#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWords extends Constraint
{
    public function __construct(
        public string $message = 'This contains a banned word "{{ banWord }}".',
        public array $banWords = ['spam', 'script'],
        ?array $groups = null,
        mixed $payload = null
    ) 
    {
        parent::__construct(null, $groups, $payload);
    }
}

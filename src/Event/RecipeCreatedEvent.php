<?php

namespace App\Event;

use App\Entity\Recipe;

class RecipeCreatedEvent
{

    public function __construct(public readonly Recipe $data){

    }

}
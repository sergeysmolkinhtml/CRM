<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $category;
    private $project;
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->seed([

        ]);
    }

}

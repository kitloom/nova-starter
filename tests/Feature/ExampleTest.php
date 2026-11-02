<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example: the home page answers.
     */
    public function test_the_home_page_answers(): void
    {
        $this->get('/')->assertOk();
    }
}

<?php

namespace Tests\Suites\Feature;

use Styde\Enlighten\ExampleGroup;
use Tests\TestCase;

class DashboardViewTest extends TestCase {

    /** @test */
    public function get_dashboard_view(): void
    {
        ExampleGroup::create(['class_name' => 'Tests\Api\UserTest', 'title' => 'User tests']);

        $response = $this->get(route('enlighten.dashboard'));

        $response->assertOk();
        $response->assertViewIs('enlighten::dashboard.index');
    }

    /** @test */
    public function redirect_to_intro_page_if_no_data_has_been_recorded_yet(): void
    {
        $response = $this->get(route('enlighten.dashboard'));

        $response->assertRedirect(route('enlighten.intro'));
    }

    /** @test */
    public function get_test_groups_by_test_suite(): void
    {
        ExampleGroup::create(['class_name' => 'Tests\Api\UserTest', 'title' => 'User tests']);
        ExampleGroup::create(['class_name' => 'Tests\Api\PostTest', 'title' => 'Post tests']);
        ExampleGroup::create(['class_name' => 'Tests\Feature\UserTest', 'title' => 'Users Feature tests']);
        ExampleGroup::create(['class_name' => 'Tests\Unit\FilterTest', 'title' => 'Filter tests']);

        $response = $this->get(
            route('enlighten.dashboard', ['suite' => 'api'])
        );

        $response->assertOk();

        $response->assertViewHas('suite');
        $response->assertSeeText('User tests');
        $response->assertSeeText('Post tests');
        $response->assertDontSeeText('Users Feature tests');
        $response->assertDontSeeText('Filter tests');
    }

    /** @test */
    public function return_first_test_suite_groups_if_no_suite_provided(): void
    {
        $this->withoutExceptionHandling();
        ExampleGroup::create(['class_name' => 'Tests\Api\UserTest', 'title' => 'User tests']);
        ExampleGroup::create(['class_name' => 'Tests\Api\PostTest', 'title' => 'Post tests']);
        ExampleGroup::create(['class_name' => 'Tests\Feature\UserTest', 'title' => 'Users Feature tests']);
        ExampleGroup::create(['class_name' => 'Tests\Unit\FilterTest', 'title' => 'Filter tests']);

        $response = $this->get(route('enlighten.dashboard'));

        $response->assertOk();

        $response->assertViewHas('suite');
        $response->assertSeeText('User tests');
        $response->assertSeeText('Post tests');
        $response->assertDontSeeText('Users Feature tests');
        $response->assertDontSeeText('Filter tests');
    }
}
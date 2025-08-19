<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_customers()
    {
        Customer::factory()->count(3)->create();

        $response = $this->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertViewHas('customers'); //ビューにcustomers変数がある
        $this->assertCount(3, $response->viewData('customers'));
    }

    public function test_can_create_a_customer()
    {
        $data = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'address' => '東京',
        ];

        $response = $this->post(route('customers.store'), $data);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', ['email' => 'test@example.com']);
    }

    public function test_validates_required_fields_on_create()
    {
        $response = $this->post(route('customers.store'), []);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_can_update_a_customer()
    {
        $customer = Customer::factory()->create();

        $data = ['name' => '更新後の名前'];

        $response = $this->put(route('customers.update', $customer), $data);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => '更新後の名前',
        ]);
    }

    public function test_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Advisory;
use App\Models\Municipality;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AccessAndAdvisoryTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake(['api.open-meteo.com/*' => Http::response(array_fill(0, Municipality::count(), [
            'current' => ['temperature_2m' => 30.5, 'relative_humidity_2m' => 80],
            'daily' => ['precipitation_sum' => [60.0]],
        ]))]);
    }

    private function admin(): User
    {
        return User::where('role', 'admin')->first();
    }

    private function officer(): User
    {
        return User::where('email', 'officer@kaani.test')->first();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/officer/advisories')->assertRedirect('/login');
    }

    public function test_each_role_lands_on_its_own_dashboard(): void
    {
        $this->post('/login', ['email' => 'admin@kaani.test', 'password' => 'password'])->assertRedirect('/');
        $this->get('/')->assertRedirect('/admin/dashboard');
        $this->post('/logout');

        $this->post('/login', ['email' => 'officer@kaani.test', 'password' => 'password']);
        $this->get('/')->assertRedirect('/officer/dashboard');
    }

    public function test_roles_cannot_open_each_others_pages(): void
    {
        $this->actingAs($this->officer())->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($this->officer())->get('/admin/advisories')->assertForbidden();
        $this->actingAs($this->admin())->get('/officer/dashboard')->assertForbidden();
        $this->actingAs($this->admin())->get('/officer/advisories')->assertForbidden();
    }

    public function test_invalid_advisory_is_rejected_by_server(): void
    {
        $this->actingAs($this->officer())
            ->post('/officer/advisories', ['municipality_id' => '', 'crop' => 'Mango', 'message' => 'short'])
            ->assertSessionHasErrors(['municipality_id', 'crop', 'message']);

        $this->assertSame(0, Advisory::count());
    }

    public function test_officer_submits_and_admin_approves(): void
    {
        $this->actingAs($this->officer())
            ->post('/officer/advisories/draft', ['municipality_id' => 1, 'crop' => 'Rice'])
            ->assertSessionHasInput('message');

        $this->actingAs($this->officer())->post('/officer/advisories', [
            'municipality_id' => 1,
            'crop' => 'Rice',
            'message' => 'Clear canals and drainage before the heavy rain.',
        ])->assertRedirect('/officer/advisories');

        $advisory = Advisory::first();
        $this->assertSame('pending', $advisory->status);
        $this->assertSame($this->officer()->id, $advisory->user_id);

        $this->actingAs($this->admin())->patch("/admin/advisories/{$advisory->id}/approve");

        $this->assertSame('approved', $advisory->fresh()->status);
        $this->assertSame($this->admin()->id, $advisory->fresh()->reviewed_by);
    }

    public function test_reject_requires_a_note(): void
    {
        $advisory = $this->officer()->advisories()->create([
            'municipality_id' => 1, 'crop' => 'Rice', 'risk_level' => 'High', 'message' => 'Test advisory message here.',
        ]);

        $this->actingAs($this->admin())->patch("/admin/advisories/{$advisory->id}/reject")
            ->assertSessionHasErrors('review_note');
        $this->assertSame('pending', $advisory->fresh()->status);
    }

    public function test_officer_only_sees_own_advisories(): void
    {
        $other = User::where('email', 'officer2@kaani.test')->first();
        $other->advisories()->create([
            'municipality_id' => 1, 'crop' => 'Corn', 'risk_level' => 'Low', 'message' => 'Advisory from another officer.',
        ]);

        $this->actingAs($this->officer())->get('/officer/advisories')
            ->assertOk()
            ->assertDontSee('Advisory from another officer.');
    }
}

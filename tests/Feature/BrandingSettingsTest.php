<?php
namespace Tests\Feature;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class BrandingSettingsTest extends TestCase
{
 public function test_branding_settings_are_protected(): void
 {
  $this->get('/admin/branding')->assertRedirect('/admin/login');
 }
 public function test_admin_can_upload_logo_and_it_appears_publicly(): void
 {
  Storage::fake('public');
  $user = User::where('email', 'admin@nexport.test')->firstOrFail();
  $this->actingAs($user)->put('/admin/branding', ['site_name'=>'NexPort Test','site_tagline'=>'Global Supply','logo'=>UploadedFile::fake()->image('logo.png',320,100)])->assertRedirect();
  $path = Setting::getValue('site_logo');
  $this->assertNotNull($path);
  $this->get('/')->assertOk()->assertSee($path)->assertSee('NexPort Test');
 }
}

<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class AdminBrandingController extends Controller {
 public function edit(){return view('admin.branding',['logo'=>Setting::getValue('site_logo'),'siteName'=>Setting::getValue('site_name','NEXPORT'),'tagline'=>Setting::getValue('site_tagline','GLOBAL TRADE')]);}
 public function update(Request $request){$data=$request->validate(['site_name'=>'required|string|max:80','site_tagline'=>'nullable|string|max:100','logo'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:2048','remove_logo'=>'nullable|boolean']);Setting::put('site_name',$data['site_name']);Setting::put('site_tagline',$data['site_tagline']??'');$old=Setting::getValue('site_logo');if($request->boolean('remove_logo')){$this->remove($old);Setting::put('site_logo',null,'image');}elseif($request->hasFile('logo')){$this->remove($old);Setting::put('site_logo',Storage::url($request->file('logo')->store('branding','public')),'image');}return back()->with('success',app()->isLocale('fr')?'Identité visuelle mise à jour avec succès.':'Branding updated successfully.');}
 private function remove(?string $path):void{if($path&&str_starts_with($path,'/storage/'))Storage::disk('public')->delete(substr($path,9));}
}

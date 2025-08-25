<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class SettingController extends Controller
{
  public function edit()
  {
    $settings = config('settings.settings');
    // dd($settings);
    $sections = Page::with('translations')->get();

    //  dd($settings);

    return view('admin.settings.edit', compact(['settings', 'sections']));
  }

  public function update(Request $request)
  {
    // Load the existing settings configuration
    $settings = config('settings.settings');
    if ($request->has('translatables')) {
      foreach ($request->translatables as $key => $value) {
        if (is_array($value)) {

          $settings[$key] = config('settings.settings.'.$key);
          $filename = base_path('config/settings/settings.php');
          $settings[$key]['value'] = $value;
          if (is_file($filename)) {
            file_put_contents($filename, arrayToPhpArray($settings));
          }
        }
      }
    }
    if ($request->has('nonTranslatables')) {
      foreach ($request->nonTranslatables as $key1 => $value1) {
        if (! is_array($value1)) {
          $settings[$key1] = config('settings.settings.'.$key1);
          $filename = base_path('config/settings/settings.php');
          $settings[$key1]['value'] = $value1;
          if (is_file($filename)) {
            file_put_contents($filename, arrayToPhpArray($settings));
          }
        }
      }
    }

    // Update the settings configuration file
    $filename = base_path('config/settings/settings.php');
    if (is_file($filename)) {
      file_put_contents($filename, arrayToPhpArray($settings));
    }

    // Redirect with a success message
    return redirect('/'.app()->getLocale().'/admin/settings/edit')->with('message', trans('admin.successfully_saved'));
  }
}

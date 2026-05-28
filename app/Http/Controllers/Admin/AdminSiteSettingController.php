<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdminSiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.pages.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->input('settings', []);

        foreach ($data as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            if (!$setting) continue;

            // Normalise booleans
            if ($setting->type === 'boolean') {
                $value = $value ? '1' : '0';
            }

            $setting->update(['value' => $value]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Site settings saved.');
    }
}

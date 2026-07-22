<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $files = $request->file('settings', []);
        $removeKeys = $request->input('remove_settings', []);

        // Handle image removals first
        foreach ($removeKeys as $key => $shouldRemove) {
            if (!$shouldRemove) continue;
            $setting = SiteSetting::where('key', $key)->first();
            if ($setting && $setting->type === 'image') {
                $setting->update(['value' => '/images/og-image.jpg']);
            }
        }

        // Ensure image settings are processed even when no text value is submitted
        foreach ($files as $key => $file) {
            if (!isset($data[$key])) {
                $data[$key] = null;
            }
        }

        foreach ($data as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            if (!$setting) continue;

            // Handle image uploads
            if ($setting->type === 'image' && isset($files[$key]) && $files[$key]->isValid()) {
                $file = $files[$key];
                $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                      . '-' . time()
                      . '.' . $file->getClientOriginalExtension();
                $file->storeAs('media', $name, 'public');
                $value = '/storage/media/' . $name;
            }

            // Normalise booleans
            if ($setting->type === 'boolean') {
                $value = $value ? '1' : '0';
            }

            // JSON type: encode array values
            if ($setting->type === 'json' && is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            // endorsement_full_body: bilingual textarea submits as array, store as JSON
            if ($key === 'endorsement_full_body' && is_array($value)) {
                $value = json_encode(array_filter($value, fn($v) => $v !== null && $v !== ''), JSON_UNESCAPED_UNICODE);
            }

            $setting->update(['value' => $value]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Site settings saved.');
    }
}

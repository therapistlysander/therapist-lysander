<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = SiteSetting::all()->groupBy('group');

        return response()->json(['data' => $settings]);
    }

    public function upsert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'settings'          => ['required', 'array'],
            'settings.*.group'  => ['required', 'string', 'max:50'],
            'settings.*.key'    => ['required', 'string', 'max:100'],
            'settings.*.value'  => ['nullable'],
            'settings.*.type'   => ['sometimes', 'string', 'in:string,boolean,json,text'],
            'settings.*.label'  => ['nullable', 'string', 'max:150'],
        ]);

        $saved = [];
        foreach ($validated['settings'] as $item) {
            $value = is_array($item['value']) ? json_encode($item['value']) : $item['value'];

            $saved[] = SiteSetting::updateOrCreate(
                ['key' => $item['key']],
                [
                    'group' => $item['group'],
                    'value' => $value,
                    'type'  => $item['type'] ?? 'string',
                    'label' => $item['label'] ?? null,
                ]
            );
        }

        return response()->json(['data' => $saved]);
    }
}

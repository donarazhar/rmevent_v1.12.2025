<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::ordered()->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            foreach ($request->except('_token', '_method') as $key => $value) {
                // Handle file uploads
                if ($request->hasFile($key)) {
                    $setting = Setting::where('key', $key)->first();
                    
                    // Delete old file if exists
                    if ($setting && $setting->value) {
                        \Storage::disk('public')->delete($setting->value);
                    }
                    
                    $path = $request->file($key)->store('settings', 'public');
                    $value = $path;
                }

                Setting::set($key, $value);
            }

            // Clear cache
            Setting::clearCache();

            return redirect()
                ->back()
                ->with('success', 'Pengaturan berhasil disimpan.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        try {
            Setting::clearCache();
            \Cache::flush();

            return redirect()
                ->back()
                ->with('success', 'Cache berhasil dibersihkan.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
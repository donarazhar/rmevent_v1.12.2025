<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $query = Slider::query();

        // Filter by placement
        if ($request->placement) {
            $query->forPlacement($request->placement);
        }

        // Filter by status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $sliders = $query->ordered()->paginate(20);

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        $placements = [
            Slider::PLACEMENT_HOMEPAGE => 'Homepage',
            Slider::PLACEMENT_EVENTS => 'Events Page',
            Slider::PLACEMENT_ABOUT => 'About Page',
            Slider::PLACEMENT_ALL => 'Semua Halaman',
        ];

        $buttonStyles = [
            Slider::BUTTON_PRIMARY => 'Primary',
            Slider::BUTTON_SECONDARY => 'Secondary',
            Slider::BUTTON_OUTLINE => 'Outline',
        ];

        $textPositions = [
            Slider::POSITION_LEFT => 'Kiri',
            Slider::POSITION_CENTER => 'Tengah',
            Slider::POSITION_RIGHT => 'Kanan',
        ];

        return view('admin.sliders.create', compact('placements', 'buttonStyles', 'textPositions'));
    }

    public function store(SliderRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('sliders', 'public');
                $data['image'] = $path;
            }

            // Handle mobile image upload
            if ($request->hasFile('image_mobile')) {
                $path = $request->file('image_mobile')->store('sliders', 'public');
                $data['image_mobile'] = $path;
            }

            Slider::create($data);

            return redirect()
                ->route('admin.sliders.index')
                ->with('success', 'Slider berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Slider $slider)
    {
        $placements = [
            Slider::PLACEMENT_HOMEPAGE => 'Homepage',
            Slider::PLACEMENT_EVENTS => 'Events Page',
            Slider::PLACEMENT_ABOUT => 'About Page',
            Slider::PLACEMENT_ALL => 'Semua Halaman',
        ];

        $buttonStyles = [
            Slider::BUTTON_PRIMARY => 'Primary',
            Slider::BUTTON_SECONDARY => 'Secondary',
            Slider::BUTTON_OUTLINE => 'Outline',
        ];

        $textPositions = [
            Slider::POSITION_LEFT => 'Kiri',
            Slider::POSITION_CENTER => 'Tengah',
            Slider::POSITION_RIGHT => 'Kanan',
        ];

        return view('admin.sliders.edit', compact('slider', 'placements', 'buttonStyles', 'textPositions'));
    }

    public function update(SliderRequest $request, Slider $slider)
    {
        try {
            $data = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($slider->image) {
                    \Storage::disk('public')->delete($slider->image);
                }
                $path = $request->file('image')->store('sliders', 'public');
                $data['image'] = $path;
            }

            // Handle mobile image upload
            if ($request->hasFile('image_mobile')) {
                // Delete old image
                if ($slider->image_mobile) {
                    \Storage::disk('public')->delete($slider->image_mobile);
                }
                $path = $request->file('image_mobile')->store('sliders', 'public');
                $data['image_mobile'] = $path;
            }

            $slider->update($data);

            return redirect()
                ->route('admin.sliders.index')
                ->with('success', 'Slider berhasil diupdate.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Slider $slider)
    {
        try {
            // Delete images
            if ($slider->image) {
                \Storage::disk('public')->delete($slider->image);
            }
            if ($slider->image_mobile) {
                \Storage::disk('public')->delete($slider->image_mobile);
            }

            $slider->delete();

            return redirect()
                ->route('admin.sliders.index')
                ->with('success', 'Slider berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle slider status
     */
    public function toggleStatus(Slider $slider)
    {
        $slider->update(['is_active' => !$slider->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $slider->is_active,
            'message' => 'Status berhasil diupdate.'
        ]);
    }

    /**
     * Reorder sliders
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'sliders' => 'required|array',
            'sliders.*.id' => 'required|exists:sliders,id',
            'sliders.*.order' => 'required|integer',
        ]);

        try {
            foreach ($request->sliders as $sliderData) {
                Slider::where('id', $sliderData['id'])
                    ->update(['order' => $sliderData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Urutan berhasil diupdate.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
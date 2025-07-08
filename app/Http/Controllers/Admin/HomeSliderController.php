<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSlider;
use Illuminate\Support\Facades\Storage;

class HomeSliderController extends Controller
{
    public function index()
    {
        $sliders = HomeSlider::orderBy('order')->get();
        return view('admin.home_sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.home_sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
        ]);
        $path = $request->file('image')->store('home-sliders', 'public');
        HomeSlider::create([
            'image_path' => $path,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'order' => HomeSlider::max('order') + 1,
        ]);
        return redirect()->route('admin.home-sliders.index')->with('success', 'Slider added successfully.');
    }

    public function edit($id)
    {
        $slider = HomeSlider::findOrFail($id);
        return view('admin.home_sliders.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = HomeSlider::findOrFail($id);
        $request->validate([
            'image' => 'nullable|image',
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
        ]);
        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
        ];
        if ($request->hasFile('image')) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
            $data['image_path'] = $request->file('image')->store('home-sliders', 'public');
        }
        $slider->update($data);
        return redirect()->route('admin.home-sliders.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy($id)
    {
        $slider = HomeSlider::findOrFail($id);
        if ($slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
        }
        $slider->delete();
        return redirect()->route('admin.home-sliders.index')->with('success', 'Slider deleted successfully.');
    }
} 
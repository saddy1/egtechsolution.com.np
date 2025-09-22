<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->latest()->paginate(12);
        return view('backend.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('backend.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'image'      => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
            'alt_text'   => ['nullable', 'string', 'max:255'],
            'link_url'   => ['nullable', 'url', 'max:2048'],
            'is_active'  => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $ext = $request->file('image')->getClientOriginalExtension();
        $imageName = $request->name . '.' . $ext;
        $dir = public_path(path: "banner/");

        // Delete old file if exists (prevent duplicates)

        // Store new file
        $request->file('image')->move($dir, $imageName);
        $image = 'banner/' . $imageName;
        Banner::create([
            'name'       => $data['name'],
            'image_path' => $image,
            'alt_text'   => $data['alt_text'] ?? null,
            'link_url'   => $data['link_url'] ?? null,
            'is_active'  => (bool) ($data['is_active'] ?? true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('banner.index')->with('success', 'Banner created successfully.');
    }


    public function destroy(Banner $banner)
    {
        try {
            if ($banner->image_path && file_exists(public_path($banner->image_path))) {
                unlink(public_path($banner->image_path));
            }

            $banner->delete();

            return redirect()->route('banner.index')
                ->with('success', 'Banner and image deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('banner.index')
                ->with('error', 'Failed to delete banner: ' . $e->getMessage());
        }
    }
}

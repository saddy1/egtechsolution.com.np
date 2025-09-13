<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.banner.index',['banner'=>Banner::latest()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                // Ensure the name is unique in the "products" table
                function ($attribute, $value, $fail) {
                    if (strpos($value, '/') !== false) {
                        $fail('The :attribute should not contain the "/" character.');
                    }
                    if (\DB::table('banners')->where('name', $value)->exists()) {
                        $fail('The :attribute is already taken.');
                    }
                }
            ],
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:3048' // Adjust mimes as needed
        ]);
        // echo"<pre>";
        // print_r($request->toArray());
        // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);// Adjust max file size as needed
        $banner = new Banner;
        $banner->name = $request->name;
        $banner->save();
        $name = $request->input('name');
        $photo = $request->file('photo');
        $newFileName = $name . '.png';

        $photo->storeAs('banner', $newFileName, 'custom'); // Use the custom disk


        return redirect()
            ->route('banner.index')
            ->with('success', 'Banner Added');

    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Delete the associated photo
        $photoPath = 'banner/' . $banner->name . '.png';
        print_r($photoPath);
        Storage::disk('custom')->delete($photoPath);
        $banner->delete();
        return redirect()->route('banner.index')->with('success', 'Banner deleted successfully');
    }
}

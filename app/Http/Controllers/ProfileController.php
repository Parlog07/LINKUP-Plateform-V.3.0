<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\PostAction;

class ProfileController extends Controller
{
    /**
     * Show the edit profile page.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Basic validation rules
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'interests' => 'nullable|array',
            'interests.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle profile picture upload
        $profilePicturePath = $user->profile_picture;
        
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            // use 'disk("public")' because that's where to store them.
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Store new profile picture
            $file = $request->file('profile_picture');
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // save to storage/app/public/profile-pictures
            $path = $file->storeAs('profile-pictures', $fileName, 'public');
            $profilePicturePath = $path;
        }

        // Prepare interests data
        $interests = $request->input('interests', []);
        $interestsJson = !empty($interests) ? json_encode($interests) : null;

        // Prepare update data
        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'bio' => $request->bio,
        ];

        // add fields if they exist in the database
        $tableColumns = DB::getSchemaBuilder()->getColumnListing('users');
        
        if (in_array('location', $tableColumns)) {
            $updateData['location'] = $request->location;
        }
        
        if (in_array('industry', $tableColumns)) {
            $updateData['industry'] = $request->industry;
        }
        
        if (in_array('profile_picture', $tableColumns)) {
            $updateData['profile_picture'] = $profilePicturePath;
        }
        
        if (in_array('interests', $tableColumns)) {
            $updateData['interests'] = $interestsJson;
        }

        // Update user data
        $user->update($updateData);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    public function show(User $user)
{
    // Fetch the friend with their posts and the count of their interactions
    $user = $user->loadCount(['posts', 'likes', 'comments'])
        ->load([
            'posts' => function($query) {
            $query->latest();
        }]);

    // Fetch the recent interactions (Likes and Comments) this friend has made
    $interactions = PostAction::where('user_id', $user->id)
        ->with('post.user') // To show which post they interacted with
        ->latest()
        ->take(10)
        ->get();

    return view('profile.show', compact('user', 'interactions'));
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Subscription;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return response()->json(User::all());
    }

    // Get a single user
    public function show($id)
    {

        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user->load('wallet', 'transactions', 'subscriptions.package'); // Adjust relations as per your models

        return response()->json([
            'message' => 'Current user retrieved successfully',
            'user' => $user,
        ]);
    }

    // Create a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            "role" => "required|in:admin,user"
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            "role" => $request->role
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
{
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'role' => 'required|in:admin,user',
        'password' => 'nullable|min:8',
        'password_confirmation' => 'nullable|same:password'
    ]);

    $user->name = $validatedData['name'];
    $user->role = $validatedData['role'];

    if (!empty($validatedData['password'])) {
        $user->password = Hash::make($validatedData['password']);
    }

    $user->save();

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user->only(['id', 'name', 'email', 'role']) // Exclude sensitive fields like `password`
    ]);
}

    // Delete a user
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function currentUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Load the necessary relations
        $user->load('wallet', 'transactions', 'subscriptions.package'); // Adjust relations as per your models

        // Get the latest subscription based on expires_at
        $latestSubscription = $user->subscriptions()
            ->orderBy('expires_at', 'desc')
            ->with('package') // Include the package details if needed
            ->first();

        return response()->json([
            "accessToken" => $request->bearerToken(),
            'message' => 'Current user retrieved successfully',
            'user' => $user,
            'latest_subscription' => $latestSubscription,
        ]);
    }

    public function getAuthorsById($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $user = $user->makeHidden(['password', 'remember_token', 'created_at', 'updated_at']);

    $authors = $user->authors;

    return response()->json([
        'message' => 'Authors retrieved successfully',
        'user' => $user->only(['id', 'name', 'email', 'role']),
        'authors' => $authors,
    ]);
}

public function updateMyProfile(Request $request)
{
    // Get the currently authenticated user
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'nullable|string|max:255',
        'password' => 'nullable|min:8',
        'password_confirmation' => 'nullable|same:password',
        'intro' => 'nullable|string|max:255',
        'social' => 'nullable|json',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    // Update password if provided
    if (!empty($validatedData['password'])) {
        $user->password = Hash::make($validatedData['password']);
    }


    // Handle avatar file upload
    if ($request->hasFile('avatar')) {
        // Store the uploaded file in the 'images' directory under 'public' disk
        $imagePath = $request->file('avatar')->store('images', 'public');
        $user->avatar = $imagePath;
    }

    // Update other fields
    $user->name = $validatedData['name'];
    $user->intro = $validatedData['intro'] ?? $user->intro; // Keep existing value if not provided
    $user->social = $validatedData['social'] ?? $user->social;

    // Save the updated user
    $user->save();

    // Return the updated user data
    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user->only(['id', 'name', 'email', 'role', 'intro', 'social', 'avatar'])
    ]);
}
}

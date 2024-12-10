<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Create a new Author.
     */
    public function create_author(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image_url' => 'nullable|url',
            'designation' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'social_accounts' => 'nullable|array',
            'social_accounts.*.name' => 'required_with:social_accounts|string',
            'social_accounts.*.url' => 'required_with:social_accounts|url',
            'course_links' => 'nullable|array',
            'course_links.*.name' => 'required_with:course_links|string',
            'course_links.*.url' => 'required_with:course_links|url'
        ]);

        $author = Author::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Author created successfully',
            'author' => $author
        ], 201);
    }

    /**
     * Get all authors.
     */
    public function get_authors()
    {
        $authors = Author::all();

        return response()->json($authors);
    }


    /**
     * Get Top Four Author
     */

    public function getTopFourAuthorsSortedByReview()
    {
        // Fetch the top 4 authors sorted by review in descending order, no eager load for students or courses
        $authors = Author::orderBy('review', 'desc')  // Sort by the review field in descending order
        ->take(4)                    // Limit the result to the top 4 authors
        ->get(['author_uid', 'name', 'review','students', 'course_links','image_url', 'designation']);  // Only select the necessary fields

        // If no authors exist, return an empty array with a success message
        if ($authors->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No authors found.',
                'data' => []
            ], 200);
        }

        // Process each author to calculate total students and course count
        $authors->transform(function ($author) {
            if (!is_array($author->students)) {
                $author->students = [];
            }
        
            if (!is_array($author->courses)) {
                $author->courses = [];
            }
        
            $author->studentAnroll = count($author->students) ?: 0;
            $author->courseCount = count($author->courses) ?: 0;
        
            unset($author->students);
            unset($author->courses);
        
            return $author;
        });
        


        return response()->json([
            'status' => 'success',
            'data' => $authors,
        ], 200);
    }


    /**
     * Get Author By Id
     */

     public function get_single_author_by_id($author_uid)
    {
        // Retrieve the author by author_uid or return 404 if not found
        $author = Author::where('author_uid', $author_uid)->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $author
        ]);
    }


    

    public function update_author(Request $request, $author_uid)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'nullable|string|max:255',
            'image_url' => 'nullable|url|max:255',
            'designation' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'students' => 'nullable|integer',
            'review' => 'nullable|numeric|min:0|max:5',
            'social_accounts' => 'nullable|array',
            'course_links' => 'nullable|array',
            'extraInfo' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'website' => 'nullable|url|max:255',
            'follow_us' => 'nullable|array',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        // Find the author by author_uid, if not found throw 404
        $author = Author::where('author_uid', $author_uid)->firstOrFail();

        // Update only the allowed fields, excluding id and author_uid
        $author->update($request->only([
            'name', 
            'image_url', 
            'designation', 
            'description', 
            'students', 
            'review', 
            'social_accounts', 
            'course_links', 
            'extraInfo', 
            'address', 
            'phone', 
            'website', 
            'follow_us',
            'created_at',
            'updated_at'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Author updated successfully',
            'data' => $author
        ]);
    }

}

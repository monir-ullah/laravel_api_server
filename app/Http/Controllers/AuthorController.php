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
        ->get(['id', 'name', 'review','students', 'course_links','image_url', 'designation']);  // Only select the necessary fields

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

     public function get_single_author_by_id($id)
    {
        // Retrieve the author by ID or return 404 if not found
        $author = Author::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $author
        ]);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'authors';

    // Fields that can be mass-assigned
    protected $fillable = [
        'author_uid',
        'name',
        'image_url',
        'designation',
        'description',
        'social_accounts',
        'course_links',
        'students',
        'review',
    ];

    // Cast JSON fields as arrays
    protected $casts = [
        'social_accounts' => 'array',
        'course_links' => 'array',
        'students' => 'array', // Cast JSON to an array
        'review' => 'float',   // Ensure the review is stored as a float

    ];

    // Automatically generate a unique author_uid when creating a new author
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($author) {
            $author->author_uid = 'author_' . uniqid();
        });
    }


    /**
     * Mutator to ensure the review is within the range 0 to 5.
     *
     * @param float $value
     * @return void
     */
    public function setReviewAttribute($value)
    {
        // Ensure the review is within the range (0 to 5) and rounds to 2 decimal places
        $this->attributes['review'] = min(max(round($value, 2), 0), 5);
    }

    /**
     * Accessor to ensure the review is always returned as a float with 2 decimal places.
     *
     * @param float $value
     * @return string
     */
    public function getReviewAttribute($value)
    {
        return number_format((float) $value, 2, '.', '');
    }
}

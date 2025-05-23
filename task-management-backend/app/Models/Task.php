<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**  
 * @property int $id  
 * @property string $title  
 * @property string|null $description  
 * @property int|null $category
 * @property DateTime|null $created_at
 * @property DateTime $updated_at  
 * @property int $user_id
 */
class Task extends Model
{
    // Fields permitted for mass attribution.
    protected $fillable = ['title', 'description', 'category', 'user_id'];

    // Fields that won't be serialized.
    protected $hidden = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

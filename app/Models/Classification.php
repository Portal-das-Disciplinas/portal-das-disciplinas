<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function disciplines()
    {
        return $this->hasManyThrough(Discipline::class, ClassificationDiscipline::class,
            'classification_id', 'id',
            'id', 'discipline_id');
    }

    /**
     * @param $discipline_id
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classificationDiscipline($discipline_id)
    {
        return $this->hasMany(ClassificationDiscipline::class, "classification_id", "id")
            ->where('discipline_id', $discipline_id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classificationsDisciplines()
    {
        return $this->hasMany(ClassificationDiscipline::class);
    }
}

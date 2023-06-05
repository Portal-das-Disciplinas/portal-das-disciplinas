<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Discipline extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada com o modelo
     *
     * @var string
     */
    protected $table = 'disciplines';

    /**
     * Os atributos que são atribuiveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'trailer',
        'emphasis_id',
        'difficulties',
        'professor_id',
        'acquirements',
    ];

    /**
     * @return int|null
     */
    public function getClassificationsValues($classification_id): ? int
    {
        return $this->classificationsDisciplines
            ->where('classification_id', $classification_id)
            ->first()->value ?? 0;
    }
    /**
     * @return Media|null
     */
    public function getTrailerAttribute(): ?Media
    {
        return $this->medias
            ->where('is_trailer', true)
            ->first();
    }

    /**
     * @return bool
     */
    public function getHasTrailerMediaAttribute(): bool
    {
        return $this->medias
                ->where('is_trailer', true)
                ->count() > 0;
    }

    /**
     *Retorna todas as mídias (Trailer não está incluído).
     *
     * @return Collection
     */
    public function getMedias(): Collection
    {
        return $this->medias
            ->where('is_trailer', false);
    }

    /**
     * @param string $type
     * @return Collection
     */
    public function getMediasByType(string $type): Collection
    {
        return $this->medias
            ->where('is_trailer', false)
            ->where('type', $type);
    }

    public function getMediaByType(string $type)
    {
        return $this->medias
            ->where('is_trailer', false)
            ->where('type', $type)
            ->first();
    }

    /**
     * @param string $type
     * @return bool
     */
    public function hasMediaOfType(string $type): bool
    {
        return $this->medias
                ->where('is_trailer', false)
                ->where('type', $type)
                ->count() > 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('title');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classifications()
    {
        return $this->hasManyThrough(Classification::class, ClassificationDiscipline::class,
            'discipline_id', 'id',
            'id', 'classification_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
     public function classificationsDisciplines()
    {
        return $this->hasMany(ClassificationDiscipline::class,"discipline_id","id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tags()
    {
        return $this->hasManyThrough(Tag::class, TagDiscipline::class,
            'discipline_id', 'id',
            'id', 'tag_id');
    }

    public function emphase()
    {
        return $this->belongsTo(Emphasis::class);
    }

    public function disciplineParticipants(){
        return $this->hasMany(DisciplineParticipant::class);
    }
}

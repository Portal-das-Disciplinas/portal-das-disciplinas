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
     */
    protected $table = 'disciplines';

    /**
     * Array com os atributos que são atribuiveis em massa no método Discipline::create.\n
     * code: Código da disciplina.\n
     * name: Nome da disciplina.\n
     * description: Descrição da disciplina.\n
     * trailer: Link do trailer da disciplina.\n
     * emphasis_id: ID da ênfase desta disciplina.\n
     * difficulties: Texto aborda as dificuldades da disciplina.\n
     * professor_id: ID do professor que leciona a discipilina.\n
     * acquirements: Texto que aborda as competências desejadas para cursar a disciplina.\n
     * podcast_url: Caminho do arquivo de áudio do podcast
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
        'podcast_url',
        'institutional_unit_id',
        'education_level_id'
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
     * Retorna o trailer da disciplina.
     * @return Media|null
     */
    public function getTrailerAttribute(): ?Media
    {
        return $this->medias
            ->where('is_trailer', true)
            ->first();
    }

    /**
     * Verifica se a disciplina tem um trailer.
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
     * Obtém as mídias do tipo $type, que não são trailers.
     * @param string $type
     * @return Collection
     */
    public function getMediasByType(string $type): Collection
    {
        return $this->medias
            ->where('is_trailer', false)
            ->where('type', $type);
    }

    /**
     * Obtém uma mídia do tipo $type que não é trailler.
     * @param string $type
     * @return Media
     */
    public function getMediaByType(string $type)
    {
        return $this->medias
            ->where('is_trailer', false)
            ->where('type', $type)
            ->first();
    }

    /**
     * Verifica se há um mídia de um determinado tipo
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
     * Retorna o professor da disciplina.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    /**
     * Retorna todas as mídias que a disciplina possui.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Retorna todas as Faqs que a disciplina possui.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('title');
    }

    /**
     * Retorna todas as classificações.
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classifications()
    {
        return $this->hasManyThrough(Classification::class, ClassificationDiscipline::class,
            'discipline_id', 'id',
            'id', 'classification_id');
    }

    /**
     * Retorna todas as classificações desta disciplina.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
     public function classificationsDisciplines()
    {
        return $this->hasMany(ClassificationDiscipline::class,"discipline_id","id");
    }

    /**
     * Retorna todas as tags desta disciplina.(obs)
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tags()
    {
        return $this->hasManyThrough(Tag::class, TagDiscipline::class,
            'discipline_id', 'id',
            'id', 'tag_id');
    }

    /**
     * Retorna a ênfase da disciplina.
     */
    public function emphase()
    {
        return $this->belongsTo('App\Models\Emphasis','emphasis_id');
    }
    /**
     * Retorna todos os participantes que contribuiram para gerar conteúdo para a página desta disciplina.
     */
    public function disciplineParticipants(){
        return $this->hasMany(DisciplineParticipant::class)->orderBy('name');
    }

    public function topics() {
        return $this->belongsToMany('App\Models\Topic');
    }
    public function subjectTopics(){
        return $this->hasMany(SubjectTopic::class);
    }

    public function subjectConcepts(){
        return $this->hasMany(SubjectConcept::class);
    }

    public function subjectReferences(){
        return $this->hasMany(SubjectReference::class);
    }

    public function professor_methodologies(){
        return $this->belongsToMany(ProfessorMethodology::class,'discipline_professor_methodology','discipline_id','prof_methodology_id');
    }

    public function institutionalUnit(){
        return $this->belongsTo(InstitutionalUnit::class);
    }

    public function courses(){
        return $this->belongsToMany(Course::class);
    }

    public function educationLevel(){
        return $this->belongsTo(EducationLevel::class);
    }
}

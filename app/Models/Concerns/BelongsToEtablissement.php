<?php

namespace App\Models\Concerns;

use App\Models\Etablissement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToEtablissement
{
    public static function bootBelongsToEtablissement(): void
    {
        if (!app()->runningInConsole()) {
            static::addGlobalScope('etablissement', function (Builder $builder) {
                $resolvedId = Auth::user()?->etablissement_id;

                if ($resolvedId) {
                    $builder->where(
                        $builder->qualifyColumn('etablissement_id'),
                        $resolvedId
                    );
                }
            });
        }

        static::creating(function (Model $model) {
            if (empty($model->etablissement_id) && Auth::check()) {
                $model->etablissement_id = Auth::user()->etablissement_id;
            }
        });
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function scopeForEtablissement(Builder $builder, ?int $etablissementId = null): Builder
    {
        $resolvedId = $etablissementId ?? Auth::user()?->etablissement_id;

        if (!$resolvedId) {
            return $builder;
        }

        return $builder->where($builder->qualifyColumn('etablissement_id'), $resolvedId);
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\BelongsToEtablissement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    use BelongsToEtablissement;

    protected $fillable = [
        'etablissement_id',
    ];
}

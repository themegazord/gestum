<?php

namespace App\Models;

use App\Enums\TipoCategoria;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasUuids, SoftDeletes;
    protected $fillable = [
        'user_id',
        'categoria_pai_id',
        'nome',
        'tipo',
        'cor',
    ];

    protected function casts(): array
    {
        return [
            'tipo' => TipoCategoria::class,
        ];
    }

    public function categoriaPai(): BelongsTo {
        return $this->belongsTo(Categoria::class, 'categoria_pai_id');
    }

    public function categoriasFilhas(): HasMany {
        return $this->hasMany(Categoria::class, 'categoria_pai_id');
    }

    public function ehCategoriaPai(): bool {
        return $this->categoriasFilhas()->exists();
    }

    public function criador(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lancamentos(): HasMany {
        return $this->hasMany(Lancamento::class, 'categoria_id');
    }

    public function getCategoriaAgrupadasPorTipo(TipoCategoria $tipo): array {
        $categorias = \App\Models\Categoria::query()
            ->where('categorias.user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('categorias.tipo', $tipo)
            ->get();


        $paisComFilhas = $categorias
            ->whereNotNull('categoria_pai_id')
            ->pluck('categoria_pai_id')
            ->unique();

        $categorias->each(function ($categoria) use (&$grouped, $categorias, $paisComFilhas) {
            $paiId = $categoria->getAttribute('categoria_pai_id');

            if ($paiId) {
                // É uma filha - agrupa pelo nome do pai
                $pai = $categorias->firstWhere('id', $paiId);
                $paiNome = $pai ? $pai->getAttribute('nome') : 'Outros';

                $grouped[$paiNome][] = [
                    'id' => $categoria->getAttribute('id'),
                    'name' => $categoria->getAttribute('nome')
                ];
            } elseif (!$paisComFilhas->contains($categoria->getAttribute('id'))) {
                // É raiz e NÃO tem filhas - adiciona como grupo individual
                $grouped[$categoria->getAttribute('nome')][] = [
                    'id' => $categoria->getAttribute('id'),
                    'name' => $categoria->getAttribute('nome')
                ];
            }
        });

        return $grouped;
    }
}

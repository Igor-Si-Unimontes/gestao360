<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use App\Models\OcorrenciaLote;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OcorrenciaController extends Controller
{
    public function index()
    {
        $ocorrencias = Ocorrencia::with(['produto', 'usuario'])
            ->latest()
            ->get();

        return view('ocorrencias.index', compact('ocorrencias'));
    }

    public function create()
    {
        $produtos = Product::with('batches')
            ->get()
            ->filter(fn($produto) => (float) $produto->total_quantity > 0)
            ->values();

        return view('ocorrencias.create', compact('produtos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'produto_id' => 'required|exists:products,id',
            'quantidade' => 'required|numeric|min:0.01',
            'motivo'     => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($data) {
                $produto = Product::findOrFail($data['produto_id']);
                $quantidade = (float) $data['quantidade'];

                $estoqueTotal = (float) $produto->total_quantity;
                if ($estoqueTotal < $quantidade) {
                    throw new \RuntimeException("Estoque insuficiente para \"{$produto->name}\". Disponível: {$estoqueTotal}");
                }

                $ocorrencia = Ocorrencia::create([
                    'produto_id' => $produto->id,
                    'usuario_id' => auth()->id(),
                    'quantidade' => $quantidade,
                    'motivo'     => $data['motivo'],
                ]);

                $this->descontarDosLotesMaisRecentes($produto, $quantidade, $ocorrencia->id);
            });
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('ocorrencias.index')
            ->with('success', 'Ocorrência registrada com sucesso e estoque descontado.');
    }

    public function edit(Ocorrencia $ocorrencia)
    {
        $produtos = Product::with('batches')->get();

        return view('ocorrencias.edit', compact('ocorrencia', 'produtos'));
    }

    public function update(Request $request, Ocorrencia $ocorrencia)
    {
        $data = $request->validate([
            'produto_id' => 'required|exists:products,id',
            'quantidade' => 'required|numeric|min:0.01',
            'motivo'     => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($data, $ocorrencia) {
                $this->reverterLotes($ocorrencia);

                $produto = Product::findOrFail($data['produto_id']);
                $quantidade = (float) $data['quantidade'];

                $estoqueTotal = (float) $produto->total_quantity;
                if ($estoqueTotal < $quantidade) {
                    throw new \RuntimeException("Estoque insuficiente para \"{$produto->name}\". Disponível: {$estoqueTotal}");
                }

                $ocorrencia->update([
                    'produto_id' => $produto->id,
                    'quantidade' => $quantidade,
                    'motivo'     => $data['motivo'],
                ]);

                $this->descontarDosLotesMaisRecentes($produto, $quantidade, $ocorrencia->id);
            });
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('ocorrencias.index')
            ->with('success', 'Ocorrência atualizada com sucesso.');
    }

    public function destroy(Ocorrencia $ocorrencia)
    {
        try {
            DB::transaction(function () use ($ocorrencia) {
                $this->reverterLotes($ocorrencia);
                $ocorrencia->delete();
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('ocorrencias.index')
            ->with('success', 'Ocorrência removida com sucesso.');
    }

    private function descontarDosLotesMaisRecentes(Product $produto, float $quantidade, int $ocorrenciaId): void
    {
        $restante = $quantidade;

        foreach ($produto->batches()->where('active', true)->where('quantity', '>', 0)->latest()->get() as $lote) {
            if ($restante <= 0) {
                break;
            }

            $deduzir = min((float) $lote->quantity, $restante);
            if ($deduzir <= 0) {
                continue;
            }

            $lote->decrement('quantity', $deduzir);

            $lote->refresh();
            if ((float) $lote->quantity <= 0 && $lote->active) {
                $lote->update(['active' => false]);
            }

            $restante -= $deduzir;

            OcorrenciaLote::create([
                'ocorrencia_id' => $ocorrenciaId,
                'batch_id'      => $lote->id,
                'quantidade'    => $deduzir,
            ]);
        }

        if ($restante > 0) {
            throw new \RuntimeException('Não foi possível descontar toda a quantidade dos lotes.');
        }
    }

    private function reverterLotes(Ocorrencia $ocorrencia): void
    {
        $ocorrencia->load('lotes.lote');

        foreach ($ocorrencia->lotes as $itemLote) {
            $lote = $itemLote->lote;

            if (!$lote) {
                continue;
            }

            $lote->increment('quantity', (float) $itemLote->quantidade);

            if (!$lote->active) {
                $lote->update(['active' => true]);
            }
        }

        $ocorrencia->lotes()->delete();
    }
}

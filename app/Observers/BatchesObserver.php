<?php

namespace App\Observers;

use App\Models\Batch;
use App\Models\LogBatches;
use Carbon\Carbon;

class BatchesObserver
{
    /**
     * Handle the Batch "created" event.
     */
    public function created(Batch $batch): void
    {
        $data = 'Lote de ' . $batch->product->name .
            ' criado por ' . (auth()->user()->first_name ?? 'sistema') .
            ' em ' . Carbon::parse($batch->created_at)->format('d/m/Y H:i') .
            ' com a quantidade de ' . $batch->quantity .
            ' e valor de R$ ' . number_format($batch->sale_price, 2, ',', '.') .
            ' com vencimento em ' . Carbon::parse($batch->expiration_date)->format('d/m/Y') . '.';
        LogBatches::create(['action' => $data]);
    }

    /**
     * Handle the Batch "updated" event.
     */
    public function updated(Batch $batch): void
    {
        $data = 'Lote de ' . $batch->product->name .
            ' atualizado por ' . (auth()->user()->first_name ?? 'sistema') .
            ' em ' . Carbon::parse($batch->updated_at)->format('d/m/Y H:i') .
            ' para a quantidade de ' . $batch->quantity .
            ' e valor de R$ ' . number_format($batch->sale_price, 2, ',', '.') .
            ' com vencimento em ' . Carbon::parse($batch->expiration_date)->format('d/m/Y') . '.';
        LogBatches::create(['action' => $data]);
    }

    /**
     * Handle the Batch "deleted" event.
     */
    public function deleted(Batch $batch): void
    {
        $data = $data = 'Lote de ' . $batch->product->name .
            ' apagado por ' . (auth()->user()->first_name ?? 'sistema') .
            ' para a quantidade de ' . $batch->quantity .
            ' e valor de R$ ' . number_format($batch->sale_price, 2, ',', '.') .
            ' com vencimento em ' . Carbon::parse($batch->expiration_date)->format('d/m/Y') . '.';
        LogBatches::create(['action' => $data]);
    }
}

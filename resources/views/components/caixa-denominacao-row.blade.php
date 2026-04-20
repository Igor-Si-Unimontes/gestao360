@props(['key', 'label', 'valor', 'quantidade' => 0])

<div class="denom-row d-flex align-items-center gap-3 py-2 border-bottom">
    <div class="denom-label fw-semibold" style="min-width:70px;">{{ $label }}</div>

    <div class="text-muted small" style="min-width:60px;">R$ {{ $valor }}/un</div>

    <div class="d-flex align-items-center gap-2 flex-grow-1">
        <button type="button" class="btn btn-outline-secondary btn-sm btn-menos px-2" data-key="{{ $key }}">
            <i class="fas fa-minus"></i>
        </button>
        <input type="number"
               id="inp_{{ $key }}"
               name="{{ $key }}"
               class="form-control form-control-sm inp-qtd text-center"
               value="{{ $quantidade }}"
               min="0"
               style="width:80px;">
        <button type="button" class="btn btn-outline-secondary btn-sm btn-mais px-2" data-key="{{ $key }}">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <div class="text-end fw-semibold" id="sub_{{ $key }}" style="min-width:100px; color:#7212E7;">
        R$ 0,00
    </div>
</div>

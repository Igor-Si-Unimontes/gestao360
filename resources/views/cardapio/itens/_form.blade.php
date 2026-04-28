@php
    $i = $item;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Categoria</label>
        <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
            @foreach (\App\Models\CardapioItem::$categorias as $valor => $rotulo)
                <option value="{{ $valor }}" @selected(old('categoria', $i?->categoria ?? \App\Models\CardapioItem::CAT_COMIDA) === $valor)>
                    {{ $rotulo }}
                </option>
            @endforeach
        </select>
        @error('categoria')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Valor (R$)</label>
        <input type="number" name="valor" step="0.01" min="0" class="form-control @error('valor') is-invalid @enderror"
               value="{{ old('valor', $i?->valor) }}" required>
        @error('valor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Nome do prato / produto</label>
        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
               value="{{ old('nome', $i?->nome) }}" required maxlength="160">
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Descrição <span class="text-muted small">(opcional)</span></label>
        <textarea name="descricao" rows="3" class="form-control @error('descricao') is-invalid @enderror"
                  maxlength="2000" placeholder="Ingredientes ou texto de apresentação">{{ old('descricao', $i?->descricao) }}</textarea>
        @error('descricao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Serve quantas pessoas?</label>
        <input type="number" name="serve_pessoas" min="1" max="500" class="form-control @error('serve_pessoas') is-invalid @enderror"
               value="{{ old('serve_pessoas', $i?->serve_pessoas ?? 1) }}" required>
        @error('serve_pessoas')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">URL da imagem <span class="text-muted small">(opcional)</span></label>
        <input type="url" name="imagem_url" class="form-control @error('imagem_url') is-invalid @enderror"
               value="{{ old('imagem_url', $i?->imagem_url) }}" maxlength="2048"
               placeholder="https://... (vazio = imagem ilustrativa automática)">
        @error('imagem_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <input type="hidden" name="visivel" value="0">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="visivel" value="1" id="visivel"
                   @checked(old('visivel', $i ? ($i->visivel ? '1' : '0') : '1') === '1')>
            <label class="form-check-label" for="visivel">Visível na vitrine pública</label>
        </div>
        <p class="text-muted small mb-0">Desmarcado: o item continua salvo, mas não aparece em <code>/menu</code>.</p>
    </div>
</div>

@if ($i)
    <div class="mt-3 p-3 rounded border bg-light d-flex align-items-center gap-3">
        <span class="text-muted small">Prévia da imagem:</span>
        <img src="{{ $i->urlImagemExibicao() }}" alt="" class="rounded" style="height:64px;width:96px;object-fit:cover;">
    </div>
@endif

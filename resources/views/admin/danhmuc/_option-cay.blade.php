@foreach ($danhsach as $item)
    @if (!isset($boquaId) || $item->id !== $boquaId)
        <option value="{{ $item->id }}" {{ (string) ($selected ?? '') === (string) $item->id ? 'selected' : '' }}>
            {{ str_repeat('— ', $cap ?? 0) }}{{ $item->ten_danh_muc }}
        </option>

        @if ($item->con && $item->con->count())
            @include('admin.danhmuc._option-cay', [
                'danhsach' => $item->con,
                'selected' => $selected ?? null,
                'cap' => ($cap ?? 0) + 1,
                'boquaId' => $boquaId ?? null,
            ])
        @endif
    @endif
@endforeach

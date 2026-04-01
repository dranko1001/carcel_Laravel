<select name="prisoner_id">
    @foreach($prisoners as $p)
        <option value="{{ $p->id }}">{{ $p->full_name }} - Celda: {{ $p->assigned_cell }}</option>
    @endforeach
</select>,
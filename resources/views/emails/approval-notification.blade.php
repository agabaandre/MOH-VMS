<div>
    <h2>{{ $modelType }} Approved</h2>
    <p>A {{ $modelType }} has been approved with the following details:</p>
    
    <table>
        @foreach($data->toArray() as $key => $value)
            @if(!in_array($key, ['created_at', 'updated_at', 'deleted_at']))
                <tr>
                    <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                    <td>{{ $value }}</td>
                </tr>
            @endif
        @endforeach
    </table>
</div>

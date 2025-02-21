@if($data->attachment_1)
    <a href="{{ url($data->attachment_1) }}" target="_blank" class="btn btn-sm btn-info" type="button">
        <span class="badge bg-light text-dark"><i class="fas fa-eye fa-sm"></i></span> Show
    </a>
@else 
    -
@endif
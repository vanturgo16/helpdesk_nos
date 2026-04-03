@if($data->attachment_1)
    <a href="{{ Storage::disk('s3')->temporaryUrl($data->attachment_1, now()->addMinutes(60)) }}" target="_blank" class="btn btn-sm btn-info" type="button">
        <span class="badge bg-light text-dark"><i class="fas fa-eye fa-sm"></i></span> {{ __('messages.show') }}
    </a>
@else 
    -
@endif
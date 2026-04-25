@if($data->attachment_1)
    @php
        $path = $data->attachment_1;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $previewable = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];
    @endphp

    @if(in_array($ext, $previewable))
        <a href="{{ Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(60), ['ResponseContentDisposition' => 'inline']) }}" 
            target="_blank" class="btn btn-sm btn-info" type="button">
            <span class="badge bg-light text-dark"><i class="fas fa-eye fa-sm"></i></span> {{ __('messages.show') }}
        </a>
    @else
        <a href="{{ Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(60), ['ResponseContentDisposition' => 'attachment; filename="'.basename($path).'"']) }}" 
            class="btn btn-sm btn-success" type="button" title="Download file (this type cannot be previewed in browser)">
            <span class="badge bg-light text-dark"><i class="fas fa-download fa-sm"></i></span> {{ __('messages.download') }}
        </a>
    @endif
@else 
    -
@endif
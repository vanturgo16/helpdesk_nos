<button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#edit{{ $data->id }}">
    <i class="mdi mdi-file-edit label-icon"></i> Edit
</button>
@if($data->is_active == 1)
    <button type="button" class="btn btn-sm btn-danger waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#disable{{ $data->id }}">
        <i class="mdi mdi-window-close label-icon"></i> Disable
    </button>
@else
    <button type="button" class="btn btn-sm btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#enable{{ $data->id }}">
        <i class="mdi mdi-check label-icon"></i> Enable
    </button>
@endif

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Update --}}
    <div class="modal fade" id="edit{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('dropdown.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Category</label> <label class="text-danger">*</label>
                                <select class="form-control select2 category-select" name="category" data-id="{{ $data->id }}" required>
                                    <option value="" disabled selected>- Select -</option>
                                    @foreach($categories as $item)
                                        <option value="{{ $item->category }}" @if($data->category == $item->category) selected="selected" @endif>{{ $item->category }}</option>
                                    @endforeach
                                    <option disabled>──────────</option>
                                    <option value="NewCat">Add New Category</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3 new-category-container" data-id="{{ $data->id }}" style="display: none;">
                                <label class="form-label">New Category</label> <label class="text-danger">*</label>
                                <input type="text" name="addcategory" class="form-control new-category-input" placeholder="Input New Category..">
                            </div>
                            <script>
                                $(document).ready(function () {
                                    // Handle category selection change
                                    $('.category-select').on("change", function () {
                                        const id = $(this).data("id"); // Get the unique ID
                                        const isNewCategory = $(this).val() === "NewCat";
                                        $(`.new-category-container[data-id="${id}"]`).toggle(isNewCategory);
                                        $(`.new-category-container[data-id="${id}"] .new-category-input`).prop("required", isNewCategory);
                                    });
                                    // Check initial state on page load
                                    $('.category-select').each(function () {
                                        const id = $(this).data("id");
                                        const isNewCategory = $(this).val() === "NewCat";
                                        $(`.new-category-container[data-id="${id}"]`).toggle(isNewCategory);
                                        $(`.new-category-container[data-id="${id}"] .new-category-input`).prop("required", isNewCategory);
                                    });
                                });
                            </script>  
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Name Value</label> <label class="text-danger">*</label>
                                <input class="form-control" type="text" name="name_value" value="{{ $data->name_value }}" placeholder="Input Name Value.." required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Code Format</label>
                                <input class="form-control" type="text" name="code_format" value="{{ $data->code_format }}" placeholder="Input Code Format.. (Optional)">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-update label-icon"></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Disable --}}
    <div class="modal fade" id="disable{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Disable Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('dropdown.disable', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Disable</b> This Dropdown?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-window-close label-icon"></i>Disable
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Enable --}}
    <div class="modal fade" id="enable{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Enable Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('dropdown.enable', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Enable</b> This Dropdown?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                            <i class="mdi mdi-check label-icon"></i>Enable
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>

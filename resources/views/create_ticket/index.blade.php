@extends('layouts.master')
@section('konten')


<style>
    .ticket-container {
        background: linear-gradient(135deg, #f8f9fa 25%, #e9ecef 100%);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        max-width: 800px;
        border: 2px dashed #6c757d;
        position: relative;
    }
    .ticket-container::before, .ticket-container::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
    }
    .ticket-container::before {
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
    }
    .ticket-container::after {
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
    }
</style>

<style>
    .page-content {
        position: relative;
        min-height: 100vh;
        padding-bottom: 80px;
    }
    .floating-card {
        position: absolute;
        bottom: 25px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        max-width: 80vw;
        background: white;
        z-index: 11;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="page-content position-relative">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-light text-primary">
                    <h4 class="card-title mb-0">Create New Ticket</h4>
                </div>
                <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                    <div id="progrss-wizard" class="twitter-bs-wizard">
                        <ul class="twitter-bs-wizard-nav nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a href="#categoryTicket" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Category Ticket">
                                        <i class="bx bx-category"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#detailTicket" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Ticket">
                                        <i class="bx bx-task"></i>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="#summaryTicket" class="nav-link" data-toggle="tab">
                                    <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Summary Ticket">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <!-- wizard-nav -->
                        <div class="tab-content twitter-bs-wizard-tab-content" style="max-height: 45vh; overflow-y: auto; overflow-x: hidden;">
                            <div class="tab-pane" id="categoryTicket">
                                <div class="text-center mb-4">
                                    <h5>Category Ticket</h5>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label> <label class="text-danger">*</label>
                                            <select class="form-control select2" name="id_mst_status" required>
                                                <option value="" disabled selected>- Select -</option>
                                                @foreach($statuses as $item)
                                                    <option value="{{ $item->id }}">{{ $item->status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label> <label class="text-danger">*</label>
                                            <select class="form-control select2" name="id_mst_category" required>
                                                <option value="" disabled selected>- Select -</option>
                                                @foreach($categories as $item)
                                                    <option value="{{ $item->id }}">{{ $item->category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sub Category</label> <label class="text-danger">*</label>
                                            <select class="form-control select2" name="id_mst_sub_category" required>
                                                <option value="" disabled selected>- Select -</option>
                                                <option disabled>──────────</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="detailTicket">
                                <div class="text-center mb-4">
                                    <h5>Detail Ticket</h5>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Notes (max 255 character)</label> <label class="text-danger">*</label>
                                            <textarea name="remarks" rows="3" cols="50" class="form-control" placeholder="Input Note.."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Upload Document</label>
                                            <input type="file" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Assign To</label> <label class="text-danger">*</label>
                                            <select class="form-control select2" name="id_department" required>
                                                <option value="" disabled selected>- Select -</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="summaryTicket">
                                <div class="text-center mb-4">
                                    <h5>Summary Ticket</h5>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="ticket-container">
                                            <h3 class="text-center">No. Ticket : <b><u>TCK123IT</u></b></h3>
                                            <div class="row mt-4">
                                                <div class="col-lg-6 mb-4">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="align-top"><strong>Requester</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top">admindev@gmail.com</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>Level</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top">High</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>Category</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top">Incident</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>Sub Category</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top">Jaringan lambat atau terputus</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p><strong>Notes :</strong><br>
                                                    <span id="notes" class="notes">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.
                                                    </span></p>
                                                    <table class="mb-3">
                                                        <tbody>
                                                            <tr>
                                                                <td class="align-top"><strong>Upload Document</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top">Yes</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>Assign To</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top">IT</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Card at the Bottom of page-content -->
    <div class="floating-card card shadow">
        <div class="card-body text-center p-0">
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-secondary" id="prevBtn">
                    <i class="fas fa-arrow-left"></i>&nbsp;&nbsp; Previous
                </button>
                <button class="btn btn-primary" id="nextBtn">
                    Next &nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Add --}}
<div class="modal fade" id="sendTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Send Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="formLoad" action="{{ route('createTicket.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_mst_status_final" value="">
                <input type="hidden" name="id_mst_category_final" value="">
                <input type="hidden" name="id_mst_sub_category_final" value="">
                <input type="hidden" name="remarks_final" value="">
                <input type="hidden" name="id_department_final" value="">
                <div class="modal-body">
                    <div class="text-center">
                        Are You Sure to <b>Send</b> This Ticket?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="bx bx-paper-plane label-icon"></i>Send Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('select[name="id_mst_category"]').on('change', function () {
            var categoryId = $(this).val();
            var subCategorySelect = $('select[name="id_mst_sub_category"]');
            
            if (categoryId) {
                $.ajax({
                    url: '/subcategory/get-subcategory/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        subCategorySelect.empty();
                        subCategorySelect.append('<option value="" disabled selected>- Select -</option>');
                        
                        if (response.success) {
                            $.each(response.data[0], function (key, item) {
                                subCategorySelect.append('<option value="' + item.id + '">' + item.sub_category + '</option>');
                            });
                        }
                        
                        subCategorySelect.append('<option disabled>──────────</option>');
                        subCategorySelect.append('<option value="Other">Other</option>');
                    },
                    error: function () {
                        alert('Error fetching subcategories. Please try again.');
                    }
                });
            } else {
                subCategorySelect.empty().append('<option value="" disabled selected>- Select -</option>');
                subCategorySelect.append('<option disabled>──────────</option>');
                subCategorySelect.append('<option value="Other">Other</option>');
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let notes = document.getElementById("notes");
        if (notes.innerText.length > 255) {
            notes.innerText = notes.innerText.substring(0, 255) + "...";
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let tabs = document.querySelectorAll('.twitter-bs-wizard-nav .nav-link');
        let tabContents = document.querySelectorAll('.tab-pane');
        let prevBtn = document.getElementById('prevBtn');
        let nextBtn = document.getElementById('nextBtn');
        let currentTab = 0;
    
        function showTab(index) {
            tabs.forEach((tab, i) => {
                tab.classList.toggle('active', i === index);
                tabContents[i].classList.toggle('active', i === index);
            });
    
            prevBtn.disabled = index === 0;
    
            if (index === tabs.length - 1) {
                nextBtn.innerHTML = 'Submit &nbsp;<i class="bx bx-paper-plane"></i>';
                nextBtn.classList.remove('btn-primary');
                nextBtn.classList.add('btn-success');
                nextBtn.setAttribute("data-bs-toggle", "modal");
                nextBtn.setAttribute("data-bs-target", "#sendTicket");
            } else {
                nextBtn.innerHTML = 'Next &nbsp;&nbsp;<i class="fas fa-arrow-right"></i>';
                nextBtn.classList.remove('btn-success');
                nextBtn.classList.add('btn-primary');
                nextBtn.removeAttribute("data-bs-toggle");
                nextBtn.removeAttribute("data-bs-target");
            }
        }
    
        prevBtn.addEventListener("click", function () {
            if (currentTab > 0) {
                currentTab--;
                showTab(currentTab);
            }
        });
    
        nextBtn.addEventListener("click", function () {
            if (currentTab < tabs.length - 1) {
                currentTab++;
                showTab(currentTab);
            }
        });
    
        showTab(currentTab);
    });
</script>

@endsection

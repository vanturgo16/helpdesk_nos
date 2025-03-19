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

<!-- Loading Overlay -->
<div id="loadingOverlay" style="
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    text-align: center;
    color: white;
    font-size: 20px;
    padding-top: 20%;
">
    <i class="fas fa-spinner fa-spin fa-3x"></i>
    <p>Loading...</p>
</div>


<div class="page-content position-relative">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-light text-primary">
                    <h4 class="card-title mb-0">{{ __('messages.ticket_new_create') }}</h4>
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
                                    <h5>{{ __('messages.ticket_cat') }}</h5>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.priority') }}</label> <label class="text-danger">*</label>
                                            <select class="form-control select2" name="priority_input" required>
                                                <option value="" disabled selected>- {{ __('messages.select') }} {{ __('messages.priority') }} -</option>
                                                @foreach($priorities as $item)
                                                    <option value="{{ $item->priority }}">{{ $item->priority }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Please select a priority.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.category') }}</label> <label class="text-danger">*</label>
                                            <select class="form-control select2" name="id_mst_category_input" required>
                                                <option value="" disabled selected>- {{ __('messages.select') }} {{ __('messages.category') }} -</option>
                                                @foreach($categories as $item)
                                                    <option value="{{ $item->id }}">{{ $item->category }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Please select a Category.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.sub_category') }}</label> <label class="text-danger">*</label>
                                            <select class="form-control select2" name="id_mst_sub_category_input" required>
                                                <option value="" disabled selected>- {{ __('messages.select') }} {{ __('messages.sub_category') }} -</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a Sub Category.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.sla_form') }} </label> <small class="text-muted"> - ({{ __('messages.auto_fill') }})</small>
                                            <div class="input-group">
                                                <input type="number" class="form-control" placeholder=".." name="sla" value="" readonly>
                                                <span class="input-group-text">{{ __('messages.minutes') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="detailTicket">
                                <div class="text-center mb-4">
                                    <h5>Detail {{ __('messages.ticket') }}</h5>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.notes') }} (max 255 character)</label> <label class="text-danger">*</label>
                                            <textarea name="notes_input" rows="3" cols="50" class="form-control" placeholder="Input {{ __('messages.notes') }}.." required></textarea>
                                            <div class="invalid-feedback">Please fill notes.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.upload_doc') }}</label>
                                            <input type="file" name="file_1" id="file_1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.report_date') }}</label> <label class="text-danger">*</label>
                                            <div class="d-flex">
                                                <div class="form-check me-5">
                                                    <input class="form-check-input" type="radio" id="use_now" name="report_date_option" value="now" checked>
                                                    <label class="form-check-label" for="use_now">{{ __('messages.use_current') }} </label> <small class="text-muted"> - ({{ __('messages.now') }})</small>
                                                </div>
                                    
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="use_custom" name="report_date_option" value="custom">
                                                    <label class="form-check-label" for="use_custom">{{ __('messages.custom') }} </label> <small class="text-muted"> - ({{ __('messages.back_date') }})</small>
                                                </div>
                                            </div>
                                            <input type="datetime-local" name="report_date_input" class="form-control mt-2" required id="report_date_input">
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    const input = document.getElementById("report_date_input");
                                                    const nowRadio = document.getElementById("use_now");
                                                    const customRadio = document.getElementById("use_custom");

                                                    function setMaxDateTime() {
                                                        const now = new Date();
                                                        const year = now.getFullYear();
                                                        const month = String(now.getMonth() + 1).padStart(2, '0');
                                                        const day = String(now.getDate()).padStart(2, '0');
                                                        const hours = String(now.getHours()).padStart(2, '0');
                                                        const minutes = String(now.getMinutes()).padStart(2, '0');
                                                        const maxDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                                                        if (nowRadio.checked) {
                                                            input.value = maxDateTime;
                                                        }
                                                        input.max = maxDateTime;
                                                    }
                                                    function toggleInput() {
                                                        if (nowRadio.checked) {
                                                            input.readOnly = true;
                                                            setMaxDateTime();
                                                        } else {
                                                            input.readOnly = false;
                                                        }
                                                    }
                                                    function validateInput() {
                                                        if (input.value > input.max) {
                                                            input.value = input.max; // Reset to max allowed if user selects future time
                                                        }
                                                    }
                                                    
                                                    toggleInput();
                                                    setMaxDateTime(); // Set the max date/time on page load
                                                    nowRadio.addEventListener("change", toggleInput);
                                                    customRadio.addEventListener("change", toggleInput);
                                                    input.addEventListener("input", validateInput); // Prevent future selection
                                                    setInterval(setMaxDateTime, 60000); // Update every minute
                                                });
                                            </script>

                                            <div class="invalid-feedback">Please fill report date.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.target_solved') }}</label>
                                            <div class="d-flex">
                                                <div class="form-check me-5">
                                                    <input class="form-check-input" type="radio" id="use_sla" name="target_date_option" value="sla_target" checked>
                                                    <label class="form-check-label" for="use_sla">{{ __('messages.use_sla') }} </label> <small class="text-muted"> - ({{ __('messages.calc_sla') }})</small>
                                                </div>
                                    
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="use_custom_target" name="target_date_option" value="custom_target">
                                                    <label class="form-check-label" for="use_custom_target">{{ __('messages.custom') }} </label> <small class="text-muted"> - ({{ __('messages.set_manual') }})</small>
                                                </div>
                                            </div>
                                            <div class="input-group mt-2" id="sla_input_group">
                                                <input type="number" class="form-control" placeholder=".." name="sla" id="sla_input" readonly>
                                                <span class="input-group-text">Minutes</span>
                                            </div>
                                            <input type="datetime-local" name="target_solved_date_input" class="form-control mt-2" id="target_solved_date_input" style="display: none;">
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    const slaRadio = document.getElementById("use_sla");
                                                    const customRadio = document.getElementById("use_custom_target");
                                                    const slaInputGroup = document.getElementById("sla_input_group");
                                                    const input = document.getElementById("target_solved_date_input");

                                                    function toggleInputFields() {
                                                        if (slaRadio.checked) {
                                                            slaInputGroup.style.display = "flex"; 
                                                            input.style.display = "none";
                                                        } else {
                                                            slaInputGroup.style.display = "none";
                                                            input.style.display = "block";
                                                        }
                                                    }
                                                    function setMinDateTime() {
                                                        const now = new Date();
                                                        const year = now.getFullYear();
                                                        const month = String(now.getMonth() + 1).padStart(2, '0');
                                                        const day = String(now.getDate()).padStart(2, '0');
                                                        const hours = String(now.getHours()).padStart(2, '0');
                                                        const minutes = String(now.getMinutes()).padStart(2, '0');
                                                        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                                                        input.min = minDateTime;
                                                    }
                                                    function validateInput() {
                                                        if (input.value < input.min) {
                                                            input.value = input.min; // Reset to min allowed if user selects a past time
                                                        }
                                                    }
                                                    toggleInputFields();
                                                    setMinDateTime(); // Set the min date/time on page load
                                                    slaRadio.addEventListener("change", toggleInputFields);
                                                    customRadio.addEventListener("change", toggleInputFields);
                                                    input.addEventListener("input", validateInput); // Prevent past selection
                                                    setInterval(setMinDateTime, 60000); // Update min every minute
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="summaryTicket">
                                <div class="text-center mb-4">
                                    <h5>{{ __('messages.summary_ticket') }}</h5>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="ticket-container">
                                            <h3 class="text-center">No. {{ __('messages.ticket') }} : <b><u>{{ $noTicket }}</u></b></h3>
                                            <div class="row mt-4">
                                                <div class="col-lg-6 mb-4">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="align-top"><strong>{{ __('messages.requestor') }}</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top">{{ auth()->user()->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>{{ __('messages.priority') }}</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top" id="summaryPriority"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>{{ __('messages.category') }}</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top" id="summaryCategory"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>{{ __('messages.sub_category') }}</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top" id="summarySubCategory"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-lg-6 mb-4">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="align-top"><strong>{{ __('messages.input_file') }}</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top" id="summaryInputFile"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>{{ __('messages.report_date') }}</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top" id="summaryReportDate"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="align-top"><strong>{{ __('messages.target_solved') }}</strong></td>
                                                                <td class="align-top" style="padding-left: 15px;">:</td>
                                                                <td class="align-top" id="summaryTargetSolved"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-lg-12">
                                                    <p><strong>{{ __('messages.notes') }} :</strong><br>
                                                        <span id="summaryNotes"></span>
                                                    </p>
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
                    <i class="fas fa-arrow-left"></i>&nbsp;&nbsp; {{ __('messages.prev') }}
                </button>
                <button class="btn btn-primary" id="nextBtn">
                    {{ __('messages.next') }} &nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Send --}}
<div class="modal fade" id="sendTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.send') }} {{ __('messages.ticket') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="formLoad" action="{{ route('createTicket.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="priority" value="">
                <input type="hidden" name="category" value="">
                <input type="hidden" name="sub_category" value="">
                <input type="hidden" name="report_date_option_val" value="">
                <input type="hidden" name="report_date" value="">
                <input type="hidden" name="target_date_option_val" value="">
                <input type="hidden" name="target_solved_date" value="">
                <input type="hidden" name="notes" value="">
                <div class="modal-body">
                    <div class="text-center">
                        {{ __('messages.are_u_sure') }} <b>{{ __('messages.send') }}</b> {{ __('messages.this_ticket') }}?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="bx bx-paper-plane label-icon"></i>{{ __('messages.send') }} {{ __('messages.ticket') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Script Select Option Category --}}
<script>
    $(document).ready(function () {
        var subCategorySelect = $('select[name="id_mst_sub_category_input"]');
        var slaInput = $('input[name="sla"]');
        $('select[name="id_mst_category_input"]').on('change', function () {
            var categoryId = $(this).val();
            subCategorySelect.empty();
            subCategorySelect.append('<option value="" disabled selected>- Select Sub Category -</option>');
            slaInput.val('');

            $("#loadingOverlay").fadeIn();

            if (categoryId) {
                $.ajax({
                    url: '/subcategory/get-subcategory/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        subCategorySelect.empty();
                        subCategorySelect.append('<option value="" disabled selected>- Select Sub Category -</option>');
                        
                        if (response.success) {
                            $.each(response.data[0], function (key, item) {
                                subCategorySelect.append('<option value="' + item.id + '">' + item.sub_category + '</option>');
                            });
                        }
                    },
                    error: function () {
                        alert('Error fetching subcategories. Please try again.');
                    },
                    complete: function() {
                        $("#loadingOverlay").fadeOut();
                    }
                });
            } else {
                subCategorySelect.empty().append('<option value="" disabled selected>- Select Sub Category -</option>');
            }
        });
        $('select[name="id_mst_sub_category_input"]').on('change', function () {
            var subCategoryId = $(this).val();
            slaInput.val('');
            $("#loadingOverlay").fadeIn();
            if (subCategoryId) {
                $.ajax({
                    url: '/subcategory/get-sla/' + subCategoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            slaInput.val(response.data[0].sla);
                        }
                    },
                    error: function () {
                        alert('Error fetching SLA. Please try again.');
                    },
                    complete: function() {
                        $("#loadingOverlay").fadeOut();
                    }
                });
            } else {
                slaInput.val('');
            }
        });
    });
</script>
{{-- Script Store File 1 In Form --}}
<script>
    document.getElementById("formadd").addEventListener("submit", function () {
        let form = this;
        let fileInput = document.getElementById("file_1");
        if (fileInput.files.length > 0) {
            // Clone the file input and keep it hidden
            let newFileInput = fileInput.cloneNode(true);
            newFileInput.style.display = "none";
            form.appendChild(newFileInput);
        }
    });
</script>
<script>
    var messages = {
        info_fct: "{{ __('messages.info_fct') }}"
    };
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
                updateSummary();
            } else {
                nextBtn.innerHTML = 'Next &nbsp;&nbsp;<i class="fas fa-arrow-right"></i>';
                nextBtn.classList.remove('btn-success');
                nextBtn.classList.add('btn-primary');
                nextBtn.removeAttribute("data-bs-toggle");
                nextBtn.removeAttribute("data-bs-target");
            }
        }

        function validateCurrentTab() {
            let currentTabContent = tabContents[currentTab];
            let requiredFields = currentTabContent.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });

            if (firstInvalidField) {
                firstInvalidField.focus();
            }

            return isValid;
        }

        function updateSummary() {
            // Get input values
            let priority = document.querySelector('select[name="priority_input"]').value;
            let category = document.querySelector('select[name="id_mst_category_input"]').selectedOptions[0]?.text || "-";
            let subCategory = document.querySelector('select[name="id_mst_sub_category_input"]').selectedOptions[0]?.text || "-";
            let notes = document.querySelector('textarea[name="notes_input"]').value || "-";
            let fileInput = document.querySelector('input[name="file_1"]');
            let fileName = fileInput.files.length > 0 ? 'Yes' : 'No';
            let reportDate = document.querySelector('input[name="report_date_input"]').value || "-";
            let targetSolved = document.querySelector('input[name="target_solved_date_input"]').value || "-";

            const reportDateOption = document.querySelector('input[name="report_date_option"]:checked').value;
            const targetDateOption = document.querySelector('input[name="target_date_option"]:checked').value;
            if (targetDateOption === "sla_target") {
                let slaInput = document.querySelector('input[name="sla"]');
                if (slaInput) {
                    targetSolved = slaInput.value + " " + messages.info_fct;
                }
            } else {
                let targetInput = document.querySelector('input[name="target_solved_date_input"]');
                if (targetInput) {
                    targetSolved = targetInput.value.replace("T", " ");
                }
            }

            console.log(targetSolved);
            
            

            // Update Summary Ticket Section
            document.getElementById("summaryPriority").innerText = priority || "-";
            document.getElementById("summaryCategory").innerText = category || "-";
            document.getElementById("summarySubCategory").innerText = subCategory || "-";
            document.getElementById("summaryInputFile").innerText = fileName;
            document.getElementById("summaryReportDate").innerText = reportDate.replace("T", " ");
            document.getElementById("summaryTargetSolved").innerText = targetSolved;
            document.getElementById("summaryNotes").innerText = notes;

            // Update Hidden Inputs in Send Ticket Modal
            document.querySelector('input[name="priority"]').value = priority;
            document.querySelector('input[name="category"]').value = category;
            document.querySelector('input[name="sub_category"]').value = subCategory;
            document.querySelector('input[name="report_date_option_val"]').value = reportDateOption;
            document.querySelector('input[name="report_date"]').value = reportDate;
            document.querySelector('input[name="target_date_option_val"]').value = targetDateOption;
            document.querySelector('input[name="target_solved_date"]').value = targetSolved;
            document.querySelector('input[name="notes"]').value = notes;
        }

        prevBtn.addEventListener("click", function () {
            if (currentTab > 0) {
                currentTab--;
                showTab(currentTab);
            }
        });

        nextBtn.addEventListener("click", function () {
            if (currentTab < tabs.length - 1) {
                if (validateCurrentTab()) {
                    currentTab++;
                    showTab(currentTab);
                }
            }
        });

        // Remove is-invalid class when clicking outside buttons
        document.addEventListener("click", function (event) {
            if (!event.target.matches("button")) {
                document.querySelectorAll(".is-invalid").forEach(field => {
                    field.classList.remove("is-invalid");
                });
            }
        });

        showTab(currentTab);
    });
</script>

@endsection

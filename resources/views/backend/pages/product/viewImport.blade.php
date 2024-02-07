@extends('backend.layouts.master')
@section('css_after')
    <style >
        .spinner {
            display: none;
        }
    </style>
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('product') }}">Product</a></li>
            <li class="breadcrumb-item">Import</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content ">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6 ">
            <div class="block-header">
                <h3 class="block-title">Import Product</h3>
            </div>
            <form class="js-validation form-prevent-multiple-submission" action="{{ url('product/import') }}" id="form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="val-username">Only .csv File <span class="text-danger">*</span></label>
                                    <p> must have - name, price, quantity, category(inlcuding title row) </p>
                                    <input type="file"  class="form-control" id="file" name="file" placeholder="..." required>
                                </div>
                            </div>
                        </div>
                        <!-- END Regular -->

                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary button-prevent-multiple-submission" id="submit">
                                    <i class="spinner fa fa-spinner fa-spin"></i>Submit
                                </button>
                            </div>
                        </div>
                        <!-- END Submit -->
                    </div>
                </div>
            </form>
            <!-- jQuery Validation -->
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')

    <script src="{{ asset('backend/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
    <script>
        $('.form-prevent-multiple-submission').on('submit',function() {
            $('.button-prevent-multiple-submission').attr('disabled', 'true');
            $('.spinner').show();
        })
        $('.input-prevent-multiple-submission').on('keypress',function() {
            $('.button-prevent-multiple-submission').removeAttr('disabled');
            $('.spinner').hide();
        })
        $('.input-prevent-multiple-submission').on('change' ,function() {
            $('.button-prevent-multiple-submission').removeAttr('disabled');
            $('.spinner').hide();
        })
    </script>
@endsection

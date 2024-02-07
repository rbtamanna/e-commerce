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
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content ">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6 ">
            <div class="block-header">
                <h3 class="block-title">Add Product</h3>
            </div>
            <form class="js-validation form-prevent-multiple-submission" action="{{ url('product/store') }}" id="form" method="POST" >
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="name" name="name" value="{{ old('name') }}"  placeholder="Enter a name.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Price <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control input-prevent-multiple-submission" id="price" name="price" value="{{ old('price') }}"  placeholder="Enter price.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="1" class="form-control input-prevent-multiple-submission" id="quantity" name="quantity" value="{{ old('quantity') }}"  placeholder="Enter quantity.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Category<span class="text-danger">*</span> </label>
                                    <select class="js-select2 form-control input-prevent-multiple-submission" id="category_id" name="category_id" style="width: 100%;" data-placeholder="Choose category.." required>
                                        <option></option>
                                        @foreach ($category as $c)
                                            <option value='{{ $c->id }}' style="color:black"> {{ $c->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END Regular -->

                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-5">
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

@extends('backend.layouts.master')
@section('css_after')
    <style >
        div#social-links {
            /*margin: 0 auto;*/
            /*max-width: 500px;*/
        }
        div#social-links ul li {
            display: inline-block;
        }
        div#social-links ul li a {
            padding: 5px;
            /*border: 1px solid #ccc;*/
            margin: 2px;
            /*font-size: 30px;*/
            /*color: #222;*/
            /*background-color: #ccc;*/
        }

    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row row-deck">
            <h3>Share This Page On</h3>
            {!! $shareComponent !!}

        </div>
        @for($i=0; $i<count($products); )
            <div class="row row-deck">
            @for($j=1; $j<=4 && $i<count($products); $i+=1, $j+=1)
                    <div class="col-sm-6 col-xl-3">
                        <!-- Pending Orders -->
                        <div class="block block-rounded d-flex flex-column">
                            <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                                <dl class="mb-0">
                                    <dt class="font-size-h2 font-w700">
                                                                        {{$products[$i]->name}}
                                    </dt>
                                    <dd class="text-muted mb-0">{{$products[$i]->category_name}}</dd>
                                    <dd class="text-muted mb-0">Quantity: {{$products[$i]->quantity}}</dd>
                                    <dd class="text-muted mb-0"> ${{$products[$i]->price}}</dd>
                                </dl>
                                <div class="item item-rounded bg-body">
                                    <i class="fa fa-box font-size-h3 text-primary"></i>
                                </div>
                            </div>
                            <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                                <a class="font-w500 d-flex align-items-center"
                                   @if(auth()->user() && $products[$i]->quantity>0)
                                        href="{{url('product/'.$products[$i]->id.'/purchase')}}"
                                   @endif
                                >
                                    Purchase
                                    <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                                </a>
                            </div>
                        </div>
                        <!-- END Pending Orders -->
                    </div>
            @endfor
            </div>
        @endfor





    </div>
@endsection
@section('js_after')

@endsection


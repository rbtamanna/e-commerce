@extends('backend.layouts.master')

@section('content')
    <div class="content">
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
                                    <dd class="text-muted mb-0"> ${{$products[$i]->price}}</dd>
                                </dl>
                                <div class="item item-rounded bg-body">
                                    <i class="fa fa-box font-size-h3 text-primary"></i>
                                </div>
                            </div>
                            <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                                <a class="font-w500 d-flex align-items-center" href="{{url('product/'.$products[$i]->id.'/purchase')}}">
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


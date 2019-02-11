@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('icommerce::shippingmethods.title.shippingmethods') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('icommerce::shippingmethods.title.shippingmethods') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    @if(isset($shippingMethods) && count($shippingMethods)>0)
                    
                        @php $c= 0; @endphp
                        <ul class="nav nav-tabs">
                            @foreach ($shippingMethods  as $index => $method)
                            <li @if($c==0) class='active' @endif>
                                <a data-toggle="tab" href="#{{$method['name']}}">{{$method->title}}</a>
                            </li>
                            @php $c++; @endphp
                            @endforeach
                        </ul>
    
                        @php $c= 0; @endphp
                        <div class="tab-content">
                            @foreach ($shippingMethods  as $ind => $method)
                              <div id="{{$method['name']}}" class="tab-pane fade @if($c==0) in active @endif ">
    
                                <h3>{{$method->title}}</h3>
                                @php

                                    $viewPath = $method->name.'s.index';

                                    $lastCharacter = substr($method->name, -1); 

                                    if($lastCharacter=='s')
                                        $viewPath = $method->name.'.index';

                                    if($lastCharacter=='y'){
                                        $methodName = substr($method->name, 0, -1); 
                                        $viewPath = $methodName.'ies.index';
                                    }
                                    
                                @endphp
                                
                                @include($method->name.'::admin.'.$viewPath)
                               
                              </div>
                              @php $c++; @endphp
                            @endforeach
                        </div>
                        
                    @else
                        <div class="alert alert-danger">
                            {{ trans('icommerce::shippingmethods.messages.no shipping methods') }}
                        </div>
                    @endif

                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')
@stop


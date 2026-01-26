@extends('frontend.layouts.app')
@section('scripts')
@stop
@section('styles')
@stop
@section('content')



    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('partials._loading')
            <div class="row justify-content-start">
                <section class="col-12 ">
                    <form action="{{route('reports.index', app()->getLocale())}}" method="GET" id="search-form">
                                        @csrf
                        {{--                @method('POST')--}}
                        <fieldset class="row form-group m-1">
                            <div class="input-group">
                                <input type="text" class="form-control" name="query" placeholder="Búsqueda"
                                       value="{{old('query',$post['query']??'')}}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </section>
            </div>
            <section id="list-item-2" class="list-items">
                <table class=" table table-bordered table-striped table-hover datatable" >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Categoría</th>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports ?? [] as $report)
                        <tr>
                            <td>{{$report->property->id ?? ''}}</td>
                            <td>{{$report->user->email ?? ''}}</td>
                            <td>{{$report->property->category->name ?? ''}}</td>
                            <td><span class="badge badge-{{$report->TypeBand}}">{{$report->option->name}}</span></td>

                            <td>{{$report->property->title ?? ''}}</td>

                            <td>
                                @if(isset($report->property) && isset($report->property->slug))
                                    <a target="_blank" href="{{route('get.property.by.slug', [app()->getLocale(), $report->property->slug])}}">
                                        <span><i class="fa fa-eye"></i> Ver</span>
                                    </a>
                                @endif
                                    @if(!is_null($report->property->isPublic()) && auth()->check() && in_array(auth()->user()->type, ['admin', 'super']))
                                        <a href="{{route("admin.privating.store",[app()->getLocale(), $report->property])}}" class="btn btn-warning btn-sm">{{trans('pages.my_ads.info_table.buttons.private')}}</a>
                                    @endif
                                    <a  href="{{route('reports.remover', [app()->getLocale(), $report->id])}}" class="btn btn-danger">
                                        <span><i class="fa fa-cancel"></i> Remover</span>
                                    </a>
                            </td>

{{--                            <td>{{$user->email}}</td>--}}
{{--                            <td>{{$user->created_at->diffForhumans()}}</td>--}}
{{--                            <td>{{$user->properties_count}}</td>--}}
{{--                            <td>{{$user->city->country->name ?? 'N/A'}}</td>--}}
{{--                            <td>{{$user->city->name ?? 'N/A'}}</td>--}}
{{--                            <td>{{$user->credits}}</td>--}}
{{--                            <td>--}}
{{--                                <div>--}}
{{--                                    <a href="{{route('admin.users.edit',$user)}}" class="action-btn">--}}
{{--                                        <i class="fas fa-edit edit" data-toggle="tooltip" data-placement="top" title="{{trans('global.edit')}} {{trans('global.admin.user.title_singular')}}"></i>--}}
{{--                                        <span>Edit</span>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </td>--}}
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </section>
            <section>
                {{$reports->links()}}
            </section>
            {{--                </div>--}}
            {{--                </div>--}}
        </div>
        <div class="col-md-4">

        </div>
    </div>
@endsection

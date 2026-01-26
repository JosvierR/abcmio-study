@extends('frontend.layouts.app')
@section('scripts')
    <script src="{{asset('js/categories.js')}}"></script>
@stop
@section('styles')
@stop
@section('content')
    <br>
    @if(\Auth::check())
        <div class="row">
            @if(!isset($category))
                <a href="{{route('admin.categories.create')}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i>
                    Crear Categoría
                </a>
            @else
                <a href="{{route('admin.categories.show',$category)}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i>
                    Crear Sub Categoría
                </a>
            @endif

        </div>
    @endif
    <br>
    <div class="form-group row">
        @if(isset($category)&&isset($category->parent))
            <a href="{{route('admin.category.child',$category->parent)}}" class="btn btn-primary">Volver</a>
        @else
            @if(isset($category)&&!isset($category->parent))
                <a href="{{route('admin.categories.index')}}" class="btn btn-primary">Volver</a>
            @else
            @endif
        @endif
    </div>
    <br>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <section id="list-item-2" class="list-items">
                <table class=" table table-bordered table-striped table-hover datatable" id="country_table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{$category->id}}</td>
                            <td>
                                @if(isset($category->parent))
                                    {{$category->name}}

                                @else
                                    <a href="{{route('admin.category.child',$category)}}" data-toggle="tooltip" data-placement="top" title="Ver Sub Categoría">
                                        {{$category->name}}
                                        <small>({{$category->children->count()}})</small>
                                    </a>
                                @endif
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                @if(!$category->children->count())
                                    <div class="trash">
                                        <form action="{{route('admin.categories.destroy',$category)}}" method="POST" class="act-delete">
                                            @method('DELETE')
                                            @csrf
                                            {{--                                        <a href="{{route('admin.categories.edit',$category)}}" class="action-btn">--}}
                                            {{--                                            <i class="fas fa-edit edit" data-toggle="tooltip" data-placement="top" title="Editar Categoría"></i>--}}
                                            {{--                                        </a>--}}
                                            <button type="submit" class="btn action action-btn">
                                                <i class="fas fa-trash trash" data-toggle="tooltip" data-placement="top" title="Borrar: {{$category->name}} "></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <a href="{{route('admin.categories.edit',$category)}}" class="action-btn">
                                        <i class="fas fa-edit edit" data-toggle="tooltip" data-placement="top" title="Editar Categoría"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>







@endsection

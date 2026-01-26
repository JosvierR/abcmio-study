@extends('frontend.layouts.app')
@section('scripts')
    <script src="{{asset('js/categories.js')}}"></script>
@stop
@section('styles')
@stop
@section('content')



    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('partials._loading')
            <div class="row justify-content-start">
                <section class="col-12 ">
                    <form action="{{route('admin.users.index')}}" method="GET" id="search-form">
                        {{--                @csrf--}}
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
                            <th>Email</th>
                            <th>Creado</th>
                            <th>Anuncios</th>
                            <th>Pais</th>
                            <th>Creditos</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->created_at->diffForhumans()}}</td>
                            <td>{{$user->properties_count}}</td>
                            <td>{{$user->country->name ?? 'N/A'}}</td>
                            <td>{{$user->credits}}</td>
                            <td>
                                <div>
                                    <a href="{{route('admin.users.edit',$user)}}" class="action-btn">
                                        <i class="fas fa-edit edit" data-toggle="tooltip" data-placement="top" title="{{trans('global.edit')}} {{trans('global.admin.user.title_singular')}}"></i>
                                        <span>Edit</span>
                                    </a>
                                </div>
                                <form action="{{route('admin.users.destroy',$user)}}" method="POST" class="act-delete deleteGroup"

                                >
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn action action-btn btn-danger btn-sm">
                                        <i class="fas fa-trash trash" data-toggle="tooltip" data-placement="top" title="Borrar: {{$user->email}} "> </i> {{trans('pages.my_ads.info_table.buttons.delete')}}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <section>
                {{$users->links()}}
            </section>
            {{--                </div>--}}
            {{--                </div>--}}
        </div>
        <div class="col-md-4">

        </div>
    </div>
@endsection

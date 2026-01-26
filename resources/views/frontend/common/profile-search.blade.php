@include('partials._loading')
<div class="row justify-content-start">
    <section class="col-12 ">
{{--        @php $URL = $searchUrl ?? "search.property.results" @endphp--}}
        <form action="{{ route('search.property', app()->getLocale())}}" method="GET" id="search-form">
            @include('partials.search._form_properties')

        </form>
    </section>
</div>

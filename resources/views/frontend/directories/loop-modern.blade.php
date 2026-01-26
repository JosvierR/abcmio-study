<div class="row">
    <div class="col-12 col-md-8 mt-md-5 mx-auto">
        <h3 class="m-3"><small><strong>{{$properties->total()}} </strong> {{trans('properties.properties.total_found')}}</small></h3>

        <section id="directory-products">
            <nav aria-label="Page navigation example" class="d-flex m-3">
                <ul class="pagination">
                    {{$properties->appends(request()->input())->links()}}
                </ul>
            </nav>
            
            {{-- List Layout --}}
            <ul class="mt-4 list-unstyled">
                @foreach($properties ?? [] as $property)
                    <li class="mb-4 @if($property->IsOwner)owner border-primary rounded p-2@endif">
                        <article class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3 text-center">
                                        <a href="{{route('get.property.by.slug',[app()->getLocale(), $property->slug])}}">
                                            <img src="{{$property->ThumbImage}}" 
                                                 alt="{{$property->title}}" 
                                                 class="img-fluid rounded mb-3" 
                                                 style="max-height: 150px; object-fit: cover;"/>
                                        </a>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <a href="{{route('get.property.by.slug',[app()->getLocale(), $property->slug])}}" class="text-decoration-none text-dark">
                                            <h3 class="h5 text-break text-md-left text-center mb-2">{{$property->title}}</h3>
                                        </a>
                                        
                                        <h2 class="h6 text-primary text-break text-center text-md-start mb-3">
                                            <strong>{{$property->business_name}}</strong>
                                        </h2>
                                        
                                        {{-- Description (Desktop only) --}}
                                        <div class="d-none d-sm-block mb-3">
                                            <p class="text-break text-muted">
                                                {!! $property->Excerpt ?? '' !!}
                                            </p>
                                        </div>
                                        
                                        {{-- Category --}}
                                        @if($property->category)
                                        <p class="mb-2">
                                            <span class="badge badge-secondary">
                                                @if($property->category->parent){{$property->category->parent->name}} / @endif
                                                {{$property->category->name}}
                                            </span>
                                        </p>
                                        @endif
                                        
                                        {{-- Country --}}
                                        @if($property->country)
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                            <strong>{{$property->country->name}}</strong>
                                        </p>
                                        @endif
                                        
                                        {{-- Owner Actions (Desktop only) --}}
                                        @if($property->IsOwner)
                                        <div class="d-none d-sm-block mb-2">
                                            <a href="{{route('properties.show',[app()->getLocale(), $property])}}" class="text-primary">
                                                <i class="fas fa-edit"></i> Editar Anuncio
                                            </a>
                                        </div>
                                        @endif
                                        
                                        {{-- Admin Email --}}
                                        @if(auth()->check() && in_array(auth()->user()->type, ['super', 'admin']))
                                        <div class="d-none d-sm-block">
                                            <p class="text-muted small mb-2">{{$property->user->email ?? 'no email'}}</p>
                                        </div>
                                        @endif
                                        
                                        {{-- Contact Information --}}
                                        <div class="row">
                                            @if(!empty($property->social_network))
                                            <div class="col-12 col-md-6 mb-2">
                                                <i class="fas fa-globe text-muted"></i>
                                                <a href="{{$property->social_network}}" target="_blank" class="text-decoration-none">
                                                    {{trans('pages.forms.ads.global.inputs.socials_media.social_network.label')}}
                                                </a>
                                            </div>
                                            @endif
                                            
                                            @if(!empty($property->whatsapp_number))
                                            <div class="col-12 col-md-6 mb-2">
                                                <a class="property-links text-decoration-none"
                                                   href="https://wa.me/{{$property->whatsapp_number}}"
                                                   data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Hacer click para contactar por whatsapp"
                                                   target="_blank">
                                                    <img src="{{asset('images/logo-whtsapp.png')}}" width="30" alt="">
                                                    <strong>{{$property->whatsapp_number}}</strong>
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Visitors Counter --}}
                                <div class="mt-3">
                                    <div class="text-center text-md-left">
                                        <small class="text-muted">
                                            <i class="fa fa-user"></i>
                                            <span class="counter">{{App\Services\PropertyService::getPropertyVisitors($property)}}</span>
                                            {{trans('global.visitors')}}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </li>
                @endforeach
            </ul>
            
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    {{$properties->appends(request()->input())->links()}}
                </ul>
            </nav>
        </section>
    </div>
    
    {{-- Sidebar with total visitors --}}
    <div class="col-md-2">
        <section class="wow fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 text-center counter-section wow fadeInUp animated" 
                         data-wow-duration="0ms" 
                         style="visibility: visible; animation-duration: 1200ms; animation-name: fadeInUp;">
                        <i class="fa fa-user medium-icon"></i>
                        <span class="timer counter alt-font appear" data-to="600" data-speed="100">{{$visitors ?? 0}}</span>
                        <span class="counter-title">{{trans('global.visitors')}}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('styles')
<style>
    /* Modern layout styles preserving original structure */
    #directory-products .card {
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    #directory-products .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    #directory-products li.owner .card {
        border: 2px solid #007bff;
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    #directory-products .badge {
        font-weight: normal;
        padding: 0.375rem 0.75rem;
    }
    
    #directory-products .property-links:hover {
        text-decoration: none;
        opacity: 0.8;
    }
    
    /* Counter styles */
    .counter-section {
        font-size: 18px;
    }
    
    .counter {
        font-weight: bold;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #directory-products .card-body {
            padding: 1rem;
        }
        
        #directory-products h3 {
            font-size: 1.1rem;
        }
        
        #directory-products h2 {
            font-size: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        // Counter animation
        $('.counter').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 4000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
        
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
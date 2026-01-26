<section class="wow fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
    <div class="container">
        <div class="row">
            <!-- counter -->
            <div class="col-md-3 col-sm-6 text-center counter-section wow fadeInUp animated" data-wow-duration="1200ms" style="visibility: visible; animation-duration: 1200ms; animation-name: fadeInUp;">
                <i class="fa fa-user medium-icon"></i>
                <span class="timer counter alt-font appear" data-to="600" data-speed="7000">{{App\Services\PropertyService::getPropertyVisitors($property)}}</span>
                <span class="counter-title">{{trans('global.visitors')}}</span>
            </div>
            <!-- end counter -->
        </div>
    </div>
</section>

<div class="d-flex justify-content-center flex-row">
@foreach (config('app.available_locales') ?? [] as $locale)
    <div class="p-2"><a class="nav-link {{(app()->getLocale() == $locale) ? 'nav-lang-active' : ''}}"
                href="{{\RouteHelper::getUrl(['locale' => $locale])}}"
                 @if (app()->getLocale() == $locale) style="font-weight: bold; text-decoration: underline" @endif>{{ strtoupper($locale) }}
            </a></div>
@endforeach
</div>

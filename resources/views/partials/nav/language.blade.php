@foreach (config('app.available_locales') ?? [] as $locale)
    <li class="nav-item " id="language-navbar">
        <a class="nav-link {{(app()->getLocale() == $locale) ? 'nav-lang-active' : ''}}"
           href="{{RouteHelper::getUrl(['locale' => $locale])}}"
           @if (app()->getLocale() == $locale) style="font-weight: bold; text-decoration: underline" @endif>{{ strtoupper($locale) }}
        </a>
    </li>
@endforeach
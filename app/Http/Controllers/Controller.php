<?php

namespace App\Http\Controllers;

use App\Category;
use App\Country;
use App\Managers\VisitorManager;
use App\Property;
use App\Services\FilterService;
use App\Services\ProductService;
use App\Services\ReportService;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\Array_;
use Carbon\Carbon;

class Controller extends BaseController
{
    public function __construct()
    {
        $this->expired_properties();
    }

    protected $limit = 10;
    protected $site_section_name = '';
    protected $search_url = 'search';

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function get_content_site(Request $request = null, $isIndex = false, $myAds = false, $args = []): array
    {
        $cityId = null;
        $post = optional($request)->all() ?? null;
        $countryId = $post['country_id'] ?? null;
        $categoryId = $post['category_id'] ?? null;
        $SubCategoryId = $post['sub_category_id'] ?? null;

        $user = \Auth::user()->id ?? null;

        if (\Auth::check() && (!isset($post['city_id']) || !isset($post['country_id']))) {
            $countryId = \Auth::user()->country->id ?? null;
        }

        $countries = [];
        $categories = [];

        if ($isIndex) {
            $countries = Country::orderBy('name', 'ASC')->get();
            $categories = Category::where('parent_id', 0)->orderBy('name', 'ASC')->get();

            $dataFilter = FilterService::generateDataFilter($post, $user);
            if ($myAds) {
                $isPublic = ($request->has('is_publish') && $request->is_publish) ? true : false;

                $dataFilter = FilterService::generateDataFilter($request->all(), auth()->user());
//                dd($dataFilter);
//                $properties = auth()->user()->properties()
//                    ->filter($dataFilter)
//                    ->when(true, function ($query) use ($isPublic){
//                        return $query->where('is_public', $isPublic);
//                    })
//                    ->orderBy('id', 'DESC')
//                    ->paginate(50);
                $properties = ProductService::getMyAdsByFilter($dataFilter);
            } else {
                $properties = ProductService::getFilteredProduct($dataFilter);
            }
        } else {
            $properties = [];
        }

        $visitors = (new VisitorManager())->getVisitorCount();

        return [
            'visitors' => $visitors,
            'countryId' => $countryId,
            'cityId' => $cityId,
            'categoryId' => $categoryId,
            'SubCategoryId' => $SubCategoryId,
            'countries' => $countries,
            'categories' => $categories,
            'searchUrl' => $this->getSearchUrl(),
            'post' => $post,
            'site_section_name' => $this->getSectionName(),
            'properties' => $properties,
            'args' => $args,
            'total_report' => (new ReportService)->getTotal()
        ];
    }

    /**
     * Set Search URL path
     * @param string $string
     * @return void
     */
    public function setSectionName(string $string = ''): void
    {
        $this->site_section_name = $string;
    }

    /**
     * Set Search URL path
     * @param string $string
     * @return void
     */
    public function setSearchUrl(string $string = 'search'): void
    {
        $this->search_url = $string;
    }

    /**
     * Get Search URL
     * @return string
     */
    public function getSearchUrl(): string
    {
        return $this->search_url;
    }

    /**
     * Get SectionName URL
     * @return string
     */
    protected function getSectionName(): string
    {
        return $this->site_section_name;
    }

    protected function send_email($view = 'test', $senders = [], $args = [], $subject = 'ABCMIO', $bcc = [])
    {
//        return view("melon.emails.$view",compact('args','senders'));
        Mail::send("emails.$view", compact('args'), function ($m) use ($senders, $subject, $bcc) {
            $m->from('info@abcmio.cpichardo.com', 'ABCMIO');
            $m->to($senders)
                ->bcc($bcc)
                ->subject($subject);
        });
        return true;
    }

    /**
     * @param Request $request
     *
     * @return  array
     * @todo Retrieve All Properties  with Search Filter
     */
    protected function getPropertySearch(Request $request)
    {
        $post = null;
//        $sort = \Auth::check()? 'ASC':'DESC';
        $sort = 'DESC';
        if ($request->has('query') || $request->has('query') || $request->has('query') || $request->has('query')) {
            $request->session()->forget("search");
            if (!$request->session()->has('search')) {
                $request->session()->put('search', $request->all());
                $post = $request->all();
            } else {
                $post = $request->session()->get("search");
            }
        }
        $properties = Property::where('status', 'enable')
            ->where('is_public', true)
            ->where(function ($query) use ($post) {
                if (isset($post['query']) && !empty(trim($post['query']))) {
                    if (isset($post['exact_match'])) {
                        return $query->where('title', 'like', '%' . strtolower($post['query']) . '%')
                            ->orWhere('description', 'like', '%' . strtolower($post['query']) . '%');
                    } else {
                        $arr = explode(" ", strtolower($post['query']));
                        foreach ($arr as $value) {
                            $query->orWhere('title', 'like', '%' . $value . '%');
                            $query->orWhere('description', 'like', '%' . $value . '%');
                        }
                    }
                }
            })
            ->with('category')
            ->with('city')
            ->whereHas('category', function ($query) use ($post) {
                if (isset($post['sub_category_id']) && $post['sub_category_id'] != -1) {
                    return $query->where('id', $post['sub_category_id']);
                }

                if (isset($post['category_id']) && $post['category_id'] != -1 && isset($post['sub_category_id']) && $post['sub_category_id'] == -1) {
                    return $query->where('parent_id', $post['category_id']);
                }
            })
            ->whereHas('city', function ($query) use ($post) {
                if (\Auth::check() && !isset($post['city_id'])) {
                    $user = \Auth::user();
                    return $query->where('id', $user->city_id);
                } else {
                    if (isset($post['city_id']) && $post['city_id'] != -1) {
                        return $query->where('id', $post['city_id']);
                    }

                    if (isset($post['city_id']) && $post['city_id'] == -1 && isset($post['country_id']) && $post['country_id'] != -1) {
                        return $query->where('country_id', $post['country_id']);
                    }
                }
            })
            ->orderBy('id', $sort)
            ->inRandomOrder()
            ->paginate($this->limit);
        return $properties;
    }

    /**
     * @param Request $request
     *
     * @return  array
     * @todo Generate the Post Array By Request Resources
     */
    protected function getPropertyFilterSearch(Request $request)
    {
        $post = null;
        if ($request->has('query') || $request->has('query') || $request->has('query') || $request->has('query')) {
            $request->session()->forget("search");
            if (!$request->session()->has('search')) {
                $request->session()->put('search', $request->all());
                $post = $request->all();
            } else {
                $post = $request->session()->get("search");
            }
        }
        return $post;
    }

    /**
     * @param App\User $user
     * @param Request $request
     *
     * @return  array
     * @todo Retrieve All Properties of a User with Search Filter
     */
    public function getPropertyDirectoryByUser(User $user, Request $request)
    {
        $post = null;
//        $sort = \Auth::check()? 'ASC':'DESC';
        $sort = 'DESC';
        if ($request->has('query') || $request->has('query') || $request->has('query') || $request->has('query')) {
            $request->session()->forget("search");
            if (!$request->session()->has('search')) {
                $request->session()->put('search', $request->all());
                $post = $request->all();
            } else {
                $post = $request->session()->get("search");
            }
        }

        $properties = $user->properties()
            ->where(function ($query) use ($post) {
                if (isset($post['query']) && !empty(trim($post['query']))) {
                    if (isset($post['exact_match'])) {
                        return $query->where('title', 'like', '%' . strtolower($post['query']) . '%')
                            ->orWhere('description', 'like', '%' . strtolower($post['query']) . '%');
                    } else {
                        $arr = explode(" ", strtolower($post['query']));
                        foreach ($arr as $value) {
                            $query->orWhere('title', 'like', '%' . $value . '%');
                        }
                    }
                }
            })
            ->with('category')
            ->with('city')
            ->whereHas('category', function ($query) use ($post) {
                if (isset($post['sub_category_id']) && $post['sub_category_id'] != -1) {
                    return $query->where('id', $post['sub_category_id']);
                }

                if (isset($post['category_id']) && $post['category_id'] != -1 && isset($post['sub_category_id']) && $post['sub_category_id'] == -1) {
                    return $query->where('parent_id', $post['category_id']);
                }
            })
            ->whereHas('city', function ($query) use ($post) {
                if (isset($post['city_id']) && $post['city_id'] != -1) {
                    return $query->where('id', $post['city_id']);
                }

                if (isset($post['city_id']) && $post['city_id'] == -1 && isset($post['country_id']) && $post['country_id'] != -1) {
                    return $query->where('country_id', $post['country_id']);
                }
            })
            ->orderBy('id', $sort)
            ->paginate($this->limit);
        return $properties;
    }

    /**
     * @param App\User $user
     * @param Request $request
     *
     * @return  array
     * @todo Retrieve All Properties of a User with Search Filter
     */
    public function getPropertyByUser(User $user, Request $request)
    {
        $post = null;
//        $sort = \Auth::check()? 'ASC':'DESC';
        $sort = 'DESC';
        if ($request->has('query') || $request->has('query') || $request->has('query') || $request->has('query')) {
            $request->session()->forget("search");
            if (!$request->session()->has('search')) {
                $request->session()->put('search', $request->all());
                $post = $request->all();
            } else {
                $post = $request->session()->get("search");
            }
        }

        $properties = $user->properties()
            ->where(function ($query) use ($post) {
                if (isset($post['query']) && !empty(trim($post['query']))) {
                    if (isset($post['exact_match'])) {
                        return $query->where('title', 'like', '%' . strtolower($post['query']) . '%')
                            ->orWhere('description', 'like', '%' . strtolower($post['query']) . '%');
                    } else {
                        $arr = explode(" ", strtolower($post['query']));
                        foreach ($arr as $value) {
                            $query->orWhere('title', 'like', '%' . $value . '%');
                            $query->orWhere('description', 'like', '%' . $value . '%');
                        }
                    }
                }
            })
            ->with('category')
            ->with('city')
            ->whereHas('category', function ($query) use ($post) {
                if (isset($post['sub_category_id']) && $post['sub_category_id'] != -1) {
                    return $query->where('id', $post['sub_category_id']);
                }

                if (isset($post['category_id']) && $post['category_id'] != -1 && isset($post['sub_category_id']) && $post['sub_category_id'] == -1) {
                    return $query->where('parent_id', $post['category_id']);
                }
            })
            ->whereHas('city', function ($query) use ($post) {
                if (isset($post['city_id']) && $post['city_id'] != -1) {
                    return $query->where('id', $post['city_id']);
                }
                if (isset($post['city_id']) && $post['city_id'] == -1 && isset($post['country_id']) && $post['country_id'] > -1) {
                    return $query->where('country_id', $post['country_id']);
                }
            })
            ->orderBy('id', $sort)
            ->paginate($this->limit);
        return $properties;
    }

    public function expired_properties()
    {
        $properties = Property::where("expire_date", '<>', null)
//            ->where("start_date", "<=", Carbon::now())
            ->where("expire_date", "<", \Carbon\Carbon::now())
            ->update(['is_public' => false, "start_date" => null, "expire_date" => null]);
    }

    protected function isAble(Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
    }
}

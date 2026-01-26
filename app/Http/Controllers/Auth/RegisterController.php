<?php

namespace App\Http\Controllers\Auth;

use App\Country;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Mail;
use App\Mail\WelcomeMail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function redirectTo()
    {
        return app()->getLocale() . '/';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $this->setSectionName(trans('nav.header.nav.directory'));
        $countries = Country::orderBy('name', 'asc')->get();
        return view('frontend.auth.register', compact('countries'))
            ->with($this->get_content_site(null, true));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'country_id' => ['required', 'numeric', 'min:1'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'g-recaptcha-response' => ['required', 'captcha']
        ]);
    }

    protected function messages() {
        return [
            'country_id.required' => 'sDebes seleccionar un país',
            'g-recaptcha-response' => [
                'required' => 'Please verify that you are not a robot.',
                'captcha' => 'Captcha error! try again later or contact site admin.',
            ],
        ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data,$password = '12345')
    {

//        $password = Str::random(8);
        $token = Str::random(191);
//        $passEnc = Hash::make($password);

        list($name,) = explode('@',$data['email']);

        $user = User::create([
            'name' => $name,
            'country_id'=> $data['country_id'],
            'email' => $data['email'],
            'password' => $data['password'],
            'token'=>$token
        ]);
        \Auth::login($user);
//        Mail::to($data['email'])->send(new WelcomeMail($user,$password,$token));

        return $user;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
//        return $request->all();
        $this->validator($request->all())->validate();

//        $password = Str::random(8);
        event(new Registered($user = $this->create($request->all())));

//        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('message', trans('notifications.success.label'));
    }

}

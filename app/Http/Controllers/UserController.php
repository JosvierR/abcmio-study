<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\SentCreditRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\SentCreditMail;
use App\Mail\UserSentCreditMail;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Mail;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function sendCreditForm()
    {
        $this->setSectionName(trans('nav.header.nav.sell-credits'));
        return view('frontend.credits.sendcredits')
            ->with($this->get_content_site());
    }

    /**
     * Envio de Creditos a un amigo
     * @param Request $request
     *
     * @return Redirect
     */
    public function creditsSent(SentCreditRequest $request)
    {
        if (auth()->user() === $request->email) {
            return back()->withErrors(trans('pages.credits.send.message.error.auto_assign'));
        }

        if ($user = User::where('email', $request->email)->first()) {
            $cUser = auth()->user();
            if ((int)$request->credits <= (int)$cUser->credits) {
                $user->credits += (int)$request->credits;
                $user->save();

                $cUser->credits -= (int)$request->credits;
                $cUser->save();

//                Sent EMail
//                Mail::to([$user->email])->send(new SentCreditMail($request->credits, auth()->user()->email,
//                    $user->email));
//                Mail::to([auth()->user()->email])->send(new UserSentCreditMail($request->credits, auth()->user()->email,
//                    $user->email));
                return redirect()->back()->with('success', trans('pages.credits.send.message.success'));
            } else {
                return redirect()->route('send.credits', app()->getLocale())->withErrors(trans('pages.credits.send.message.error.max_limit'));
            }
        } else {
            return redirect()->route('send.credits', app()->getLocale())->withErrors(trans('pages.credits.send.message.error.email_not_found'));
        }
    }

    public function profile()
    {
        $user = \Auth::user();
        $this->setSectionName(trans('global.admin.profile.title_singular'));

        return view('frontend.account.profile', compact('user'))->with($this->get_content_site());
    }

    /**
     * Update Profile Info
     * @param \Illuminate\Http\Request $request
     * @@return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        $id = \Auth::user()->id;
        $user = User::find($id);

        $user->name = $request->name;
        $birth = Carbon::createFromFormat('d/m/Y', $request->birth_date)->toDateString();
        $user->birth_date = $birth;
        $user->country_id = $request->country;

        if ($request->has('current_password') && !empty(trim($request->current_password))) {
            $validator = \Validator::make([
                'current_password' => $request->current_password,
                'new_password' => $request->new_password,
                'new_confirm_password' => $request->new_confirm_password,
            ],[
                'current_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
                'new_confirm_password' => ['same:new_password'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('profile')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $user->update(['password' => \Hash::make(trim($request->new_password))]);
            }
        }


        if ($request->has('confirmed')) {
            $user->confirmed = (int)$request->confirmed;
            $user->token = null;
        }


        $user->save();
        return redirect()->route('profile', app()->getLocale())->with("success", trans('notifications.profile.updated.success'));
    }
}

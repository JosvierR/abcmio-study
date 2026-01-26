<?php

use App\Category;
use App\City;
use App\Country;
use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailResetPassword;
use App\Property;

//use Mail;

class TestSeeder extends Seeder
{

    function plusMinus($arr)
    {
        // Write your code here
        $positive = 0;
        $negative = 0;
        $zero = 0;
        foreach ($arr as $value) {
            if ($value > 0) {
                $positive++;
            } elseif ($value < 0) {
                $negative++;
            } else {
                $zero++;
            }
        }

        print number_format($positive / sizeof($arr), '6') . "\n";
        print  number_format($negative / sizeof($arr), '6') . "\n";
        print number_format($zero / sizeof($arr), '6'  ) . "\n";
    }

    function staircase($n) {
        // Write your code here
        for($y = 0 ; $y < $n; $y ++) {
            for($x = 0; $x < $n ; $x ++) {
                if( (($n - $x) -1 ) === $y || $x >= ($n - $y  ) ){
                    print "#";
                }else {
                    print " ";
                }
            }
            print "\n";
        }
    }

    function miniMaxSum($arr) {
        sort($arr);
        $old = $arr;
        unset($arr[0]);
        $arrayMin = $arr;
        unset($old[sizeof($old) - 1]);
        $arrayMax =  $old;
         print array_sum($arrayMax) . ' ' . array_sum($arrayMin);
    }

    function birthdayCakeCandles($candles) {
        // Write your code here
        $max = max($candles);
        $count = 0;
        foreach($candles as $value) {
            if($value === $max ) {
                $count ++;
            }
        }
        return $count;
    }

    function timeConversion($s) {
        // Write your code here
        $ampm = trim(substr($s, -2));
        $time = str_replace($ampm, '', $s);
        list($hour, $minute, $second) = explode(':', $time);
        if($ampm === "PM" && (int)$hour < 12) {
             $hour = ((int) $hour + 12);
        }elseif($ampm  === "AM" && (int) $hour == 12) {
            $hour = ((int) $hour - 12);
        }
        return implode(':', [str_pad($hour, '2', '0', STR_PAD_LEFT), str_pad($minute, '0', STR_PAD_LEFT), str_pad($second, '0', STR_PAD_LEFT)]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        $n = intval(trim(fgets(STDIN)));
//        $n = 5;
//        $n = 6;

//        $arr_temp = rtrim(fgets(STDIN));

//        $arr = array_map('intval', preg_split('/ /', $arr_temp, -1, PREG_SPLIT_NO_EMPTY));
//        $arr = [1,1,0,-1,-1];
//        $arr = [-4, 3, -9, 0, 4, 1];

//        dd($this->plusMinus($arr));
//        $this->staircase($n);
//        $arr = [1,5,3,4,5];
//        dd($this->birthdayCakeCandles($arr));
//        dd($this->miniMaxSum($arr));
        $s = "05:01:00PM";
        $s = "02:15:00PM";
        $s = "07:05:45PM";
        dd($this->timeConversion($s));
        die();
        // $this->exportCategories();
//        $this->exportUsers();
        $properties = Property::where("expire_date", '<>', null)
//            ->where("start_date", "<=", Carbon::now())
            ->where("expire_date", "<",
                \Carbon\Carbon::now())//            ->update(['is_public' => false, "start_date" => null, "expire_date" => null])
        ;
        dd($properties->count());
    }

    public function exportUsers()
    {
        $filePath = public_path() . '/data/csv/users-imported-sql.csv';
        if ($fp = fopen($filePath, 'w+')) {
            $buffer = ["id", "name", "email", "birth_date", "gender", "type", "confirmed", "credits"];
            fputcsv($fp, $buffer);
            foreach (User::all() as $user) {
                // print base64_encode($property->description) . "\n";
                // $image = $property->getMedia('photo')->isNotEmpty() ?   $property->getMedia('photo')->first()->getUrl() : -1;
                // if($image !== -1){
                // print $image;
                // print "\n";
                // }
                $buffer = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->birth_date,
                    $user->gender,
                    $user->type,
                    $user->confirmed,
                    $user->credits
                ];
                fputcsv($fp, $buffer);
            }

            fclose($fp);
        }
    }

    public function exportProperties()
    {
        $properties = Property::all();
        $filePath = public_path() . '/data/csv/imported-sql.csv';
        if ($fp = fopen($filePath, 'w+')) {
            $buffer = [
                "id",
                "title",
                "category_id",
                "user_id",
                "image_url",
                "website",
                "show_website",
                "is_public",
                "phone",
                "address",
                "email",
                "google_map",
                "start",
                "expire",
                "description"
            ];
            fputcsv($fp, $buffer);
            foreach ($properties as $property) {
                // print base64_encode($property->description) . "\n";
                $image = $property->getMedia('photo')->isNotEmpty() ? $property->getMedia('photo')->first()->getUrl() : -1;
                // if($image !== -1){
                // print $image;
                // print "\n";
                // }
                $buffer = [
                    $property->id,
                    $property->title,
                    $property->category_id,
                    $property->user_id,
                    $image,
                    $property->website,
                    $property->show_website,
                    $property->is_public,
                    $property->address,
                    $property->phone,
                    $property->email,
                    $property->google_map,
                    $property->start_date,
                    $property->expire_date,
                    base64_encode($property->description)
                ];
                fputcsv($fp, $buffer);
            }

            fclose($fp);
        }
    }

    public function exportCategories()
    {
        $filePath = public_path() . '/data/csv/categories-imported-sql.csv';
        if ($fp = fopen($filePath, 'w+')) {
            $buffer = ["id", "name", "parent_id", "is_free"];
            fputcsv($fp, $buffer);
            foreach (Category::all() as $category) {
                // print base64_encode($property->description) . "\n";
                // $image = $property->getMedia('photo')->isNotEmpty() ?   $property->getMedia('photo')->first()->getUrl() : -1;
                // if($image !== -1){
                // print $image;
                // print "\n";
                // }
                $buffer = [$category->id, $category->name, $category->parent_id, $category->is_free];
                fputcsv($fp, $buffer);
            }

            fclose($fp);
        }
    }

    public function sendMail()
    {
        $user = User::findOrFail(1);
        $password = strtolower(\Str::random(8));
        $user->password = \Hash::make($password);
        $user->save();
        Mail::to($user->email)->send(new MailResetPassword($password));
//        Mail::t('emails.password_reset', ['user' => $user,"password"=>$password], function ($m) use ($user) {
//            $m->from('hello@app.com', 'Your Application');
//
//            $m->to($user->email, $user->name)->subject('Your Reminder!');
//        });
    }

    public function test()
    {
        //        $country = 1;
//        $category = null;
//        $parent = null;

//        if($request->has("country_id") && $request->country_id != -1)
//            $country = $request->country_id;

//        if($request->has("category_id") && $request->category_id != -1)
//            $category = $request->category_id;

//        if($request->has("parent") && $request->parent != -1)
//            $parent = $request->category_id;

//        $cities =  City::whereHas("properties", function($query) use ( $category, $parent){
//            return $query->with('category')
//                ->where("status","enable")
//                ->where("is_public",true)
//                ->whereHas("category",function($query) use ($category,$parent){
//                if(!is_null($parent))
//                    $query->where("parent_id",$parent);
//                if(!is_null($category))
//                    $query->where("category_id",$category);
//            });
//
//        })->where("country_id",$country)
//            ->orderBy("name","ASC")->get();
//
//        dd($cities);
//        $this->sendMail();
//        print "Start \n";
//        sleep(10);
//        print "Second \n";
//        print "Third \n";
//        sleep(30);
//        print "Finished \n";
//        return Country::whereHas('cities',function($q) {
//            return $q->whereHas('properties',function($q){
//                return $q->where('is_public',true);
//            });
//        })->count();

    }
}

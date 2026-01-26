-- Image Intervention library
This Library can resize the image dimension

http://image.intervention.io/getting_started/installation


-- Optimize images in your Laravel app
This Library can reduce image weight 

https://github.com/spatie/laravel-image-optimizer


LOad Youtube Video
https://laraveldaily.com/embed-and-parse-youtube-vimeo-videos-with-laravel-embed-package/


Repo para Vue + Laravel Articles

https://github.com/bradtraversy/larticles_api

ADS Managers
https://www.laraship.com/laraship-laravel-advertisement-module/

https://github.com/adumskis/laravel-advert

Select2
https://select2.org/getting-started/basic-usage


Error page
https://tutsforweb.com/how-to-create-custom-404-page-laravel/

http://app.bluemax.tv

ssh cpicmkpw@cpichardo.com -p 21098
pass: SupeR@0614!

https://github.com/php-tmdb/api/tree/2.1/examples/search/api

NameCheap hosting install composer and laravel 
https://www.namecheap.com/support/knowledgebase/article.aspx/9977/29/how-to-install-composer-on-shared-servers

Social Share Buttons
https://github.com/jorenvh/laravel-share

image paths and size:
http://image.tmdb.org/t/p/w500/
Hi again, these are the the sizes that I know: "w92", "w154", "w185", "w342", "w500", "w780", or "original"; and I think there isn't any other sizes "original" will give you a very large poster, if you're on mobile "w185" is the best choice

$result = $client->getSearchApi()->searchKeyword('scary');
$result = $client->getSearchApi()->searchPersons('bruce lee');
$result = $client->getSearchApi()->searchCollection('star wars');
$result = $client->getSearchApi()->searchList('award');

$ php artisan key:generate

$ php artisan clear-compiled 
$ composer dump-autoload
$ php artisan optimize
$ php artisan cache:clear
$ php artisan route:cache 



GREP and put in file 

php artisan route:list | grep 'api' > endpoints.txt



php artisan migrate:refresh --seed
php artisan db:seed --class=CreateSchedules


--Con esto puedo llamar al paciente con todos sus datos.
App\Patient::with(['user','user.contacts','user.contacts.phones','user.contacts.emails'])->find(2)

-- Using Where Like 
App\Country::where('name', 'like', '%USA%')->get();

-- Where Like
$s = App\Speciality::where('name','like','%odont%')->first()

-- Cuando deseamos tener una relacion howToMany, donde el doctor tiene especialidades
es a la especialidad que le hacemos la relacion , en el caso de que sea uno a uno al doctor

Ex: 
users -> no tiene este contact_id 

            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->integer('city_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            
            
-- en el caso de specialities , el modelo debe tener el forign_key , no estoy 100% por el 
-- caso del modelo de contacts no lo tiene 
     class Speciality extend ...
         public function doctors(){
            return $this->belongsTo('App\Doctor','doctor_id')->with('user');
         }
        
        https://laracasts.com/discuss/channels/requests/created-boot-method-trigger-with-validation
public static function boot()
    {
        parent::boot();

        self::creating(function($model){

        });

        self::created(function($model){
            // ... code here
        });

        self::updating(function($model){
            // ... code here
        });

        self::updated(function($model){
            // ... code here
        });

        self::deleting(function($model){
            // ... code here
        });

        self::deleted(function($model){
            // ... code here
        });
    }
    
    
    foreach(Limit::where('lottery_id',1)->where('shift','night')->get() as $limit)
    {
        echo $limit->lottery->name;
        echo " - ";
        echo $limit->lottery_type->name;
        echo " - ";
        echo $limit->shift;
        echo " - ";
        echo Setting::currency();
    
        echo number_format($limit->amount,2);
        echo "<br/>";
    
    }
            
    http://laraveldaily.com/eloquent-date-filtering-wheredate-and-other-methods/
    $q->whereDate('created_at', '=', Carbon::today()->toDateString());
    ->where('created_at', '>=', Carbon::today())
    return $model->where('created_at', '>=', \Carbon::today()->toDateString());
    
    Para que esto funcione , esta aqui la solucion: 
    https://stackoverflow.com/questions/40917189/laravel-syntax-error-or-access-violation-1055-error
    
    $productId = DB::table('products_purchases')
        ->groupBy('product_id')
        ->orderBy(DB::raw('SUM(quantity)'), 'desc')
        ->value('product_id');
        
        SELECT g.first,g.second,g.third, sum(gt.amount) as total FROM games g, game_ticket gt 
        WHERE g.id = gt.game_id AND g.lottery_id = 2
        GROUP BY g.first,g.second,g.third 
        ORDER BY total DESC;
        
        SELECT * FROM games WHERE lottery_id = 2;
        
        
        User::with('tickets.games')->whereHas('games',función($this){
            $this->where(condición);
        })
        
        https://laracasts.com/discuss/channels/eloquent/sum-on-relation-column-in-collection?page=1****
        
        Project::join('variations', 'variations.project_id', '=', 'projects.id')
                ->where('projects.status', '=', 'ongoing')
                ->groupBy('projects.id')
                ->get(['projects.id', DB::raw('sum(variations.value) as value')])
                ->sum('value');
                
                
                Ticket::join('game_ticket','game_ticket.ticket_id','=','tickets.id')
                            ->where(function($query) use ($user){
                                if(!is_null($user))
                                {
                                    return $query->where('user_id',$user->id);
                                }
                            })
                            ->where('blocked',false)
                            ->where(function($query) use ($start,$end){
                
                                if(is_null($start) && is_null($end))
                                    return  $query->where('tickets.created_at','>=',Carbon::today()->toDateString());
                
                                if(!is_null($start) && is_null($end))
                                    return  $query->where('tickets.created_at','>=',$start->toDateString());
                
                                if(is_null($start) && !is_null($end))
                                    return  $query->where('tickets.created_at','<=',$end->toDateString());
                
                                if(!is_null($start) && !is_null($end))
                                {
                                    $query->where('tickets.created_at','>=',$start->toDateString());
                                    $query->where('tickets.created_at','<=',$end->toDateString());
                                }
                            })
                            ->groupBy('tickets.id')
                            ->get(['tickets.id', DB::raw('sum(game_ticket.amount) as amount')])
                            ->sum('amount');
                            
                            https://stackoverflow.com/questions/30682421/how-to-protect-image-from-public-view-in-laravel-5
                            
                            
                                $input  = '11/06/1990';
                                $format = 'd/m/Y';
                                
                                $date = Carbon\Carbon::createFromFormat($format, $input)
                                
                                $date = new Carbon('2016-01-23');
                                
                                leidsa 54 38 26
                                Nacional 36 86 1
                               
                               
Triple relationship selector passing values $week and $day

$record = User::where('id',$client->id)
    ->with(['orders'=>function($q){
        return $q->where('active',true); //Active order
    },'orders.records'=>function($q) use ($week,$day){
        $q->where('week',$week); //Record Week
        if(!is_null($day))
            $q->where('day',$day); //Record Day
}])->first()->orders->first()->records->first();

Espuela 

$ ssh espuela0@185.56.85.210 -p18765

$u = App\User::onlyTrashed()->whereIn('id',$ids)->get()
$u = App\User::onlyTrashed()->whereIn('id',$ids)->forceDelete();

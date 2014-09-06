<?php namespace Larabook\Users;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Eloquent;
use Illuminate\Support\Facades\Hash;
use Larabook\Registration\Events\UserRegistered;
use Laracasts\Commander\Events\EventGenerator;
use Laracasts\Presenter\PresentableTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait, EventGenerator, PresentableTrait;

    /**
     * Which fields may be mass assigned
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password'];

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    /**
     * Path to the presenter for a user
     *
     * @var string
     */
    protected $presenter = 'Larabook\Users\UserPresenter';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    /**
     * @param $password
     */
    public function setPasswordAttribute($password){

        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     * @return static
     */
    public static function register($username, $email, $password){

        $user = new static(compact('username' , 'email' , 'password'));

        $user->raise(new UserRegistered($user));

        return $user;
    }

    /**
     * A user has many statuses
     *
     * @return mixed
     */
    public function statuses(){

        return $this->hasMany('Larabook\Statuses\Status');
    }

    public function is($user){

        if(is_null($user)) return false;

        return $this->username == $user->username;
    }

    public function follows(){

        return $this->belongsToMany(self::class, 'follows', 'follower_id', 'followed_id')->withTimestamps();
    }

    /**
     * Determine if user follows otheruser
     *
     * @param User $otherUser
     * @return bool
     */
    public function isFollowedBy(User $otherUser)
    {
        $idsWhoOtherUserFollows = $otherUser->follows()->lists('followed_id');

        return in_array($this->id, $idsWhoOtherUserFollows);
    }
}

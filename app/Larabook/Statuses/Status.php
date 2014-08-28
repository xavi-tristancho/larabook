<?php namespace Larabook\Statuses;

use Larabook\Statuses\Events\StatusWasPublished;
use Laracasts\Commander\Events\EventGenerator;

class Status extends \Eloquent{

    use EventGenerator;

    /**
     * Fillable fields for new status
     *
     * @var array
     */
    protected $fillable = ['body'];

    /**
     * Publish a new status
     *
     * @param $body
     * @return static
     */
    public static function publish($body)
    {
        $status = new static(compact('body'));

        $status->raise(new StatusWasPublished($body));

        return $status;
    }

    /**
     * A status belogns to a user
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belognsTo('Larabook\Users\User');
    }
}
<?php namespace Larabook\Statuses;


use Larabook\Users\User;

class StatusRepository {

    public function getAllForUser(User $user)
    {
        return $user->statuses()->latest()->get();
    }

    /**
     * Get the feed for a user
     * @param User $user
     * @return mixed
     */
    public function getFeedForUser(User $user){

        $userIds = $user->follows()->lists('followed_id');

        $userIds[] = $user->id;

        return Status::whereIn('user_id', $userIds)->latest()->get();
    }

    /**
     * Save a new Status for a user
     *
     * @param Status $status
     * @param $userId
     * @return mixed
     */
    public function save(Status $status, $userId)
    {
        return User::findOrFail($userId)
            ->statuses()
            ->save($status);
    }
}
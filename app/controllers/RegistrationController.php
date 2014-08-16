<?php

class RegistrationController extends \BaseController {

	/**
	 * Show the form for registering a user.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('registration.create');
	}

    /**
     * Create a new Larabook user.
     *
     * @return Response
     */
    public function store()
    {
        return Redirect::home();
    }
}

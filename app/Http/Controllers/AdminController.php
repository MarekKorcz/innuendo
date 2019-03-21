<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function userList()
    {
        $users = User::where('isAdmin', null)->orderBy('created_at', 'desc')->get();

        return view('admin.user_list')->with('users', $users);
    }
    
    /**
     * Display specific resource.
     *
     * @param integer $id
     * @return Response
     */
    public function userShow($id)
    {
        if ($id !== null)
        {
            $user = User::where('id', $id)->first();
            $bosses = User::where('isBoss', 1)->get();
            $properties = $user->getPlaces();
            
            $subscriptions = new Collection();
            
            foreach ($properties as $property)
            {
                $propertySubscriptions = $property->subscriptions;
                
                foreach ($propertySubscriptions as $propertySubscription)
                {
                    if (!$subscriptions->contains($propertySubscription))
                    {
                        $subscriptions->push($propertySubscription);
                    }
                }
            }
            
            dump($subscriptions);die;

            return view('admin.user_show')->with([
                'user' => $user,
                'bosses' => $bosses
            ]);
        }
        
        return redirect()->route('welcome');
    }
    
    /**
     * Edits specific resource.
     *
     * @param integer $id
     * @return Response
     */
    public function userEdit(Request $request)
    {
        dump($request->request->all());die;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

    public $room;

    /**
     * HomeController constructor.
     * @param Room $room
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = $this->room->paginate(config('room.page_size'));
        return view('home' , ['rooms' => $rooms]);
    }

}

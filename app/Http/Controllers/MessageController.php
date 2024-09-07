<?php
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Events\NewMessageNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Events\MyEvent;
 
class MessageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
 
    public function index()
    {
        $user_id = Auth::user()->id;
        $data = array('user_id' => $user_id);
 
        return view('broadcast', $data);
    }
 
    public function send()
    {
        // ... 
         
        // message is being sent 
        $message = new Message;
        $message->setAttribute('from', 1);
        $message->setAttribute('to', 2);
        $message->setAttribute('message', '11Demo message from user 1 to user 2');
        $message->save();
         
        // want to broadcast NewMessageNotification event 
        //event(new NewMessageNotification($message));
        // event(new NewMessageNotification($message));
        event(new MyEvent('Hello World!'));

         
        // ... 
    }


    public function p_index()
    {
        
        $user_id = Auth::user()->id;
        $data = array('user_id' => $user_id);
 
        return view('p_broadcast', $data);
    }
 
    public function p_send()
    {
        // ... 
         
        // message is being sent 
        $message = new Message;
        $message->setAttribute('from', 1);
        $message->setAttribute('to', 2);
        $message->setAttribute('message', '11Demo message from user 1 to user 2');
        $message->save();
         
        // want to broadcast NewMessageNotification event 
        //event(new NewMessageNotification($message));
        event(new NewMessageNotification($message));
        
         
        // ... 
    }
    public function triggerEvent(){
        $data = ['message' => 'Hello, World!'];
        broadcast(new MyEvent($data));

        return response()->json(['status' => 'Event broadcasted']);
    }
}
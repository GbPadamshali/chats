<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        return DataTables::of($users)
                            ->editColumn('name', function($users) {
                                return Str::limit($users->name, 20, '...');
                            })
                            ->editColumn('mobile', function($users) {
                                if (!empty($users->mobile)) {
                                    return $users->mobile;
                                } else {
                                    return '--';
                                }
                            })
                            ->addColumn('action', function($users){
                                // $html = '<a href="'.route('chat.user_chat', $users->id).'" class="btn btn-primary"><strong>Chat</strong></a> | <a href="#" class="btn btn-warning"><strong>Mark all as Read</strong></a>';
                                $html = '<a href="'.route('chat.user_chat', $users->id).'" class="btn btn-primary"><strong>Chat</strong></a>';
                                return $html;
                            })
                            ->rawColumns(['name', 'mobile', 'action'])
                            ->make(true);
    }

    public function chatPage(Request $request, $id)
    {
        $user = User::find($id);
        // dd($user);
        return view('chats', compact('user'));
    }

    public function getMessages(Request $request)
    {
        $input = $request->except('_token', 'count');
        try {
            if ($request->count == 1) {
                $messages = DB::table('chats')
                                ->join('users', 'chats.receiver_id', '=', 'users.id')
                                ->select('chats.*', 'users.name as receiver_name', 'users.email')
                                ->where('chats.sender_id', '=', $input['receiver_id'])
                                ->where('chats.receiver_id', '=', $input['sender_id'])
                                ->get();
            } else {
                $messages = DB::table('chats')
                                ->join('users', 'chats.receiver_id', '=', 'users.id')
                                ->select('chats.*', 'users.name as receiver_name', 'users.email')
                                ->where('chats.sender_id', '=', $input['sender_id'])
                                ->where('chats.receiver_id', '=', $input['receiver_id'])
                                ->where('chats.read_at', '=', null)
                                ->get();
            }
            $response = json_encode([
                'status' => true,
                'status_messge' => 'Success',
                'messages' => $messages
            ]);
        } catch (Illuminate\Database\QueryException $e) {
            $response = json_encode([
                'status' => false,
                'status_messge' => 'Something went wrong. Please, try again.'
            ]);
        }
        return $response;
    }

    public function readMessages(Request $request)
    {
        $input = $request->except('_token');
        $update_array = ['read_at' => Carbon::now()];
        try {
            $read_messages = Chat::where('sender_id', $input['receiver_id'])
                                    ->where('receiver_id', $input['sender_id'])
                                    ->update($update_array);
            $response = json_encode([
                'status' => true,
                'messsage' => 'Messages read successfully'
            ]);
        } catch (Illuminate\Database\QueryException $e) {
            $response = json_encode([
                'status' => false,
                'messsage' => 'Messages can not be read successfully'
            ]);
        }
        return $response;
    }

    public function sendMessage(Request $request)
    {
        $input = $request->except('_token');
        $input['sent_at'] = Carbon::now();
        try {
            $send_message = Chat::create($input);
            $response = json_encode([
                'status' => true,
                'message' => 'Message sent successfully.'
            ]);
        } catch (Illuminate\Database\QueryException $e) {
            $response = json_encode([
                'status' => false,
                'message' => 'Message not sent successfully.',
                // 'error' => $e->getMessage()
            ]);
        }
        return $response;
    }
}

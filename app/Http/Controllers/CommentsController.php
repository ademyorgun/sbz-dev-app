<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Appointment;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->input('id');
        $appointment = Appointment::findOrFail($id);
        
        $comments = $appointment->comments;

        $currentUserRole = auth()->user()->role->name;

        // the appointment has been duplicated
        // we also load the comments of the 
        // appointment duplicated from
        $previousAppointmentComments = [];
        if($appointment->duplicated_from_id != null && $currentUserRole == 'SuperAdmin') {
            $previousAppointmentComments = Appointment::findOrFail($appointment->duplicated_from_id)->comments;
        }

        $currentUser = auth()->user();
        $creator = Appointment::findOrFail($id)->user;

        foreach ($comments as $key => $comment) {
            $comment->user_username = $comment->user->user_name;
            $comment->avatar = $comment->user->avatar;
        };
        foreach($previousAppointmentComments as $key => $comment) {
            $comment->user_username = $comment->user->user_name;
            $comment->avatar = $comment->user->avatar;
        }
        return response()->json([
            'comments' => $comments,
            'previousAppointmentComments' => $previousAppointmentComments,
            'current_user' => $currentUser,
            'creator' => $creator
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // $request->validate([
        //     'appointmentId' => 'required'
        // ]);
        
        //get the uploaded file
        // $file = $request->file('image');
        //get the reply body


        $user = auth()->user();
        $comment = new Comment();
        $comment->body = $request->reply;
        $comment->appointment_id = $request->appointmentId;
        $comment->user_id = $user->id;
        
        // todo upload image to digital ocean + save the url to db
            $path = $request->file('image')->store('comment-images','s3');
        // $comment->comment_image = $path;
        
        $comment->user_username = $user->user_name;
        $comment->avatar = $comment->user->avatar;

        $comment->save();
        
        return response()->json([
            'comment' => $comment
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contact = Contact::latest()->first();
        return view('backend.admin.contact.index', compact('contact'));
    }

    public function updatePhone(Request $request)
    {
        Contact::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['success'=>'done']);
    }

    public function updateFacebook(Request $request)
    {
        Contact::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['success'=>'done']);
    }

    public function updateGmail(Request $request)
    {
        Contact::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['success'=>'done']);
    }

    public function updateAddress(Request $request)
    {
        Contact::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['success'=>'done']);
    }
}

<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\PaymentProvider;
use App\Http\Controllers\Controller;

class PaymentProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = PaymentProvider::orderBy('id', 'DESC')->get();
        return view('backend.admin.payments.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.payments.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // return $request->all();
        $validateData = $this->validate($request, [
            'name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            // 'phone_number' => 'required|phone:MM',
            'phone_number' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,gif'
        ]);

        // if ($request->hasFile('image')) {
        //     $dir = 'payment/';
        //     // get image extension
        //     $extension = strtolower($request->file('image')->getClientOriginalExtension());
        //     // rename image
        //     $fileName = uniqid() . '.' . $extension;
        //     $request->file('image')->move($dir, $fileName);
        // }
        $image = $request->file('image');
        $path = $image->store('payments');
        
        PaymentProvider::create([
            'name' => $request->name,
            'owner' => $request->owner,
            'phone_number' => $request->phone_number,
            'image' => $path
        ]);
    
        return redirect()->route('providers.index')->with('success', 'Payment Provoider created successfully');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider = PaymentProvider::find($id);
        return view('backend.admin.payments.edit', compact('provider'));
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
        $validateData = $this->validate($request, [
            'name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            // 'phone_number' => 'required|phone:MM',
            'phone_number' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,gif,png'
        ]);

        $payment = PaymentProvider::findOrFail($id);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('payments');
        } else {
            $path= $payment->image;
        }
            
        $payment->update([
            'name' => $request->name,
            'owner' => $request->owner,
            'phone_number' => $request->phone_number,
            'image' => $path
        ]);
    
        return redirect()->route('providers.index')->with('success', 'Payment Provider updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // PaymentProvider::find($id)->delete();
        // return redirect()->route('payments.index')->with('success','Payment Provider deleted successfully');
    }
}

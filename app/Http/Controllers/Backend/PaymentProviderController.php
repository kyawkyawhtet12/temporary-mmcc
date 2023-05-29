<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminPaymentProvider;

class PaymentProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = AdminPaymentProvider::orderBy('id', 'DESC')->get();
        return view('backend.admin.payment_provider.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.payment_provider.create');
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
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            // 'phone_number' => 'required|phone:MM',
            'phone_number' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,gif'
        ]);

        $image = $request->file('image');
        $fileName = uniqid('img').'.'.$image->extension();
        $path = $image->storeAs('payments', $fileName, 'public');
        $file_path = request()->getSchemeAndHttpHost() . '/storage/' . $path;

        AdminPaymentProvider::create([
            'name' => $request->name,
            'owner' => $request->owner,
            'phone_number' => $request->phone_number,
            'image' => $file_path
        ]);

        return redirect()->route('providers.index')
                    ->with('success', 'Payment Provoider created successfully');
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
        $provider = AdminPaymentProvider::find($id);
        return view('backend.admin.payment_provider.edit', compact('provider'));
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

        $payment = AdminPaymentProvider::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = uniqid('img').'.'.$image->extension();
            $path = $image->storeAs('payments', $fileName, 'public');
            $file_path = request()->getSchemeAndHttpHost() . '/storage/' . $path;
        } else {
            $file_path= $payment->image;
        }

        $payment->update([
            'name' => $request->name,
            'owner' => $request->owner,
            'phone_number' => $request->phone_number,
            'image' => $file_path
        ]);

        return redirect()->route('providers.index')->with('success', 'Payment Provider updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $provider = AdminPaymentProvider::find($id);

        if ($request->status == 0) {
            $provider->update([ 'status' => 0 ]);
            $status = "closed";
        } else {
            $provider->update([ 'status' => 1 ]);
            $status = "opened";
        }

        return redirect()->route('providers.index')
                        ->with('success', "Payment Provider {$status} successfully");
    }
}

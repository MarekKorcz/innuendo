<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Month;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\TempProperty;
use App\Models\User;
use App\Models\TempUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Redirect;

class PropertyController extends Controller
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
    public function index()
    {
        $properties = Property::where('id', '!=', 0)->get();

        if (count($properties) > 0)
        {
            foreach ($properties as $property)
            {
                if ($property->boss_id > 0)
                {
                    $property['boss'] = User::where('id', $property->boss_id)->first();
                }
            }
        }

        $tempProperties = TempProperty::where('id', '!=', 0)->get();

        if (count($tempProperties) > 0)
        {
            foreach ($tempProperties as $tempProperty)
            {
                if ($tempProperty->temp_user_id > 0)
                {
                    $tempProperty['owner'] = TempUser::where('id', $tempProperty->temp_user_id)->first();
                }
            }
        }

        return view('property.index')->with([
            'properties' => $properties,
            'tempProperties' => $tempProperties
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $users = User::where([
            'isAdmin' => null,
            'isEmployee' => null,
            'isBoss' => 1,
            'boss_id' => null
        ])->get();

        return view('property.create')->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'name'          => 'required',
            'street'        => 'required',
            'city'          => 'required'
        );
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('property/create')
                ->withErrors($validator);
        } else {

            $bossId = null;

            if (request()->input('user') !== 0)
            {
                $boss = User::where([
                    'id' => request()->input('user'),
                    'isAdmin' => null,
                    'isEmployee' => null,
                    'isBoss' => 1,
                    'boss_id' => null
                ])->first();

                if ($boss !== null)
                {
                    $bossId = $boss->id;
                }
            }

            $property = new Property();
            $property->name          = request()->input('name');
            $property->slug          = Str::slug(request()->input('name'));
            $property->description   = request()->input('description');
            $property->street        = request()->input('street');
            $property->street_number = request()->input('street_number');
            $property->house_number  = request()->input('house_number');
            $property->city          = request()->input('city');
            $property->boss_id       = $bossId !== null ? $bossId : auth()->user()->id;
            $property->save();

            return redirect('/property/index')->with('success', 'Property successfully created!');
        }
    }

    /**
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $property = Property::where('id', $id)->with([
            'boss',
            'years'
        ])->first();

        if ($property !== null)
        {
            return view('property.show')->with([
                'property' => $property
            ]);
        }

        return redirect()->route('welcome')->with('error', 'There is no such property');
    }

    public function canShowChange($id)
    {
        $property = Property::where('id', $id)->first();

        if ($property !== null)
        {
            if ($property->canShow == 0)
            {
                $property->canShow = 1;

            } else if ($property->canShow == 1) {

                $property->canShow = 0;
            }

            $property->save();

            return redirect()->action(
                'PropertyController@show', [
                    'id' => $property->id
                ]
            );
        }

        return redirect()->route('welcome');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function tempPropertyShow($id)
    {
        $tempProperty = TempProperty::where('id', $id)->with('tempUser')->first();

        if ($tempProperty !== null)
        {
            return view('property.temp_property_show')->with([
                'tempProperty' => $tempProperty
            ]);
        }

        return redirect()->route('welcome')->with('error', 'There is no such temporary property');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $property = Property::where('id', $id)->first();

        if ($property !== null)
        {
            $bosses = User::where([
                'isAdmin' => null,
                'isEmployee' => null,
                'isBoss' => 1,
                'boss_id' => null
            ])->get();

            $admins = User::where([
                'isAdmin' => 1,
                'isEmployee' => null,
                'isBoss' => null,
                'boss_id' => null
            ])->get();

            if (count($admins) > 0)
            {
                foreach ($admins as $admin)
                {
                    $bosses->push($admin);
                }

                $property['bosses'] = $bosses;

                return view('property.edit')->with('property', $property);
            }
        }

        return redirect()->route('welcome');
    }

    public function tempPropertyEdit($id)
    {
        $tempProperty = TempProperty::where('id', $id)->first();

        if ($tempProperty !== null)
        {
            return view('property.temp_property_edit')->with('tempProperty', $tempProperty);
        }

        return redirect()->route('welcome');
    }

    public function update($id)
    {
        $rules = array(
            'name'          => 'required',
            'street'        => 'required',
            'city'          => 'required'
        );
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/property/' . $id . '/edit')
                ->withErrors($validator);
        } else {

            $property = Property::where('id', $id)->first();

            if ($property !== null)
            {
                $property->name          = request()->input('name');
                $property->slug          = Str::slug(request()->input('name'));
                $property->street        = request()->input('street');
                $property->street_number = request()->input('street_number');
                $property->house_number  = request()->input('house_number');
                $property->city          = request()->input('city');

                $boss = null;

                if (request()->input('boss_id') !== 0)
                {
                    $boss = User::where([
                        'id' => request()->input('boss_id'),
                        'isAdmin' => null,
                        'isEmployee' => null,
                        'isBoss' => 1,
                        'boss_id' => null
                    ])->first();
                }

                if ($boss !== null)
                {
                    $property->boss_id = $boss->id;

                } else {

                    $property->boss_id = 0;
                }

                $property->save();

                return redirect('/property/' . $property->id)->with('success', 'Property successfully updated!');
            }

            return redirect()->route('welcome')->with('error', 'There is no such property');
        }
    }

    public function invoiceDataCreate($id)
    {
        $property = Property::where('id', $id)->with([
            'invoiceData'
        ])->first();

        if ($property !== null)
        {
            if ($property->invoiceData == null)
            {
                return view('property.invoice_data_create')->with('property', $property);

            } else {

                return redirect()->action(
                    'PropertyController@invoiceList', [
                        'property' => $property->id
                    ]
                )->with('success', 'Invoice data has been already added!');
            }
        }
    }

    public function invoiceDataStore()
    {
        $rules = array(
            'company_name' => 'required',
            'email'        => 'required',
            'phone_number' => 'required',
            'nip'          => 'required',
            'property_id'  => 'required'
        );
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('property/invoice-data/create/' . request()->input('property_id'))
                ->withErrors($validator);
        } else {

            $property = Property::where('id', request()->input('property_id'))->with([
                'invoiceData'
            ])->first();

            if ($property !== null && $property->invoiceData == null)
            {
                $invoiceData = new InvoiceData();
                $invoiceData->company_name  = request()->input('company_name');
                $invoiceData->email         = request()->input('email');
                $invoiceData->phone_number  = request()->input('phone_number');
                $invoiceData->nip           = request()->input('nip');
                $invoiceData->property_id   = request()->input('property_id');
                $invoiceData->save();

                return redirect('/property/invoice/list/' . $property->id)->with('success', 'Invoice data has been created!');
            }
        }
    }

    public function invoiceDataEdit($id)
    {
        $property = Property::where('id', $id)->with([
            'invoiceData'
        ])->first();

        if ($property !== null && $property->invoiceData !== null)
        {
            return view('property.invoice_data_edit')->with([
                'property' => $property,
                'invoiceData' => $property->invoiceData
            ]);
        }

        return redirect()->route('home');
    }

    public function invoiceDataUpdate()
    {
        $rules = array(
            'company_name' => 'required',
            'email'        => 'required',
            'phone_number' => 'required',
            'nip'          => 'required',
            'property_id'  => 'required'
        );
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/property/invoice-data/edit/' . request()->input('property_id'))
                ->withErrors($validator);
        } else {

            $property = Property::where('id', request()->input('property_id'))->with('invoiceData')->first();

            if ($property !== null && $property->invoiceData !== null)
            {
                $invoiceData = $property->invoiceData;

                $invoiceData->company_name  = request()->input('company_name');
                $invoiceData->email         = request()->input('email');
                $invoiceData->phone_number  = request()->input('phone_number');
                $invoiceData->nip           = request()->input('nip');
                $invoiceData->save();

                return redirect('/property/invoice/list/' . $property->id)->with('success', 'Invoice data has been successfully updated!');
            }
        }
    }

    public function invoice($id)
    {
        $invoice = Invoice::where('id', $id)->with([
            'month.year',
            'property'
        ])->first();

        if ($invoice !== null)
        {
            return view('property.property_invoice')->with([
                'invoice' => $invoice
            ]);
        }
    }

    public function invoiceList($id)
    {
        $property = Property::where('id', $id)->with([
            'invoices.month.year',
            'invoiceData'
        ])->first();

        if ($property !== null)
        {
            return view('property.property_invoices')->with([
                'property' => $property,
                'invoices' => $property->invoices->sortBy('month.id')
            ]);
        }
    }

    public function addInvoice($id)
    {
        $property = Property::where('id', $id)->with([
            'invoiceData',
            'years.months'
        ])->first();

        if ($property !== null)
        {
            return view('property.property_add_invoice')->with([
                'property' => $property
            ]);
        }

        return redirect()->route('welcome')->with('error', 'There is no such property');
    }

    public function invoiceDestroy($id)
    {
        $invoice = Invoice::where('id', $id)->with([
            'property'
        ])->first();

        if ($invoice !== null)
        {
            $property = $invoice->property;

            $invoice->delete();

            // remove file from storage folder
            Storage::disk('local_pdf')->delete($invoice->invoice);

            return redirect()->action(
                'PropertyController@invoiceList', [
                    'property' => $property->id
                ]
            )->with('success', 'Invoice has been succesfully deleted!');
        }

        return redirect()->route('welcome')->with('error', 'There is no such invoice');
    }

    public function addInvoiceUpdate()
    {
        $rules = array(
            'property_id' => 'required',
            'invoice'     => 'required'
        );
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/property/add-invoice/' . request()->input('property_id'))
                ->withErrors($validator);
        } else {

            $property = Property::where('id', request()->input('property_id'))->with([
                'invoiceData',
                'invoices'
            ])->first();

            $month = Month::where('id', request()->input('month_id'))->with('year')->first();

            $file = Input::file('invoice');

            if ($property !== null && $month !== null && $file !== null)
            {
                $invoice = new Invoice();
                $invoice->month_id = $month->id;
                $invoice->property_id = $property->id;

                $fileName =
                    $property->name . '_' .
                    $month->month . '_' .
                    $month->year->year . '_' .
                    time() .
                    '.pdf';

                Storage::disk('local_pdf')->put($fileName, File::get($file));

                $invoice->invoice = $fileName;
                $invoice->save();

                return redirect('/property/invoice/list/' . $property->id)->with('success', 'Invoice has been successfully added!');
            }

            return redirect('/property/index');
        }
    }

    public function tempPropertyUpdate($id)
    {
        $rules = array(
            'name'          => 'required',
            'street'        => 'required',
            'street_number' => 'required',
            'house_number'  => 'required',
            'city'          => 'required'
        );
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/property/' . $id . '/edit')
                ->withErrors($validator);
        } else {

            $tempProperty = TempProperty::where('id', $id)->first();
            $tempProperty->name          = request()->input('name');
            $tempProperty->slug          = Str::slug(request()->input('name'));
            $tempProperty->street        = request()->input('street');
            $tempProperty->street_number = request()->input('street_number');
            $tempProperty->house_number  = request()->input('house_number');
            $tempProperty->city          = request()->input('city');
            $tempProperty->save();

            return redirect('/temp-property/' . $tempProperty->id)->with('success', 'Temporary property successfully updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $property = Property::where('id', $id)->first();

        if ($property !== null)
        {
            $property->delete();

            return redirect('/property/index')->with('success', 'Property deleted!');
        }

        return redirect()->route('welcome')->with('error', 'There is no such property');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function tempPropertyDestroy($id)
    {
        $tempProperty = TempProperty::where('id', $id)->first();

        if ($tempProperty !== null)
        {
            $tempProperty->delete();

            return redirect('/property/index')->with('success', 'Temporary property deleted!');
        }

        return redirect()->route('welcome')->with('error', 'There is no such temporary property');
    }
}

<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Company;
use Illuminate\Http\Request;

class CustomersController extends Controller {

    /**
     * Reads data from the database into the view on window load
     */
    public function index () {

        $activeCustomers = Customer::active()->get();
        $inActiveCustomers = Customer::inActive()->get();
        $allCustomers = Customer::all();


        /**
         * `Compact()` method is simply used to pass multiple data to the view.
         *
         * Meanwhile in the view code, you can change between active and inactive
         * Customers to see results
         */
        return view('customers.index', compact(
            'activeCustomers',
            'inActiveCustomers',
            'allCustomers'
        ));
    }

    /**
     * Displays the Create View
     */
    public function create() {

        $companies = Company::all();
        $customer = new Customer();

        return view('customers.create', compact('companies', 'customer'));

    }


    /**
     * Stores Data in the database and afterwards, should redirect
     * the user to the `show()` method.
     */
    public function store (Request $request) {

        /**Recieves request data,converts it to array */
        $data = (array)json_decode($request->input('data'));

        /**Create a customer with the return data array*/
        $customer = Customer::create($data);

        /***
         * Supposed to redirect to show view but nope
         * i am using a dialog on the front end to display
         * the created record details returned through a response
         * from an Ajax request
         */
        //return redirect('customers');

        /**Returns a response to the calling Ajax Post
         * Note:
         *      This gave us a mass assignment error
         *      and to fix it with had to tell laravel
         *      how many fields it should always expect
         *      and fill. That was done in the Customer
         *      model class using the protected $fillable
         *      array variable.
        */
        return response()->json(['customerData' => $customer, 'customerCompany' => $customer->company->name]);

    }

    /**
     * Basically takes us to the view where we could
     * see details about what we `created` and `stored`
     */
    public function show(Request $request) {

    }

    public function edit(Customer $customer) {
        $companies = Company::all();
        return view('customers.edit', compact('customer', 'companies'));
    }

    public function update(Customer $customer) {
        /**Recieves request data,converts it to array */
        $data = (array) json_decode(request()->input('data'));

        /**Create a customer with the return data array*/
        $customer->update($data);

        return response()->json(['customerData' => $customer, 'customerCompany' => $customer->company->name]);
    }

    public function destroy(Customer $customer) {
        $deleted = $customer->delete();

        return response()->json(['deleted' => $deleted]);
    }

}
